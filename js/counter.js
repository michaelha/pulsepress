jQuery(function($) {
	
	disable_submit();
	$("#posttext").keypress(disable_submit).focusout(disable_submit);
	
	// lets try to shorten the urls 
	$("#shorten-url").click(function(){
		
		var words = jQuery("#posttext").val().split(" ");
		var num_of_words =  words.length;
		var i=0;
		var urls = new Array();
		if(words[0]){
		for (i=0;i<num_of_words;i++){
			
			if( words[i].substring(4,0)  == "http" && 
			    words[i].substring(13,0) != "http://bit.ly" && // ignore bitly as well 
			    words[i].substring(11,0) != "http://j.mp" && // ignore bitly as well 
			    words[i].substring(13,0) != "http://goo.gl") {
			  urls.push(words[i]); // collect all the urls
			 
			}
		}
		}
		
		if(urls[0]) {
		
		
		urls = unique(urls); // only
		var num_of_urls = urls.length;
		for (i=0;i<num_of_urls;i++){
			// let shorten that url
	
			googlurl(urls[i],words,1);
		
		}
		}
		
		
	return false;
	});
});

function disable_submit(){
	// we need atime out to have a better reading of what is really there. 
	timeout = setTimeout(function() {
		var remainder = 140 - jQuery("#posttext").val().length;
		
		if(remainder < 0) {
			 jQuery('#submit').attr('disabled','disabled').addClass('disabled');
			
		} else{
			  jQuery('#submit').removeAttr('disabled').removeClass('disabled');
		}
		// update the counter
		jQuery('#post-count').html(remainder);
	},50);
}

// http://davidbau.com/archives/2011/01/30/using_googl_with_jsonlib.html
if(typeof jsonlib!="object")jsonlib={};
(function(g){function s(b){var a=document.createElement("script"),c=false;a.src=b;a.async=true;a.onload=a.onreadystatechange=function(){if(!c&&(!this.readyState||this.readyState==="loaded"||this.readyState==="complete")){c=true;a.onload=a.onreadystatechange=e;a&&a.parentNode&&a.parentNode.removeChild(a)}};k||(k=document.getElementsByTagName("head")[0]);k.appendChild(a)}function h(b,a,c,d,m){var n="?",f="jsonlib_cb_"+ ++t,o;a=a||{};for(key in a)if(a.hasOwnProperty(key))n+=encodeURIComponent(key)+"="+encodeURIComponent(a[key])+"&";i[f]=function(j){clearTimeout(o);i[f]=e;if(c){if(d)j=j.hasOwnProperty(d)?j[d]:m;c(j)}try{delete i[f]}catch(v){}};o=setTimeout(function(){i[f]=e;c(m);try{delete i[f]}catch(j){}},u);s(b+n+"callback="+f);return f}function p(b){return typeof b.url=="string"&&b.url.indexOf("https:")==0?q:l}function r(b,a,c){if(typeof b=="string")a={url:a,select:b,a:"text"};else{a={url:a,a:"text"};for(var d in b)if(b.hasOwnProperty(d))a[d]=b[d]}h(p(a)+"fetch",a,c,"content",e)}var t=(new Date).getTime(),k,i=this,l="http://call.jsonlib.com/",q="https://jsonlib.appspot.com/",u=6E3,e=null;g.ip=function(b){h(l+"ip",{},b,"ip",e)};g.time=function(b){h(l+"time",{},b,"time",e)};g.fetch=function(b,a){if(typeof b=="string")b={url:b};h(p(b)+"fetch",b,a,e,{error:"timeout"})};g.scrape=r;g.scrapeattr=function(b,a,c,d){r({select:a,a:"attr_"+b},c,d)};g.urandom=function(b,a){if(arguments.length==1){a=b;b={}}h(q+"urandom",b,a,"urandom",e)}})(jsonlib);

function googlurl(url,words,repeats) {
  
  jsonlib.fetch({
    url: 'https://www.googleapis.com/urlshortener/v1/url',
    header: 'Content-Type: application/json',
    data: JSON.stringify({longUrl: url})
  }, function (m) {
    var result = null;
    try {
      result = JSON.parse(m.content).id;
      console.log(m);
      if (typeof result != 'string') result = null;
    } catch (e) {
      result = null;
    }
    if(result) {
    	if(result.length < url.length){
    		replace_word(result, url, words);
    		disable_submit();
    	}
    } else {
    	// lets try it again
    	// but stop after ten times 
    	if(repeats < 50){
    	repeats++;
    	googlurl(url,words,repeats);
    	}
    	
   		
    }
  });
}

function replace_word(result, url,words){
	// 
	var num_of_words =  words.length;
	var i=0;
		
	if(words[0]) {
		for (i=0;i<num_of_words;i++) {
		
			if(words[i] == url) {
				words[i] = result;
				
			}
		}
	}
	// put the words 
		
	jQuery("#posttext").val(words.join(" "));
	

}
/**
 * Returns a unique array 
 */
function unique(a) {
	tmp = new Array(0);
	for(i=0;i<a.length;i++){
		if(!contains(tmp, a[i])){
			tmp.length+=1;
			tmp[tmp.length-1]=a[i];
		}
	}
	return tmp;
}
/**
 * Returns true if 's' is contained in the array 'a'
 */
function contains(a, e) {
	for(j=0;j<a.length;j++)if(a[j]==e)return true;
	return false;
}
