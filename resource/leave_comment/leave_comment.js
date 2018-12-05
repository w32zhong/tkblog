function autoFillUsingCookie(id)
{
	var c_author = getCookie("author");
	var c_url    = getCookie("url");
	var c_email  = getCookie("email");
	
	if (c_author != null && c_author != "")
	{
		var author = document.getElementById('author_input_id' + id);
		var email = document.getElementById('email_input_id' + id);
		var url = document.getElementById('url_input_id' + id);

		author.value = c_author;
		email.value = c_email;
		url.value = c_url;

		//disable email blank's auto clean
		var str = email.className;
		email.className = str.replace('soft_hint','');
		email.onfocus=null;

		//init the Gravatar image according to auto filled email addr.
		MailOnChange(document.getElementById('email_input_id' + id),id);
	}
}

function updateLeavingCommentCookie(id)
{
	var author = document.getElementById('author_input_id' + id).value;
	var email = document.getElementById('email_input_id' + id).value;
	var url = document.getElementById('url_input_id' + id).value;

	setCookie("author", author, 365);
	setCookie("url", url, 365);
	setCookie("email", email, 365);
}

function getShortWaterFall(id)
{
	var w1 = document.getElementById('waterFall1_id' + id);
	var w2 = document.getElementById('waterFall2_id' + id);
	var h1 = w1.offsetHeight;
	var h2 = w2.offsetHeight;

	if(h1 <= h2)
		return w1;
	else
		return w2;
}

function getCommentsNumInWaterFall(id)
{
	var n1 = document.getElementById('waterFall1_id' + id).childNodes.length;
	var n2 = document.getElementById('waterFall2_id' + id).childNodes.length;

	return n1 + n2;
}

function MoreComments(id,Obj)
{
	LoadButtonClick(Obj);
	var from = getCommentsNumInWaterFall(id) + 1;
	AjaxPost('moreComments', "\tto_id:" + id + "\n\tfrom:" + from + "\n", MoreCommentsResponseCallbk , id );
}

function MoreCommentsResponseCallbk(id)
{
	if(ifResponseComplete())
	{
		//showResponseXML();
		var res_array = getResponseTagArray('comment');

		for(var i = 0; i < res_array.length; i++)
		{
			var waterFall = getShortWaterFall(id);
			waterFall.innerHTML += getTagArraySubTagValue(res_array[i],'innerHTML');
		}

		var ret_arry = getResponseTagArray('return');
		var ret_value = getTagArraySubTagValue(ret_arry[0],'left');
		var node = document.getElementById('more_comments_button_id' + id);
		
		if(ret_value == 'no_more')
		{	
			if(node)
				node.parentNode.removeChild(node);
			document.getElementById('input_area_id' + id).className = 'input_area';
		}
		else
			ResetLoadButton(node, '还有更多');

		var total_arry = getResponseTagArray('total_comments');
		var total_value = getTagArraySubTagValue(total_arry[0],'value');
		document.getElementById('total_comments_num_id' + id).innerHTML = 
			String(total_value) + '条评论:';
	}
}

function SubmitComment(id, Obj)
{
	SetErrorPrompt(id,'正在写入cookie...');
	updateLeavingCommentCookie(id);
	
	if(!FormValidation(id))
	{
		ResetLoadButton(Obj,'提交评论');
		return;
	}
	
	SetErrorPrompt(id,'正在提交评论...');
	
	var content = document.getElementById('input_area_id' + id).value;
	var author = document.getElementById('author_input_id' + id).value;
	var email = document.getElementById('email_input_id' + id).value;
	var url = document.getElementById('url_input_id' + id).value;
	var mail_notify = document.getElementById('comment_mail_notify_id' + id).checked;
	var captcha = document.getElementById('captcha_input_id' + id).value;
	
	AjaxPost('commentSubmit', "\tto_id:" + id + "\n" +
	                          "\tauthor:" + author  + "\n" +
	                          "\temail:" + email + "\n" +
	                          "\turl:" + url + "\n" +
	                          "\tmail_notify:" + mail_notify + "\n" +
	                          "\tcaptcha:" + captcha + "\n" +
	                          content, SubmitCommentCallbk , id );
}

