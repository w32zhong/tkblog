<?php
require_once('resource/blog_frame/blog_frame.php');
require_once('resource/comment/comments.php');
require_once('resource/phrase/phrase.php');
require_once('access_sql.php');

$recent_comments = $_GET['recent_comments'];

if(isset($recent_comments)) {
	$res = dbGetRecentComments($recent_comments);
	for($i=0; $i< $res->num_rows; $i++)
	{
		$comment = $res->fetch_assoc();
		echo $comment['path'].'/'.$comment['f_name']."\n";
	}
} else
	echo 'argument unset';
?>
