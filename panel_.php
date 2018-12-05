<!DOCTYPE html>
<html lang="zh-CN">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>添砖加瓦</title>
</head>
<head>
<?php
require_once('resource/comment/comments.php');
require_once('resource/leave_comment/leave_comment.php');
require_once('resource/blog_frame/blog_frame.php');
require_once('resource/blog_frame/gadgets.php');
require_once('resource/phrase/phrase.php');
require_once('resource/search/search_curl.php');
require_once('access_sql.php');
require_once('date_inzone.php');

function deleteDir($dir) 
{ 
	if (is_dir($dir) && rmdir($dir)==false) {
		if ($dp = opendir($dir)) {
			while (($file = readdir($dp)) != false) {
				if (is_dir($file) && $file != '.' && $file != '..') {
					deleteDir($file);
				} else {
					echo "unlink $dir/$file ";
					if (unlink($dir.'/'.$file))
						echo "successful.<br/>";
					else
						echo "failed.<br/>";
				}
			}
		}

		rmdir($dir);
		closedir($dp);
	} else {
		echo "permission problem.";
		exit;
	}
}

if(isset($_GET['init']))
{
	$val = $_GET['init'];
	if($val == 'drop')
	{
		dbInit(true);
		echo 'init db (drop) finished.';
	}
	else if($val == 'db')
	{
		dbInit();
		echo 'init db finished.';
	}
	else if($val == 'timezone')
	{
		date_default_timezone_set('Asia/Shanghai');
		echo 'init timezone finished.';
	}
	else
	{
		echo 'input not valide';
	}

	exit;
}

if(isset($_GET['del_draft']))
{
	deleteDir('./blog/draft');
	echo 'deleted.';
	exit;
}

if(isset($_GET['rm_to_id']) && isset($_GET['rm_sub_id']))
{
	$to_id = $_GET['rm_to_id'];
	$sub_id = $_GET['rm_sub_id'];
	
	$comment = dbGetCommentByID($to_id, $sub_id);
	$location = $comment['path'].'/'.$comment['f_name'];
	echo 'comment file location: '.$location.'<br/>';
	
	dbDeleComment($to_id, $sub_id);
	unlink($location);

	echo 'remove done';
	exit;
}

if(isset($_GET['rm_id']))
{
	$id = $_GET['rm_id'];
	
	$blog = dbGetBlogByID($id);
	$location = $blog['path'].'/'.$blog['f_name'];
	echo 'post file location: '.$location.'<br/>';

	dbDeleBlogByID($id);
	unlink($location);

	echo 'remove done';
	exit;
}

$zone = 'Asia/Shanghai';
echo 'php 当前时间: ';
display_time_in($zone);
echo "<br />";

?>
<form action="file_input.php" method="post" enctype="multipart/form-data">
<input type="radio" name="ifPublish"/>发表
<input type="file" name="files" />
<input type="submit" value="OK" />
</form>

<br/>
快捷链接：
<br/>
<a href="entry.php?id=0">查看草稿<br/></a>
<a href="entry.php?id=1089">查看计划<br/></a>
<a href="rss_gen.php">产生RSS<br/></a>
<a href="my_send.log">邮件发送log<br/></a>
<br/>

<br/>
快速本地 index：
<br/>
curl "http://127.0.0.1/tkblog/resource/search/search_req.php?action=index_all"
<br/>

<br/>
删除本地 index：
<br/>
curl "http://127.0.0.1/tkblog/resource/search/search_req.php?action=clear"
<br/>

<br/>
本页参数：<br/>
?init=drop<br/>
?init=db<br/>
?init=timezone<br/>
?init=srch<br/>
?del_draft<br>
?rm_to_id=xx&amp;rm_sub_id=xx<br/>
?rm_id=xx<br/>

<br/>
entry.php 参数：<br/>
?id=xx<br/>
?recent_comments=5<br/>

<br/>
pull.php 参数：<br/>
?recent_comments=5<br/>

<br/>
没有选择发表时：<br/>
若名为.blog后缀 当作是草稿，id设置为0；<br/>
按照格式year-month-day-hour-minute-...<br/>
若不符合该格式，归入老博客 twbook的image里面；<br/>
符合格式的情况下:<br/>
若为类似 2011-03-12-05-41-0.txt 格式导入博文；
若为 src-2011-03-12-05-41-some name.png 格式导入引用资源；<br/>
若为 com-2011-03-12-05-41-to_id-sub_id.txt 格式导入评论。<br/>
<br/>
选择发表以后：<br/>
若名为.blog后缀 则发表博文；<br/>
否则发表资源。<br/>
<br/>
可以使用的html标签：embed,a,{img style="display:block; margin-left:auto; margin-right:auto;"}, em(强调),i(斜体),ul,ol,li,b,strike,pre,h[1-6] (可以有类似style="text-align: center"的属性), table(可以有border=“n”属性), tr, td(可以有colspan=“n”或者rowspan=“n”属性), th, sub, sup.<br/>
(html标签如果紧跟着\n和另外一个html标签则\n会被删除) <br/>
可以使用的博文标签: 工作篇，生活篇，隐藏，未分类。<br/>
可以使用的自定义标签:[code lan="Language" hi="2,3,4" fl="1" ln="false"][key][kbd][cmd][quote][face][cut_more][imath][dmath][photo][underline][overline][link][page_break][diagram].<br/>
code的参数可以使用：
<pre>
Language    Aliases
bash        bash
XML/HTML    xml, html, xhtml, xslt
Sql         sql
Python      py, python
PHP         php
JavaScript  js, jscript, javascript
CSS         css
C++         cpp, c, c++
Latex       latex
assemble    asm
makefile    make
plain       plain, text
LISP        lisp
vhdl        vhdl
perl        perl, Perl, pl
diff        diff, patch
lua         lua
</pre>
<br/>
<?php
echo "<h2>统计</h2>";
if (dbTestTableExists('blog')) {
	echo 'blog post max ID: ';
	echo dbGetMaxBlogID();
	echo '<br/>';
	echo 'blog post number: ';
	echo dbStatNumOfBlogPosts();
	echo '<br/>';
	echo 'comments number: ';
	echo dbStatNumOfComments();
	echo '<br/>';
} else {
	echo '表不存在.';
}
echo "<br />";
//echoRecentComments(15);

$face_dir = 'resource/leave_comment/faces';
$f_it = new FilesystemIterator($face_dir, FilesystemIterator::SKIP_DOTS);
$f_cnt= iterator_count($f_it);
printf("There were %d gifs:<br/>", $f_cnt);
echo '<br/>';

for ($i = 1; $i <= $f_cnt; $i++) {
	$tag='[face]'."$i".'[/face]';
	echo ReplaceSelfDefTags_face($tag)."($tag)";
}

?>
</body>
</html>
