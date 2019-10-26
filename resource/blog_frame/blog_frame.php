<?php
$absDirPrefix = dirname(__FILE__).'/../.';

function echoMoreBlogsButton()
{
	echo '<span id="more_blogs_notice" class="clear_blogs_hint"></span>';
	echo '<div id="more_blogs_button" class="load_button" onclick="MoreBlogs(this);">';
	echo '<a href ="javascript:;">';
	echo '更多文章';
	echo '</a>';
	echo '</div>';
}

function echoBlog($blog)
{
	$id = $blog['id'];
	$ifMoreComments = 0;
	$ifMoreContent = false;

	echo '<div class="blog" id="blog_id'.$id.'">';
		echo '<div class="blog_main">';
			echoCalendar($blog['year'],$blog['month'],$blog['day'],$id);
			echoBlogTitle($blog);
			$ifMoreContent = echoBlogEntry($blog);
		echo '</div>';

		if($ifMoreContent)
			echo  '<div id="more_content_button_id'.$id.
		          '" class="load_button" onclick="MoreContent('.$id.',this)">'.
		          '<a href="javascript:;">查看全文</a></div>';

		echo '<div class="blog_comments">';
			$ifMoreComments = echoInitComments($id);
		echo '</div>';
			
			if($ifMoreComments)
			{
				echoMoreCommentsButton($id);
				echoBlogLeavingComment($id, 'hide');
			}
			else
				echoBlogLeavingComment($id, '');
	echo '</div>';

}

function echoInitComments($id)
{
	global $absDirPrefix;
	$num_init_show = 4;

	$comments_total = dbGetTotalComments($id);
	echoCommentStart($id,$comments_total);

	$comments = dbGetCommentsByFloor($id,1,min($num_init_show,$comments_total));
	$num_comments = $comments->num_rows;

	$left_side = array();
	$right_side = array();
	for( $i = 0 ; $i < $num_comments ; $i++)
	{
		$row = $comments->fetch_assoc();

		if($i % 2 == 0)
			$left_side[$i/2] = $row;
		else
			$right_side[floor($i/2) + 1] = $row;
	}

	echo '<span id="waterFall1_id'.$id.
		'" class="waterFallColumn" style="width:320px">';
	foreach($left_side as $index => $comment)
	{
		$content = IgnoreFileHead(
				file_get_contents($absDirPrefix.$comment['path'].'/'.$comment['f_name']) );
		echoComment($comment, $content );
	}
	echo '</span>';
	echo '<span id="waterFall2_id'.$id.
		'" class="waterFallColumn" style="width:320px">';
	foreach($right_side as $index => $comment)
	{
		$content = IgnoreFileHead(
				file_get_contents($absDirPrefix.$comment['path'].'/'.$comment['f_name']) );
		echoComment($comment, $content );
	}
	echo '</span>';

	return ($comments_total > $num_init_show);
}

function echoBlogTitle($blog)
{
	echo '<div class="title">'.trim($blog['tag']).'</div>';
}

function echoBlogEntry($blog, $ifShowAll=0)
{
	global $absDirPrefix;
	$ifMoreContent = false;
	
	echo '<span id="blog_entry_id'.$blog['id'].'" class="main_font">';
	
	if(empty($blog))
		echo 'EMPTY';
	else
	{
		$blog_location = $absDirPrefix.$blog['path'].'/'.$blog['f_name'];
		$blog_content = IgnoreFileHead(file_get_contents($blog_location));
		
		if(!$ifShowAll)
			$ifMoreContent = doBlogExcerpt($blog_content);
		else
			rmCut_more($blog_content);
		
		$blog_content = phraseBlogContent($blog_content);
		
		echo $blog_content;
	}

	echo '</span>';

	return $ifMoreContent;
}

function echoMoreCommentsButton($id)
{
	echo '<div id="more_comments_button_id'.$id.
		'" class="load_button" onclick="MoreComments('.$id. ',this)">'.
		'<a href="javascript:;">查看更多评论</a></div>';
}

function echoBlogLeavingComment($id, $init_css_class)
{
	echoBlogLeavingCommentTop($id);

	echo '<div id="blog_leaving_comment_id'.$id.'" class="">';
	echo '<span class="waterFallColumn" style="width:300px">';
		//这里onkeyup是为了兼容IE，它对setInterval函数支持不好
		echo '<textarea onkeyup="CommentTimer(this,'.$id.')" onfocus="onFocusCommentEdit(this,'.$id.')" '.
			'id="input_area_id'.$id.'" class="input_area '.$init_css_class.'">评论...</textarea>';
		echoFaces($id);
	echo '</span>';

	echo '<span class="waterFallColumn" style="width:300px">';
		echoBlogLeavingCommentBottom($id);
	echo '</span>';
	
	echoBlogLeavingCommentValidationResult($id);
	echo '</div>';
}

