<?php
require_once('../ajax/response.php');
require_once('../../access_sql.php');
require_once('../phrase/phrase.php');
require_once('../calendar/calendar.php');
require_once('blog_frame.php');
require_once('gadgets.php');
require_once('../comment/comments.php');
require_once('../leave_comment/leave_comment.php');

function handleMoreBlogs($type_value, $msg_value, $pace=9)
{
	$agr_arr = phraseFileHead($msg_value);
	
	//recv agreements
	$from = $agr_arr['from'];
	$id_filter = $agr_arr['id_filter'];
	$tag_filter = $agr_arr['tag_filter'];
	$time_filter = $agr_arr['time_filter'];
	$keyword_filter = $agr_arr['keyword_filter'];

	//if not safe, make it safe
	//if(!get_magic_quotes_gpc())
	//{
	//	$from = addslashes($from);
	//	$id_filter = addslashes($id_filter);
	//	$tag_filter = addslashes($tag_filter);
	//	$time_filter = addslashes($time_filter);
	//	$keyword_filter = addslashes($keyword_filter);
	//}

	$res = dbGetBlogsByFilters($id_filter, $tag_filter, $time_filter, 
			$keyword_filter, $from, $pace + 1);
	
	$left = 'more';
	if($res->num_rows <= $pace)
		$left = 'no_more';
	
	$num_to_show = min($pace, $res->num_rows);

	responseBegin();
	responseNewTag('return');
	responseAddIntoTag('left',$left);

	for($i = 0; $i < $num_to_show ; $i++)
	{
		$row = $res->fetch_assoc();
		
		ob_start();
		echoBlog($row);
		$blog_str = ob_get_contents();
		ob_end_clean();

		responseNewTag('blog');
		responseAddIntoTag('id',$row['id']);
		responseAddIntoTag('time',$row['time']);
		responseAddIntoTag('innerHTML',$blog_str);
	}
	
	responseEnd();
}

function handleMoreBlogContent($type_value, $msg_value)
{
	global $absDirPrefix;

	$agr_arr = phraseFileHead($msg_value);
	$id = $agr_arr['id'];

	//if(!get_magic_quotes_gpc())
	//{
	//	$id = addslashes($id);
	//}

	$blog = dbGetBlogByID($id);
	$blog_more_content = '';
	
	if(empty($blog))
		$blog_more_content = 'EMPTY';
	else
	{
		$blog_location = $absDirPrefix.$blog['path'].'/'.$blog['f_name'];
		$blog_content = IgnoreFileHead(file_get_contents($blog_location));
		$blog_more_content = getBlogMoreContent($blog_content);
		$blog_more_content = phraseBlogContent($blog_more_content);
	}

	responseBegin();
	responseNewTag('blog_more');
	responseAddIntoTag('innerHTML',$blog_more_content);
	responseEnd();
}

function handleRecentCommentsUpdate($type_value, $msg_value)
{
	ob_start();
	echoRecentComments();
	$str = ob_get_contents();
	ob_end_clean();

	responseBegin();
	responseNewTag('recent_comments');
	responseAddIntoTag('innerHTML',$str);
	responseEnd();
}

function handleReaderRankUpdate($type_value, $msg_value)
{
	ob_start();
	echoReaderRank();
	$str = ob_get_contents();
	ob_end_clean();

	responseBegin();
	responseNewTag('reader_rank');
	responseAddIntoTag('innerHTML',$str);
	responseEnd();
}
?>
