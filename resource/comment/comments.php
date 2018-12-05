<?php
function echoCommentStart($blogID,$total)
{
	$flag = "${total}条评论:";
	if($total == 0)
		$flag = '暂无评论。';
	echo '<div class="comments_start">';
	echo '<img src="resource/comment/comments.png"/>';
	echo '<span id="total_comments_num_id'.$blogID.'">';
	echo $flag.'</span></div>';
}

function HtmlComment($comment, $content, $ifGadgets = false)
{
	$blogID = $comment['to_id'];
	$subID = $comment['sub_id'];

	$floor = 0;
	if(!$ifGadgets) {
		$floor = $comment['floor'];
	}

	$author = $comment['name'];
	$ip = $comment['IP'];
	$ua = $comment['u_agent'];
	$email = $comment['email'];
	$authorHomePage = $comment['url'];
	$year = $comment['year'];
	$month = $comment['month'];
	$day = $comment['day'];
	$hour = $comment['hour'];
	$minute = $comment['minute'];
	$time_str = "${year}年${month}月${day}日${hour}时${minute}分";
	
	$res_str = '';
	$flag = '';
	if($author == 't.k.ga6840')
	{	
		$author = 't.k.';
		$flag = '(卑鄙的博主)';
	}
	if($ifGadgets)
		$res_str .= '<div class="comment">';//prevent from somebody's @
	else
		$res_str .= '<div class="comment" id="'."comment_id_${blogID}_${subID}".'">';
	$email_md5 = md5(strtolower(trim($email)));
	$res_str .= '<div class="comment_image"><img src="https://gravatar.com/avatar/'.$email_md5.'?s=32&d=wavatar"/></div>';
	$res_str .= '<div class="comment_head">';
	if($ifGadgets)
		$res_str .= '&nbsp;<a target="_Blank" href="index.php?tag=whole&id='.$blogID.'">查看被留言文章</a>';
	else
		$res_str .= '<span class="floor_font"><a href="#input_area_id'.$blogID.'" onclick="AtSomeOne('.$blogID.','.$subID.')" title="在评论里@一下他/她">#'.$floor.'</a></span>';
	
	$res_str .= $flag;
	$res_str .= '<br/>';
	$author = '<span  id="author_name_id_'.$blogID.'_'.$subID.'">'.$author.'</span>';
	if($authorHomePage == 'no')
		$res_str .= $author." 于${time_str}:</div>";
	else
		$res_str .= '<a href="http://'.$authorHomePage.'" title="访问他/她的主页" target="_Blank">'.$author."</a> 于${time_str}:</div>";
	$res_str .= '<div class="comment_body main_font">';
		$res_str .= '<div class="roundtop-all">';
			$res_str .= '<div class="roundtop-0"></div>';
			$res_str .= '<div class="roundtop-1"></div>';
			$res_str .= '<div class="roundtop-2"></div>';
			$res_str .= '<div class="roundtop-3"></div>';
			$res_str .= '<div>'.phraseCommentContent($content).'</div>';
			$res_str .= '<div class="roundtop-3"></div>';
			$res_str .= '<div class="roundtop-2"></div>';
			$res_str .= '<div class="roundtop-1"></div>';
			$res_str .= '<div class="roundtop-0"></div>';
		$res_str .= '</div>';
	$res_str .= '</div>';
	$res_str .= '</div>';

	return $res_str;
}

function echoComment($comment, $content, $ifGadgets = false)
{
	echo HtmlComment($comment, $content, $ifGadgets);
}
?>
