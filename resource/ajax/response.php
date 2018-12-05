<?php
$type_value = $_POST['type'];
$msg_value = $_POST['message'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER["REMOTE_ADDR"]; 

require_once('../leave_comment/response.php');
require_once('../blog_frame/response.php');

//debug only:
/*
$h_file = fopen("../../".$type_value ,"w");
fwrite($h_file, $msg_value);
fclose($h_file);
*/

if( $type_value == 'captchaValidation' )
{
	handleValueOfCapRes($type_value,$msg_value);
}
else if( $type_value == 'commentSubmit')
{
	handleValueOfSubmit($type_value,$msg_value,$ua,$ip);
}
else if( $type_value == 'moreComments')
{
	handleMoreComments($type_value,$msg_value);
}
else if( $type_value == 'moreBlogs')
{
	handleMoreBlogs($type_value,$msg_value);
}
else if( $type_value == 'moreContent')
{
	handleMoreBlogContent($type_value,$msg_value);
}
else if( $type_value == 'updateRecentComments')
{
	handleRecentCommentsUpdate($type_value,$msg_value);
}
else if( $type_value == 'updateReaderRank')
{
	handleReaderRankUpdate($type_value,$msg_value);
}

function responseBegin()
{
	global $dom, $father;
	$dom = new DOMDocument('1.0','UTF-8');
	$father = $dom->createElement('ajax_response');
	$dom->appendChild($father);
}

function responseNewTag($tag_name)
{
	global $dom, $father, $son;
	$son = $dom->createElement($tag_name);
	$father->appendChild($son);
}

function responseAddIntoTag($sub_tag_name, $str)
{
	global $dom, $son;
	$sub_tag = $dom->createElement($sub_tag_name);
	$text = $dom->createTextNode($str);
	$sub_tag->appendChild($text);
	$son->appendChild($sub_tag);
}

function responseEnd()
{
	global $dom;
	header("Content-Type: text/xml; charset=utf-8");
	
	echo $dom->saveXML();
}

?>