function echoBlogLeavingCommentTop($id)
{
	echo '<div id="leave_comment_top_id'.$id. '" class="hide comment">';
		echo '<div class="blog_comments">';
			echo '<div class="comment_image preview_image">';
			echo '<img id="preview_img_id'.$id. '" onclick="gravatarHint('.$id. ')" title="本站根据您填写的电子邮件生成头像，您也可以注册gravatar拥有属于自己的全球通用头像。" src="resource/icon/default-gravatar32.jpg"/>';
			echo '</div>';
			
			echo '&nbsp;(评论预览)';
			echo '<span id="words_count_id'.$id. '" class="words_count_normal">0/180</span>';
			
			echo '<div class="comment_body main_font">';
				echo '<div class="roundtop-all">';
					echo '<div class="roundtop-0"></div>';
					echo '<div class="roundtop-1"></div>';
					echo '<div class="roundtop-2"></div>';
					echo '<div class="roundtop-3"></div>';
						echo '<div id="preview_comment_id'.$id.'">评论...</div>';
					echo '<div class="roundtop-3"></div>';
					echo '<div class="roundtop-2"></div>';
					echo '<div class="roundtop-1"></div>';
					echo '<div class="roundtop-0"></div>';
				echo '</div>';
			echo '</div>';
		
			echo '<span id="gravatar_hint_id'.$id. '" class="hide gravatar_hint"><img src="resource/icon/info.png"/>本站根据您填写的电子邮件生成头像，您也可以<a href="https://cn.gravatar.com/" target="_blank">注册gravatar</a>拥有属于自己的全球通用头像。</span>';
		echo '</div>';
	echo '</div>';
}

function echoBlogLeavingCommentBottom($id)
{
	echo '<div id="leave_comment_bottom_id'.$id. '" class="hide">';
	echo '称呼:<input id="author_input_id'.$id. '" name="author" class="input_line" onchange="NameOnChange(this,'.$id. ')"></input><b>(必填)</b><span id="name_validation_id'.$id. '" class="validation_hint"></span></br>';
	echo '邮箱:<input id="email_input_id'.$id. '" name="email" onchange="MailOnChange(this,'.$id. ')" onfocus="onFocusEmailEdit(this)" class="input_line soft_hint" value="邮箱地址不被公开"></input><b>(必填)</b><span id="email_validation_id'.$id. '" class="validation_hint"></span></br>';

	echo '主页:<input id="url_input_id'.$id. '" name="url" onchange="WebOnChange(this,'.$id. ')" class="input_line"></input>(选填)<span id="url_validation_id'.$id. '" class="validation_hint"></span><br/>';
	echo '<img id="captcha_id'.$id. '" onclick="refreshCaptcha('.$id.')" src="resource/icon/loading.gif"/><br/>';
	echo '(点击图片刷新)<br/>';
	echo '验证码:<input onchange="CaptchaOnChange(this,'.$id. ')" id="captcha_input_id'.$id. '" class="captcha_line"></input><span id="captcha_validation_id'.$id. '" class="validation_hint"></span>';

	echo '<br/><input type="checkbox" id="comment_mail_notify_id'.$id.'" checked="checked" style="width: auto;">有人@我时请邮件通知我';
		
	echo '<div id="submit_button_id'.$id. '" class="load_button" onclick="BeforeSubmit('.$id. ',this)">';
	echo '<a href="javascript:;">提交评论</a>';
	echo '</div>';
	
	echo '<span id="form_id'.$id. '_error" class="form_error_prompt"></span>';
	echo '</div>';
}

function echoBlogLeavingCommentValidationResult($id)
{
	echo '<div id="form_id'.$id. '_comment" class="hide">no,请填写评论</div>';
	echo '<div id="form_id'.$id. '_name" class="hide">no,请填写称呼</div>';
	echo '<div id="form_id'.$id. '_email" class="hide">no,请填写邮件地址</div>';
	echo '<div id="form_id'.$id. '_url" class="hide">yes,null</div>';
	echo '<div id="form_id'.$id. '_captcha" class="hide">no,请填写验证码</div>';
}
?>
