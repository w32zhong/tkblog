<!DOCTYPE html>
<html lang="zh-CN">

<head>
<title>上传文件处理</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>

<?php
require_once('resource/phrase/phrase.php');
require_once('resource/search/search_curl.php');
require_once('access_sql.php');
require_once('date_inzone.php');

//echo "<pre>";
//var_dump($_POST);
//var_dump($_FILES);
//echo "</pre>";
//exit();

$files = $_FILES['files'];
$err = $_FILES['files']['error'];

$ifPublish = 0;
if(isset($_POST['ifPublish']))
{
	$ifPublish = 1;
}

if($err == UPLOAD_ERR_OK)
{
	$f_name = $files['name'];
		
	if(is_uploaded_file($files['tmp_name']))
	{
		//---- new blog/src handle-------
		$ext_name = substr($f_name, -5, 5);

		if($ifPublish)
		{
			if($ext_name == '.blog')
			{
				$new_blog_id = intval(dbGetMaxBlogID()) + 1;
				$f_name = 
					date_inzone('Asia/Shanghai', 'Y-m-j-H-i-').$new_blog_id.'.txt';
			}
			else
			{
				$f_name = 'src-'.date_inzone('Asia/Shanghai', 'Y-m-j-H-i-').$f_name;
			}
		}
		else if($ext_name == '.blog')
		{
			$f_name = date_inzone('Asia/Shanghai', 'Y-m-j-H-i-').'0.txt';
		}
		// elsewise it should be either .txt or src 
		//---------end--------------------
			
		$arr = phraseFileName($f_name);
		//print_r($arr);
		
		/* if id is zero and type is blog */
		if($arr[7] == 0 && $arr[0] == 'blog')
		{
			$arr[6] = './blog/draft';//fix its saving path
		}
		
		if(!file_exists($arr[6]))
		{
			if(!mkdir($arr[6],0775,true))
				echo 'cannot create directory.(error 4)'."$arr[6] </br>\n";
			else
				echo "mkdir $arr[6] <br/>"."\n";
		}
		
		if (!isset($_POST['ifNoTouch'])) {
			if(move_uploaded_file($files['tmp_name'], 
			   $arr[6].'/'.$f_name)) {
				echo 'successful:'." $arr[6]/${f_name}<br/>\n";
			} else {
				echo 'failed:'.$f_name.'(error 1).<br/>'."\n";
				exit;
			}
		}

		if (!isset($_POST['ifNoIndex']) 
		    && $arr[6] != './blog/draft'
			&& ($arr[0] == 'blog' || $arr[0] == 'comment')) 
		{ /* not a draft and is a (blog|comment) */
			echo "Indexing...<br/>";
			echo "if you see error, please install php5-curl:<br/>";
			echo "apt-get install php5-curl<br/>";
			echo "service nginx restart<br/>";
			$relav_path_to_searchd = ".$arr[6]/${f_name}";

			# index this post. 
			$request = array('action' => 'index', 
			                 'path' => $relav_path_to_searchd);
			$ret_json = search_request($request);
			$ret_obj = json_decode($ret_json);
			echo 'done ';
			echo $ret_obj->desc;
		}

		if($arr[0] == 'blog' || $arr[0] == 'comment')
		{
			$content = file_get_contents($arr[6].'/'.$f_name);
			$content_arr = phraseFileHead($content);

			if($arr[0] == 'blog')
			{
				$res = dbGetBlogByID($arr[7]);
				if(!empty($res))
					dbDeleBlogByID($arr[7]);

				dbInsertBlog($arr,$content_arr);
			}
			else
			{
				$res = dbGetCommentsByFloor($arr[8], $arr[9], 1);
				if($res->num_rows != 0)
					dbDeleComment($arr[8],$arr[9]);

				dbInsertComment($arr,$content_arr);
			}
		}
	}
	else
		echo 'failed:'.$f_name.'(error 2)<br/>'."\n";

}
else
	echo 'failed(error 3)<br/>error code:'.$err."\n";

?>

</body>
</html>
