function post_render()
{
	SyntaxHighlighter.highlight();
	MathJax.Hub.Queue(
		["Typeset",MathJax.Hub]
	);
	mermaid.initialize({startOnLoad:false});
	mermaid.init(undefined, ".mermaid");
}

function frame_init()
{
	//alert(window.location.href);
	window.id_filter = 0;
	window.tag_filter = 'all';
	window.time_filter = '8000-1-1';
	window.keyword_filter = '';
	window.nowBlogTime = '8000-1-1';

	var temp = geturlargs('id');
	if(temp != '')
		window.id_filter = temp;

	var temp = geturlargs('tag');
	if(temp != '')
		window.tag_filter = temp;

	var temp = geturlargs('time');
	if(temp != '')
		window.time_filter = temp;

	var temp = decodeURIComponent(geturlargs('key'));
	if(temp != "''")
		window.keyword_filter = temp.replace(/(^'|'$)/g,'');

	if(document.all)
	{
		//alert('为了您更好的浏览体验，请不要使用IE浏览器。');
	}

	var ButtonObj = document.getElementById("more_blogs_button");
	MoreBlogs(ButtonObj);
}

function geturlargs(_name)
{
	var url = window.location.href;
	if(new RegExp("[&?]"+_name+"=([^&#]+).*","gi").test(url)){
		return RegExp.$1;
	}else{
		return '';
	}
}

function getNumBlogs()
{
	var obj = document.getElementById('blogs_column');
	return obj.childNodes.length;
}

function DelayBombSet()
{
	window.DelayBombTimer = setTimeout(function(){DelayBombCallbk()},15000);

	var noticeObj = document.getElementById('more_blogs_notice');
	noticeObj.innerHTML = '';
}

function DelayBombCutOut()
{
	clearTimeout(window.DelayBombTimer);
}

function DelayBombCallbk()
{
	var Obj = document.getElementById('more_blogs_button');

	ResetLoadButton(Obj, '更多文章');

	var noticeObj = document.getElementById('more_blogs_notice');
	noticeObj.innerHTML = '额。。。网络好像有些慢，再尝试一次吧。';
	noticeObj.innerHTML += '<img src="resource/leave_comment/faces/face32.gif"/>';
}

function MoreBlogs(Obj)
{
	if(ifLoadButtonClicked(Obj))
		return;

	LoadButtonClick(Obj);
	
	var from = getNumBlogs() + 1;
	
//	rand = parseInt(Math.random()*10);
//	if(rand > 2)
	AjaxPost('moreBlogs', "\tfrom:" + from + 
	                    "\n\tid_filter:" + window.id_filter + 
	                    "\n\ttag_filter:" + window.tag_filter + 
	                    "\n\ttime_filter:" + window.time_filter + 
	                    "\n\tkeyword_filter:" + window.keyword_filter + 
	                    "\n", MoreBlogsResponseCallbk , Obj);
	
	DelayBombSet();
}

function MoreBlogsResponseCallbk(Obj)
{
	if(ifResponseComplete())
	{
		DelayBombCutOut();

		//showResponseXML();
		var blogsColumn = document.getElementById('blogs_column');
		var res_array = getResponseTagArray('blog');
		var ret_array = getResponseTagArray('return');
		var left = getTagArraySubTagValue(ret_array[0],'left');
		
		for(var i=0; i<res_array.length; i++)
		{
			//we need to rule out the conflict ID possibility.
			var insertBlogId = getTagArraySubTagValue(res_array[i],'id');
			var conflictObj = document.getElementById('blog_id' + insertBlogId);
			if( conflictObj && conflictObj.parentNode )
				conflictObj.parentNode.removeChild(conflictObj);

			//get current reading blog time
			var nowBlogTime = getTagArraySubTagValue(res_array[i],'time');
			window.nowBlogTime = nowBlogTime;

			blogsColumn.innerHTML += getTagArraySubTagValue(res_array[i],'innerHTML');
		}

		if(left == 'more')
			ResetLoadButton(Obj, '更多文章');
		else
			DisableLoadButton(Obj, '没有了');
		
		post_render();

		var noticeObj = document.getElementById('more_blogs_notice');
		if(blogsColumn.childNodes.length > 19)
		{
			noticeObj.innerHTML = '<img src="resource/icon/info.png"/>';
			noticeObj.innerHTML += '你已加载了很多文章，<a href="javascript:;" onclick="clearBlogsAndReadMore()">单击这里</a>清除以上文章并继续阅读。';
		}
		else
			noticeObj.innerHTML = '';
	}
}