function BeforeSubmit(id, Obj)
{
	if(ifLoadButtonClicked(Obj))
		return;

	CommentEditOnChange(document.getElementById('input_area_id' + id),id);
	NameOnChange(document.getElementById('author_input_id' + id),id);
	MailOnChange(document.getElementById('email_input_id' + id),id);
	WebOnChange(document.getElementById('url_input_id' + id),id);
	CaptchaOnChange(document.getElementById('captcha_input_id' + id),id);
	
	LoadButtonClick(Obj);
	SetErrorPrompt(id,'');
	
	setTimeout(function(){SubmitComment(id, Obj)},4500);
}

function SubmitCommentCallbk(id)
{
	if(ifResponseComplete())
	{
		//showResponseXML();
		var leavingCommentObj = document.getElementById('blog_leaving_comment_id' + id);
		var topObj = document.getElementById('leave_comment_top_id' + id);
		var bottomObj = document.getElementById('leave_comment_bottom_id' + id);
		var faceObj = document.getElementById('face_select_id' + id);
		var textObj = document.getElementById('input_area_id' + id);
		var buttonObj = document.getElementById('submit_button_id' + id);

		leavingCommentObj.className = '';
		topObj.style.display = "none";
		bottomObj.style.display = "none";
		faceObj.style.display = "none";
		textObj.value = '继续评论';
		textObj.onfocus=function(){onFocusCommentEdit(textObj,id)};
		textObj.className='input_area';
			
		ResetLoadButton(buttonObj,'提交评论');
		SetErrorPrompt(id,'');
	
		MoreCommentsResponseCallbk(id);
		
		setTimeout(function(){updateRecentComments()},3000);
		setTimeout(function(){updateReaderRank()},6000);
	}
}

function CommentEditOnChange(InputObj,id)
{
	var text = InputObj.value;
	text = text.replace(/ /g,'');
	text = text.replace(/\n/g,'');
	if(text.length == 0)
	{
		document.getElementById('form_id' + id + '_comment').innerHTML =
			'no,没有评论内容，请填写评论。';
	}
	else if(InputObj.value.length > 180)
	{
		document.getElementById('form_id' + id + '_comment').innerHTML =
			'no,你输入的评论内容过多。';
	}
	else
	{
		document.getElementById('form_id' + id + '_comment').innerHTML =
			'yes,null';
	}
}

function SetErrorPrompt(id, str)
{
	var error_prompt_obj = 
		document.getElementById('form_id' + id + '_error');
	
	error_prompt_obj.innerHTML = str;
}

function FormValidation(id)
{
	var pat = /[^,]+$/;
	var error_prompt_obj = 
		document.getElementById('form_id' + id + '_error');
	var str = '';

	str = document.getElementById('form_id' + id + '_comment').innerHTML;
	if(str.search('yes') == -1)
	{
		str = str.match(pat);
		error_prompt_obj.innerHTML = '<img src="resource/icon/exclamation.png"/>' + str;	
		return false;
	}

	str = document.getElementById('form_id' + id + '_name').innerHTML;
	if(str.search('yes') == -1)
	{
		str = str.match(pat);
		error_prompt_obj.innerHTML = '<img src="resource/icon/exclamation.png"/>' + str;	
		return false;
	}

	str = document.getElementById('form_id' + id + '_email').innerHTML;
	if(str.search('yes') == -1)
	{
		str = str.match(pat);
		error_prompt_obj.innerHTML = '<img src="resource/icon/exclamation.png"/>' + str;	
		return false;
	}

	str = document.getElementById('form_id' + id + '_url').innerHTML;
	if(str.search('yes') == -1)
	{
		str = str.match(pat);
		error_prompt_obj.innerHTML = '<img src="resource/icon/exclamation.png"/>' + str;	
		return false;
	}

	str = document.getElementById('form_id' + id + '_captcha').innerHTML;
	if(str.search('yes') == -1)
	{
		str = str.match(pat);
		error_prompt_obj.innerHTML = '<img src="resource/icon/exclamation.png"/>' + str;	
		return false;
	}

	error_prompt_obj.innerHTML = '';
	return true;
}

