<?php
require_once('../ajax/response.php');
require_once('../../access_sql.php');
require_once('../../date_inzone.php');
require_once('../phrase/phrase.php');
require_once('../comment/comments.php');
require_once('../../plugin/phpmailer/my_send.php');

function handleValueOfSubmit($type_value, $msg_value, $ua, $ip)
{
	$agr_arr = phraseFileHead($msg_value);

	if('no' == handleValueOfCapRes('',$agr_arr['captcha'],false))
	{
		handleValueOfCapRes('',$agr_arr['captcha']);
		return;
	}

	$agr_arr['url'] = preg_replace("/^http:\/\//", '', $agr_arr['url']);

	//recv agreements
	$to_id = htmlentities($agr_arr['to_id'], ENT_COMPAT,'UTF-8');
	$author = htmlentities($agr_arr['author'], ENT_COMPAT,'UTF-8');
	$email = htmlentities($agr_arr['email'], ENT_COMPAT,'UTF-8');
	$url = htmlentities($agr_arr['url'], ENT_COMPAT,'UTF-8');
	$mail_notify = $agr_arr['mail_notify'];
	
	//take care with the 4 value above, we don' trust them because
	//they are generated on the user-side, and, they will be displayed,
	//we must make sure there is not <script> tags in it.
	
	//we let content pass, because we will deal with it later.
	//now, we just make sure the content string is striped if it
	//is added with slashes in the POST.
	$content = $agr_arr['content'];

	//debug code:
	//$h_file = fopen( "../../just_for_debug.log" ,"w");
	//fwrite($h_file, $content);
	//fclose($h_file);

	//if(get_magic_quotes_gpc())
	//{
	//	$content = stripslashes($content);
	//}

	//get max sub id of comments to to_id
	$max_sub_id = dbGetMaxSubIDOfComments($to_id);
	$sub_id = $max_sub_id + 1;

	//generate file name
	$f_name = 
		'com-'.date_inzone('Asia/Shanghai', 'Y-m-j-H-i-').$to_id.'-'.$sub_id.'.txt';

	//generate file 
	$f_str = "\tname:${author}\n";
	$f_str .= "\temail:${email}\n";
	$f_str .= "\tu_agent:${ua}\n";
	$f_str .= "\tIP:${ip}\n";
	$f_str .= "\tmail_notify:${mail_notify}\n";
	
	if(preg_match("/\./",$url) == 1)
		$f_str .= "\turl:${url}\n";
	else
		$f_str .= "\turl:no\n";
	
	$f_str .= $content;

	//phrase @ and send mail
	$direct_url = "http://[这里是他的域名（他很懒）]/index.php?tag=whole&id=${to_id}";
	SearchAtTagsAndSendMail($content, $direct_url);

	//re-using code
	$arr = phraseFileName($f_name);
	$content_arr = phraseFileHead($f_str);
		
	$addr = '../.'.$arr[6];
	if(!file_exists($addr))
		mkdir($addr,0777,true);
	
	//write file
	$h_file = fopen( $addr.'/'.$f_name ,"w");
	fwrite($h_file, $f_str);
	fclose($h_file);

	//insert into database
	dbInsertComment($arr,$content_arr);

	//return the new comment
	$floor = dbGetTotalComments($to_id);
	handleMoreComments('', "\tto_id:${to_id}\n\tfrom:${floor}\n");
}

function handleMoreComments($type_value, $msg_value, $pace = 7)
{
	$agr_arr = phraseFileHead($msg_value);
	$from_floor = intval($agr_arr['from']);
	$to_floor = $from_floor + $pace -1;
	$res = dbGetCommentsByFloor($agr_arr['to_id'],$from_floor,$pace);
	$total = dbGetTotalComments($agr_arr['to_id']);

	responseBegin();
	
	$left = 'more';
	if($to_floor >= $total)
		$left = 'no_more';
	
	responseNewTag('return');
	responseAddIntoTag('left',$left);
	
	for($i = 0; $i < $res->num_rows ; $i++)
	{
		$row = $res->fetch_assoc();
		responseNewTag('comment');

		$comment_location = '../.'.$row['path'].'/'.$row['f_name'];
		$innerHtml = HtmlComment($row, 
				IgnoreFileHead(file_get_contents($comment_location)) );
		responseAddIntoTag('innerHTML',$innerHtml);
	}

	responseNewTag('total_comments');
	responseAddIntoTag('value',$total);
	
	responseEnd();
}

function handleValueOfCapRes($type_value, $msg_value, $if_response = true)
{
	$ifPass = 'no';
	$debug  = 'null';
	session_start();
	if( isset( $_SESSION['turing_string'] ) )
	{ 
		if( strtoupper($_SESSION['turing_string']) == strtoupper($msg_value) ) 
		{ 
			$ifPass = 'yes';
		}
		else
		{
			$debug = 'your typing:'.$msg_value;
		}
	}
	else
	{
		$debug = 'unset';
	}
	
	if($if_response)
	{
		responseBegin();
		responseNewTag('validation');
		responseAddIntoTag('result',$ifPass);
		responseAddIntoTag('debug',$debug);
		responseEnd();
	}

	return $ifPass;
}
?>