function clearBlogsAndReadMore()
{
	var blogColumn = document.getElementById('blogs_column');

	while(blogColumn.firstChild)
	{
		var old = blogColumn.removeChild(blogColumn.firstChild);
		old = null;
	}
	
	window.time_filter = window.nowBlogTime;
	
	var ButtonObj = document.getElementById("more_blogs_button");
	MoreBlogs(ButtonObj);

	window.scrollTo(0,0);
}

function MoreContent(id,Obj)
{
        LoadButtonClick(Obj);
        AjaxPost('moreContent', "\tid:" + id + "\n", MoreContentResponseCallbk , id );
}

function MoreContentResponseCallbk(id)
{
	if(ifResponseComplete())
	{
	//	showResponseXML();
		var res_array = getResponseTagArray('blog_more');
		var entry = document.getElementById('blog_entry_id' + id);
		entry.innerHTML += getTagArraySubTagValue(res_array[0],'innerHTML');

		var button = document.getElementById('more_content_button_id' + id);
		if(button && button.parentNode)
			button.parentNode.removeChild(button);
		
		post_render();	
	}
}

function updateRecentComments()
{
        AjaxPost('updateRecentComments', '', updateRecentCommentsCallbk , '');
}

function updateRecentCommentsCallbk(no_thing)
{
	if(ifResponseComplete())
	{
		//showResponseXML();
		var res_array = getResponseTagArray('recent_comments');
		var obj = document.getElementById('recent_comments');
		obj.innerHTML = getTagArraySubTagValue(res_array[0],'innerHTML');
	}
}

function updateReaderRank()
{
        AjaxPost('updateReaderRank', '', updateReaderRankCallbk , '');
}

function updateReaderRankCallbk(no_thing)
{
	if(ifResponseComplete())
	{
		//showResponseXML();
		var res_array = getResponseTagArray('reader_rank');
		var obj = document.getElementById('reader_rank');
		obj.innerHTML = getTagArraySubTagValue(res_array[0],'innerHTML');
	}
}
/*
*  Fixed bar
*
*/
function GetZoomFactor() 
{
	var factor = 1;
	if (document.body.getBoundingClientRect) {
		// rect is only in physical pixel size in IE before version 8 
		var rect = document.body.getBoundingClientRect ();
		var physicalW = rect.right - rect.left;
		var logicalW = document.body.offsetWidth;

		// the zoom level is always an integer percent value
		factor = Math.round ((physicalW / logicalW) * 100) / 100;
	}
	return factor;
// always return 1, except at non-default zoom levels in IE before version 8
}

function GetDocumentScrollOffset() 
{
	if('pageXOffset' in window) {  // all browsers, except IE before version 9
		var scrollLeft =  window.pageXOffset;
		var scrollTop = window.pageYOffset;
	}
	else
	{ 
		// Internet Explorer before version 9
		var zoomFactor = GetZoomFactor ();
		var scrollLeft = Math.round (document.documentElement.scrollLeft / zoomFactor);
		var scrollTop = Math.round (document.documentElement.scrollTop / zoomFactor);
	}

//	alert ("The current horizontal scroll amount: " + scrollLeft + "px");
//	alert ("The current vertical scroll amount: " + scrollTop + "px");
	return scrollTop;
}

function GetScrollOffset(obj)
{
	var rect = obj.getBoundingClientRect();
	var zoomFactor = GetZoomFactor();
	var offset = Math.round (rect.top / zoomFactor);

	return offset;
}

function getAbsPos(obj)
{
	return GetScrollOffset(obj) + GetDocumentScrollOffset();
}

function updateFixedBar()
{
	var joey = document.getElementById('left_column_top');
	var walle = document.getElementById('left_column_bottom');

	if(GetDocumentScrollOffset() > getAbsPos(joey) + joey.offsetHeight)
	{
		walle.style.position = "fixed";
		walle.style.top = "0px";
	}
	else
	{
		walle.style.position = "static";
	}
}

/* run code below only when document is ready.
/* window.onscroll = function() {
	updateFixedBar();
} */