function isEmail( str ){  
    if(Reg.test(str)) return true; 
    return false; 
}

function NameOnChange(InputObj,id)
{
	var rep = /^[0-9a-zA-Z\-._\u4e00-\u9fa5]+$/;

	if(rep.test(InputObj.value))
	{
		document.getElementById('name_validation_id' + id).innerHTML =
			'<img src="resource/icon/right.png"/>';
		document.getElementById('form_id' + id + '_name').innerHTML =
			'yes,null';
	}
	else
	{
		document.getElementById('name_validation_id' + id).innerHTML =
			'<img src="resource/icon/wrong.png"/>';

		if(InputObj.value.length == 0)
			document.getElementById('form_id' + id + '_name').innerHTML =
				'no,请填写称呼';
		else
			document.getElementById('form_id' + id + '_name').innerHTML =
				'no,输入的称呼只能包含数字、中英文、以及“.-_”这几个符号。';
	}
}

function MailOnChange(InputObj,id)
{
	var val = InputObj.value;
	var rep = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if(rep.test(val))
	{	
		document.getElementById('email_validation_id' + id).innerHTML =
			'<img src="resource/icon/right.png"/>';
		document.getElementById('preview_img_id' + id).src = 
			"https://gravatar.com/avatar/"+calcMD5(val)+"?d=wavatar&s=32";
		document.getElementById('form_id' + id + '_email').innerHTML =
			'yes,null';
	}
	else
	{
		document.getElementById('email_validation_id' + id).innerHTML =
			'<img src="resource/icon/wrong.png"/>';

		if(InputObj.value.length == 0)
			document.getElementById('form_id' + id + '_email').innerHTML =
				'no,请填写邮件地址';
		else
			document.getElementById('form_id' + id + '_email').innerHTML =
				'no,您的邮箱地址格式貌似不正确。';
	}
}

function WebOnChange(InputObj,id)
{
	var rep = /^(http:\/\/)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i;
	if(rep.test(InputObj.value) || InputObj.value.length == 0)
	{
		document.getElementById('url_validation_id' + id).innerHTML =
			'<img src="resource/icon/right.png"/>';
		document.getElementById('form_id' + id + '_url').innerHTML =
			'yes,null';
	}
	else
	{
		document.getElementById('url_validation_id' + id).innerHTML =
			'<img src="resource/icon/wrong.png"/>';
		document.getElementById('form_id' + id + '_url').innerHTML =
			'no,你的网页格式貌似不正确。';
	}
}

function CaptchaOnChange(InputObj,id)
{
	AjaxPost( 'captchaValidation', InputObj.value,
		validationCaptcha , id);
}

function validationCaptcha(id)
{
	if(ifResponseComplete())
	{
		var res_array = getResponseTagArray('validation');
		var res_str = getTagArraySubTagValue(res_array[0],'result');
		
		if(res_str == 'yes')
		{
			document.getElementById('captcha_validation_id' + id).innerHTML =
				'<img src="resource/icon/right.png"/>';
			document.getElementById('form_id' + id + '_captcha').innerHTML =
				'yes,null';
		}
		else
		{
			document.getElementById('captcha_validation_id' + id).innerHTML =
				'<img src="resource/icon/wrong.png"/>';

			document.getElementById('form_id' + id + '_captcha').innerHTML =
				'no,验证码错误，请重新填写。';
		}
	}
}

function refreshCaptcha(id)
{
	rand = parseInt(Math.random()*999999999);
	document.getElementById("captcha_id" + id).src="plugin/captcha/code.php?r=" + rand;

	//clean the validation flag after refresh
	document.getElementById('captcha_validation_id' + id).innerHTML = '';
	document.getElementById('form_id' + id + '_captcha').innerHTML = 'no,验证码错误，请重新填写。';
}

