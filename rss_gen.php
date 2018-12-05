<?php
require_once('access_sql.php');
require_once('resource/blog_frame/blog_frame.php');
require_once('resource/phrase/phrase.php');

$h_file = fopen("feed.xml" , "wt");
$dom = new DOMDocument('1.0','UTF-8');
$RSS = $dom->createElement('rss');
$RSS->setAttribute('version', '2.0');
$dom->appendChild($RSS);
$channel = $dom->createElement('channel');
$RSS->appendChild($channel);

$rss_title = $dom->createElement('title', '卑鄙的t.k.');
$channel->appendChild($rss_title);

$rss_title = $dom->createElement('description', 't.k.的博客');
$channel->appendChild($rss_title);

$rss_title = $dom->createElement('copyright', 'Copyright Zhong Wei');
$channel->appendChild($rss_title);

$rss_title = $dom->createElement('link', "http://$_SERVER[HTTP_HOST]/tkblog");
$channel->appendChild($rss_title);

date_default_timezone_set('Asia/Shanghai');
$time_now = date('r');

$rss_title = $dom->createElement('lastBuildDate', $time_now);
$channel->appendChild($rss_title);
//<lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>

$rss_title = $dom->createElement('image');
$rss_title->appendChild($dom->createElement('title', 'blog icon'));
$rss_title->appendChild($dom->createElement('url', "http://$_SERVER[HTTP_HOST]/tkblog/resource/icon/icon.jpeg"));
$rss_title->appendChild($dom->createElement('link', "http://$_SERVER[HTTP_HOST]/tkblog"));
$channel->appendChild($rss_title);

//begin create item nodes
$res = dbGetBlogsByFilters(0, 'all', '8000-1-1', '', 1, 10);

for($i = 0; $i < $res->num_rows; $i++)
{
	$blog = $res->fetch_assoc();

	ob_start();
	echoBlogEntry($blog, 1);
	$blog_str = ob_get_contents();
	ob_end_clean();

	$title = "${blog['year']}年${blog['month']}月${blog['day']}日";
	$description = $blog_str;
	$link =  "http://$_SERVER[HTTP_HOST]/tkblog/index.php?id=${blog['id']}&tag=whole";
	
	$item = $dom->createElement('item');
	
	$sub_item = $dom->createElement('title');
	$text = $dom->createTextNode($title);
	$sub_item->appendChild($text);
	$item->appendChild($sub_item);

	$sub_item = $dom->createElement('description');
	$text = $dom->createTextNode($description);
	$sub_item->appendChild($text);
	$item->appendChild($sub_item);

	$sub_item = $dom->createElement('link');
	$text = $dom->createTextNode($link);
	$sub_item->appendChild($text);
	$item->appendChild($sub_item);

	$channel->appendChild($item);
}

fwrite($h_file,$dom->saveXML());
fclose($h_file);

echo 'RSS generated.';
?>
