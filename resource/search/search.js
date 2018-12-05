$(document).ready(function(){
	$('#search_button').on('click', function() {
		str = $("#input_box").val();
		srch(encodeURI(str), 1);
	});

	$("#input_box").keypress(function(e) {
		if (e.which == 13) {
			str = $("#input_box").val();
			srch(encodeURI(str), 1);
		}
	});
});

function init_search(str) {
	/* passed in by GET q=xxx */
	$("#input_box").val(str); // overwrite input box
	srch(encodeURI(str), 1);
}

function str_fmt() {
    // The string containing the format items (e.g. "{0}")
    // will and always has to be the first argument.
    var theString = arguments[0];
    
    // start with the second argument (i = 1)
    for (var i = 1; i < arguments.length; i++) {
        // "gm" = RegEx options for Global search (more than one instance)
        // and for Multiline search
        var regEx = new RegExp("\\{" + (i - 1) + "\\}", "gm");
        theString = theString.replace(regEx, arguments[i]);
    }
    
    return theString;
}

function srch(url_arg, page) {
	console.log('search:' + url_arg);

	$.ajax({
		url: 'resource/search/search_req.php',
		data: 'action=search&page=' + page + '&query=' + url_arg,
		dataType: 'json',
	}).done(function(data) {
		if (data['error']) {
			$("#container").html('error: ' + data['desc']);
			return;
		}
		page_now = data['desc']['page_now'];
		page_count = data['desc']['pagecount'];
		result_li = data['desc']['list'];
		if (page_count == 0) {
			$("#container").html('No result found.');
			return;
		}

		/* clear search results using an unordered list */
		$("#container").html('<ol id="reslist"></ol>');

		for (var i in result_li) {
//			console.log('item ' + i + ':');
//			console.log('\tblog_id=' + result_li[i]['blog_id']);
//			console.log('\t' + result_li[i]['highlights']);
			anchor = str_fmt('<a href="index.php?id={0}">index.php?id={0}</a>', 
			                 result_li[i]['blog_id']);
			highlights = str_fmt('<pre>{0}</pre>', result_li[i]['highlights']);
			item_html = str_fmt('<li>{0}<br/>{1}</li>', anchor, highlights); 
			$("#reslist").append(item_html);
		}

		/* previous page anchor */
		a_prev = ' ';
		if (page_now - 1 > 0) {
			a_prev = str_fmt('<a href="{0}" onclick="srch(\'{1}\',{2})">prev</a>',
			                 'javascript:void(0);', url_arg, page_now - 1);
		}
		td_left = str_fmt('<td>{0}</td>', a_prev);

		/* next page anchor */
		a_next = ' ';
		if (page_now + 1 <= page_count) {
			a_next = str_fmt('<a href="{0}" onclick="srch(\'{1}\',{2})">next</a>',
			                 'javascript:void(0);', url_arg, page_now + 1);
		}
		td_right = str_fmt('<td>{0}</td>', a_next);

		/* page status */
		td_mid = str_fmt('<td>page {0}/{1}</td>', page_now, page_count);

		/* echo navigation */
		navg = str_fmt('<table style="width:400px"><tr>{0}{1}{2}</tr></table>',
		               td_left, td_mid, td_right);
		$("#container").append(navg);
//		console.log('page:' + page_now + '/' + page_count);

	}).fail(function(data) {
		alert('Ajax fails');
	});
}