function LimitWords(textObj,comments_id)
{
	var text = textObj.value;
	var nWords = text.length;
	var wordsCont = document.getElementById("words_count_id" + comments_id);
	if(nWords > 180)
	{
		wordsCont.className = 'words_count_alert';
	}
	else
	{
		wordsCont.className = 'words_count_normal';
	}

	wordsCont.innerHTML = String(nWords) + '/180';

	CommentEditOnChange(textObj,comments_id);
}

function AtReplaceCallbk(match)
{
	match = match.replace(/\[at\]/,'');
	match = match.replace(/\[\/at\]/g,'');
	var blogID = match.match(/^[0-9]+/); 
	match = match.replace(/^[0-9]+_/,'');
	var sub_id = match.match(/^[0-9]+/); 
	var str = '<a href="#comment_id_' + blogID + '_' + sub_id + '">';
	var authorSpan = document.getElementById('author_name_id_' + blogID + '_' + sub_id);
	str += '@' + authorSpan.innerHTML + '&nbsp;</a>';

	return str;
}

function HrefReplaceCallbk(match)
{
	match = match.replace(/&lt;a&nbsp;href="/,'');
	var url = match.match(/^[^"]+/);
	match = match.replace(/^[^"]+/,'');
	match = match.replace(/^"&gt;/,'');
	var htxt = match.match(/.+?&lt;/);
	htxt = String(htxt);
	htxt = htxt.replace(/&lt;$/,'');

	var replece_str = '<a target="_blank" href="'+url+'">'+htxt+'</a>';
	return replece_str;
}

function BReplaceCallbk(match)
{
	match = match.replace(/&lt;b&gt;/,'');
	match = match.replace(/&lt;\/b&gt;/,'');

	var replece_str = '<b>'+match+'</b>';
	return replece_str;
}

function IReplaceCallbk(match)
{
	match = match.replace(/&lt;i&gt;/,'');
	match = match.replace(/&lt;\/i&gt;/,'');

	var replece_str = '<i>'+match+'</i>';
	return replece_str;
}

function StrikeReplaceCallbk(match)
{
	match = match.replace(/&lt;strike&gt;/,'');
	match = match.replace(/&lt;\/strike&gt;/,'');

	var replece_str = '<strike>'+match+'</strike>';
	return replece_str;
}

function CommentTimer(textObj,comments_id)
{
	if(textObj.className != 'input_area_expand')
		return;

	var inputText = textObj.value;
	LimitWords(textObj,comments_id);

	inputText = htmlentities(inputText,"ENT_QUOTES");
	inputText = inputText.replace(/ /g,'&nbsp;');
	inputText = inputText.replace(/\n/g,'<br/>');
	
	//replace face tags
	inputText = inputText.replace(/\[face\]([0-9]+)\[\/face\]/gi, "<img src=\"resource/leave_comment/faces/face$1.gif\"/>");

	inputText = inputText.replace(/\[at\][0-9_]+\[\/at\]/g,AtReplaceCallbk);
	//                              <  a _    href="..." >...  <  /a >
	inputText = inputText.replace(/&lt;a&nbsp;href=".+?"&gt;.+?&lt;\/a&gt;/g,HrefReplaceCallbk);
	
	inputText = inputText.replace(/&lt;b&gt;.+?&lt;\/b&gt;/g,BReplaceCallbk);
	inputText = inputText.replace(/&lt;i&gt;.+?&lt;\/i&gt;/g,IReplaceCallbk);
	inputText = inputText.replace(/&lt;strike&gt;.+?&lt;\/strike&gt;/g,StrikeReplaceCallbk);

	document.getElementById('preview_comment_id'+comments_id).innerHTML = inputText;
}

function onFocusCommentEdit(textObj,comments_id)
{
	var leavingCommentObj = document.getElementById('blog_leaving_comment_id' + comments_id);
	var topObj = document.getElementById('leave_comment_top_id' + comments_id);
	var bottomObj = document.getElementById('leave_comment_bottom_id' + comments_id);
	var faceObj = document.getElementById('face_select_id' + comments_id);
	var captchaInput = document.getElementById('captcha_input_id' + comments_id);
	var previewTxt = document.getElementById('preview_comment_id' + comments_id);

	leavingCommentObj.className = 'blog_leaving_comment';
	topObj.style.display = "block";
	bottomObj.style.display = "block";
	faceObj.style.display = "block";

	autoFillUsingCookie(comments_id);
	captchaInput.value = '';
	previewTxt.innerHTML = '评论...';
	
	setInterval(function(){CommentTimer(textObj,comments_id)},1000);
	
	refreshCaptcha(comments_id);

	textObj.value = '';
	textObj.onfocus=null;
	textObj.className='input_area_expand';
}

function onFocusEmailEdit(textObj)
{
	textObj.value = '';
	var str = textObj.className;
	textObj.className = str.replace('soft_hint','');
	textObj.onfocus=null;
}

function gravatarHint(id)
{
	document.getElementById('gravatar_hint_id' + id).style.display = "inline";
}

function onFaceSelect(face_num,comments_id)
{
	var el = document.getElementById("input_area_id" + comments_id);
	insertTextAtCursor(el,"[face]"+face_num+"[/face]");
	
	CommentTimer(el,comments_id);
}

function AtSomeOne(blogId,comment_floor)
{
	var el = document.getElementById("input_area_id" + blogId);
	
	if(el && el.className == 'input_area_expand')
	{
		insertTextAtCursor(el,'[at]' + blogId + '_' + comment_floor + '[/at]');
		CommentTimer(el,blogId);
	}
	else
		alert('请在评论时使用@功能');
}

function insertTextAtCursor(el, text) {
	var val = el.value, endIndex, range;
	if (typeof el.selectionStart != "undefined" && typeof el.selectionEnd != "undefined") {
		endIndex = el.selectionEnd;
		el.value = val.slice(0, endIndex) + text + val.slice(endIndex);
		el.selectionStart = el.selectionEnd = endIndex + text.length;
	} else if (typeof document.selection != "undefined" && typeof document.selection.createRange != "undefined") {
		el.focus();
		range = document.selection.createRange();
		range.collapse(false);
		range.text = text;
		range.select();
	}
}

function get_html_translation_table (table, quote_style) {
	var entities = {},
		hash_map = {},
		decimal;
	var constMappingTable = {},
		constMappingQuoteStyle = {};
	var useTable = {},
		useQuoteStyle = {};

	// Translate arguments
	constMappingTable[0] = 'HTML_SPECIALCHARS';
	constMappingTable[1] = 'HTML_ENTITIES';
	constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
	constMappingQuoteStyle[2] = 'ENT_COMPAT';
	constMappingQuoteStyle[3] = 'ENT_QUOTES';

	useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
	useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

	if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
		throw new Error("Table: " + useTable + ' not supported');
		// return false;
	}

	entities['38'] = '&amp;';
	if (useTable === 'HTML_ENTITIES') {
		entities['160'] = '&nbsp;';
		entities['161'] = '&iexcl;';
		entities['162'] = '&cent;';
		entities['163'] = '&pound;';
		entities['164'] = '&curren;';
		entities['165'] = '&yen;';
		entities['166'] = '&brvbar;';
		entities['167'] = '&sect;';
		entities['168'] = '&uml;';
		entities['169'] = '&copy;';
		entities['170'] = '&ordf;';
		entities['171'] = '&laquo;';
		entities['172'] = '&not;';
		entities['173'] = '&shy;';
		entities['174'] = '&reg;';
		entities['175'] = '&macr;';
		entities['176'] = '&deg;';
		entities['177'] = '&plusmn;';
		entities['178'] = '&sup2;';
		entities['179'] = '&sup3;';
		entities['180'] = '&acute;';
		entities['181'] = '&micro;';
		entities['182'] = '&para;';
		entities['183'] = '&middot;';
		entities['184'] = '&cedil;';
		entities['185'] = '&sup1;';
		entities['186'] = '&ordm;';
		entities['187'] = '&raquo;';
		entities['188'] = '&frac14;';
		entities['189'] = '&frac12;';
		entities['190'] = '&frac34;';
		entities['191'] = '&iquest;';
		entities['192'] = '&Agrave;';
		entities['193'] = '&Aacute;';
		entities['194'] = '&Acirc;';
		entities['195'] = '&Atilde;';
		entities['196'] = '&Auml;';
		entities['197'] = '&Aring;';
		entities['198'] = '&AElig;';
		entities['199'] = '&Ccedil;';
		entities['200'] = '&Egrave;';
		entities['201'] = '&Eacute;';
		entities['202'] = '&Ecirc;';
		entities['203'] = '&Euml;';
		entities['204'] = '&Igrave;';
		entities['205'] = '&Iacute;';
		entities['206'] = '&Icirc;';
		entities['207'] = '&Iuml;';
		entities['208'] = '&ETH;';
		entities['209'] = '&Ntilde;';
		entities['210'] = '&Ograve;';
		entities['211'] = '&Oacute;';
		entities['212'] = '&Ocirc;';
		entities['213'] = '&Otilde;';
		entities['214'] = '&Ouml;';
		entities['215'] = '&times;';
		entities['216'] = '&Oslash;';
		entities['217'] = '&Ugrave;';
		entities['218'] = '&Uacute;';
		entities['219'] = '&Ucirc;';
		entities['220'] = '&Uuml;';
		entities['221'] = '&Yacute;';
		entities['222'] = '&THORN;';
		entities['223'] = '&szlig;';
		entities['224'] = '&agrave;';
		entities['225'] = '&aacute;';
		entities['226'] = '&acirc;';
		entities['227'] = '&atilde;';
		entities['228'] = '&auml;';
		entities['229'] = '&aring;';
		entities['230'] = '&aelig;';
		entities['231'] = '&ccedil;';
		entities['232'] = '&egrave;';
		entities['233'] = '&eacute;';
		entities['234'] = '&ecirc;';
		entities['235'] = '&euml;';
		entities['236'] = '&igrave;';
		entities['237'] = '&iacute;';
		entities['238'] = '&icirc;';
		entities['239'] = '&iuml;';
		entities['240'] = '&eth;';
		entities['241'] = '&ntilde;';
		entities['242'] = '&ograve;';
		entities['243'] = '&oacute;';
		entities['244'] = '&ocirc;';
		entities['245'] = '&otilde;';
		entities['246'] = '&ouml;';
		entities['247'] = '&divide;';
		entities['248'] = '&oslash;';
		entities['249'] = '&ugrave;';
		entities['250'] = '&uacute;';
		entities['251'] = '&ucirc;';
		entities['252'] = '&uuml;';
		entities['253'] = '&yacute;';
		entities['254'] = '&thorn;';
		entities['255'] = '&yuml;';
	}

	if (useQuoteStyle === 'ENT_QUOTES') {
		entities['39'] = '&#39;';
	}
	entities['60'] = '&lt;';
	entities['62'] = '&gt;';

	// ascii decimals to real symbols
	for (decimal in entities) {
		if (entities.hasOwnProperty(decimal)) {
			hash_map[String.fromCharCode(decimal)] = entities[decimal];
		}
	}

	return hash_map;
}

function htmlentities (string, quote_style, charset, double_encode) {
	var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
		symbol = '';
	string = string == null ? '' : string + '';

	if (!hash_map) {
		return false;
	}

	if (!!double_encode || double_encode == null) {
		for (symbol in hash_map) {
			if (hash_map.hasOwnProperty(symbol)) {
				string = string.split(symbol).join(hash_map[symbol]);
			}
		}
	} else {
		string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function (ignore, text, entity) {
				for (symbol in hash_map) {
				if (hash_map.hasOwnProperty(symbol)) {
				text = text.split(symbol).join(hash_map[symbol]);
				}
				}

				return text + entity;
				});
	}

	return string;
}

function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
		{
			return unescape(y);
		}
	}
}

function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
