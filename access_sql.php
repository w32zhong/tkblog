<?php 
$host_name = '127.0.0.1';
$usr_name = 'thoughts_ga6840';
$password = 'xxxxxxxxxxxxx';
$database = 'thoughts_ga6840';

function dbOpen()
{
	global $host_name,$usr_name,$password,$database;
	$db = new mysqli($host_name,$usr_name,$password,$database);
	
	if(mysqli_connect_errno())
	{	
		echo 'database error 1<br/>';
		exit;
	}

	return $db;
}

function dbQuire($db, $q)
{
	$res = $db->query($q);
	if(!$res)
	{
		echo "database error 2 when query: ${q}<br/>";
		exit;
	}

	return $res;
}

function dbClose($db)
{
	$db->close();
}

function dbInit($ifDropTable = false)
{
	$db = dbOpen();

	if($ifDropTable)
	{
		dbQuire($db , 'drop table blog');
		dbQuire($db , 'drop table comment');
	}
	
	dbQuire($db , 'create table blog(
			id      int unsigned primary key,
			path    char(64),
			f_name  char(64),
			tag     char(32),
			time    date);
		');

	dbQuire($db , 'create table comment(
			to_id    int unsigned,
			sub_id   int unsigned,
			path     char(64),
			f_name   char(64),
			time     datetime,
			name     char(64),
			IP       char(64),
			u_agent  text,
			email    char(64),
			url      char(64),
			mail_notify bool);
		');
	
	dbClose($db);
}

function dbInsertComment($arr,$content_arr)
{
	//if (!get_magic_quotes_gpc())
	//{
	//	$safe_to_id = addslashes($arr[8]);
	//	$safe_name = addslashes($content_arr['name']);
	//	$safe_u_agent = addslashes($content_arr['u_agent']);
	//	$safe_email = addslashes($content_arr['email']);
	//	$safe_url =addslashes($content_arr['url']); 
	//}
	//else
	{
		$safe_to_id = $arr[8];
		$safe_name = $content_arr['name'];
		$safe_u_agent = $content_arr['u_agent'];
		$safe_email = $content_arr['email'];
		$safe_url =$content_arr['url']; 
	}

	$mail_notify_bool = 0;
	if($content_arr['mail_notify'] == 'true')
		$mail_notify_bool = 1;
	
	$db = dbOpen();
	$q = "insert into comment values(".
			"'${safe_to_id}', '${arr[9]}', '${arr[6]}', '${arr[10]}', ".
			"'${arr[1]}-${arr[2]}-${arr[3]} ${arr[4]}:${arr[5]}:00', ".
			"'${safe_name}', '${content_arr['IP']}', ".
			"'${safe_u_agent}', '${safe_email}', ".
			"'${safe_url}', '${mail_notify_bool}' );";
	dbQuire($db,$q);

	dbClose($db);
}

function dbDeleComment($to_id,$sub_id)
{
	$db = dbOpen();
	$q = "delete from comment where to_id=${to_id} and sub_id=${sub_id};";
	dbQuire($db,$q);
	dbClose($db);
}

function dbInsertBlog($arr,$content_arr)
{
	$db = dbOpen();

	$q = "insert into blog values(".
			"'${arr[7]}', '${arr[6]}', '${arr[10]}', '${content_arr['tag']}',".
			"'${arr[1]}-${arr[2]}-${arr[3]}'); ";

	dbQuire($db,$q);
	dbClose($db);
}

function dbDeleBlogByID($id)
{
	$db = dbOpen();
	$q = "delete from blog where id=${id};";
	dbQuire($db,$q);
	dbClose($db);
}

function dbGetBlogByID($id)
{
	$db = dbOpen();
	$q = "select *, year(time) as year, month(time) as month, day(time) as day ".
	     "from blog where id=${id};";
	
	$res = dbQuire($db,$q);
	$blog = $res->fetch_assoc();
	
	dbClose($db);

	return $blog;
}

function dbGetCommentNameAndEmailByID($to_id, $sub_id)
{
	$db = dbOpen();
	$q = "select name, email, mail_notify from comment where ".
		"to_id='${to_id}' and sub_id='${sub_id}'";
	
	$res = dbQuire($db,$q);
	$comment = $res->fetch_assoc();
	
	dbClose($db);

	return $comment;
}

function dbGetCommentByID($to_id, $sub_id)
{
	$db = dbOpen();
	$q = "select * from comment where ".
		"to_id='${to_id}' and sub_id='${sub_id}'";
	
	$res = dbQuire($db,$q);
	$comment = $res->fetch_assoc();
	
	dbClose($db);

	return $comment;
}

function dbGetTotalComments($to_id)
{
	$db = dbOpen();
	$q = "select count(*) as total from comment where to_id=${to_id};";
	
	$res = dbQuire($db,$q);
	$assoc_res = $res->fetch_assoc();
	
	dbClose($db);

	return $assoc_res['total'];
}

function dbGetMaxBlogID()
{
	$db = dbOpen();
	$q = "select max(id) as max_id from blog;";
	
	$res = dbQuire($db,$q);
	$assoc_res = $res->fetch_assoc();
	
	dbClose($db);

	return $assoc_res['max_id'];
}

function dbGetMaxSubIDOfComments($to_id)
{
	$db = dbOpen();
	$q = "select max(sub_id) as max_sub_id from comment where to_id=${to_id};";
	
	$res = dbQuire($db,$q);
	$assoc_res = $res->fetch_assoc();
	
	dbClose($db);

	return $assoc_res['max_sub_id'];
}

function dbGetCommentsByFloor($to_id, $floorFrom, $NumQuire)
{
	$db = dbOpen();
	$from = intval($floorFrom) - 1;
	
	$q = "set @seq:=0;";
	$res = dbQuire($db,$q);
	$q = "select *, year(time) as year, month(time) as month, day(time) as day, hour(time) as hour, minute(time) as minute from ".
	     "(select *, @seq:=@seq+1 as floor from comment where to_id=${to_id} order by sub_id) ".
             "a order by floor limit ${from},${NumQuire};";
	$res = dbQuire($db,$q);
	
	dbClose($db);
	return $res;
}

function dbGetRecentComments($num = 3)
{
	$db = dbOpen();
	
	$q = "set @seq:=0;";
	$res = dbQuire($db,$q);
	$q = "select *, year(time) as year, month(time) as month, day(time) as day, hour(time) as hour, ".
	     "minute(time) as minute from (select *, @seq:=@seq+1 as seq from comment order by time desc limit 0,${num}) ".
	     "a order by seq;";
	$res = dbQuire($db,$q);
	
	dbClose($db);
	return $res;
}

function dbGetBlogsByFilters($id_filter, $tag_filter, $time_filter, 
		$keyword_filter, $from, $pace)
{
	$db = dbOpen();
	$start = intval($from) - 1;
	$tag_selector = "and (tag='__impossible__')";

	if($tag_filter == 'all')
	{
		$tag_selector = "and (tag='工作篇' or tag='生活篇' or tag='未分类')";
	}
	else if($tag_filter == 'life')
	{
		$tag_selector = "and tag='生活篇'";
	}
	else if($tag_filter == 'work')
	{
		$tag_selector = "and tag='工作篇'";
	}
	else if($tag_filter == 'hide_')
	{
		$tag_selector = "and tag='隐藏'";
	}
	else if($tag_filter == 'whole')
	{
		$tag_selector = '';
	}

	$id_filter = intval($id_filter);
	$id_selector = "id='${id_filter}'";

	if( $id_filter == 0 )
		$id_selector = 'id > 0';
	else if( $id_filter < 0 )
		$id_selector = 'id = 0';

	$q = "select *, year(time) as year, month(time) as month, day(time) as day ".
		 "from blog where time <= '${time_filter}' ".
	     "${tag_selector} and ${id_selector} order by time desc, id desc ".
		 "limit ${start},${pace};";

	$res = dbQuire($db,$q);
	
	dbClose($db);
	return $res;
}

function dbBlogArchives()
{
	$db = dbOpen();
	$q = 'select year(time) as year, month(time) as month, count(id) as count from blog where id <> 0 group by year(time),month(time) order by year(time) desc,month(time) desc;';
	
	$res = dbQuire($db,$q);
	dbClose($db);

	return $res;
}

function dbReaderRank($num)
{
	$db = dbOpen();
	$q = "select name, email, url, count(email) as comments from comment where email <> 'clock126@126.com' group by email order by comments desc limit 0,$num;";
	
	$res = dbQuire($db,$q);
	dbClose($db);

	return $res;
}

function dbTestTableExists($table)
{
	$db = dbOpen();
	$q = "show tables like '".$table."';";
	
	$res = dbQuire($db,$q);
	dbClose($db);

	return ($res->num_rows != 0);
}

function dbStatNumOfComments()
{
	$db = dbOpen();
	$q = "select count(*) as total from comment;";
	
	$res = dbQuire($db,$q);
	$assoc_res = $res->fetch_assoc();
	
	dbClose($db);

	return $assoc_res['total'];
}

function dbStatNumOfBlogPosts()
{
	$db = dbOpen();
	$q = "select count(*) as total from blog;";
	
	$res = dbQuire($db,$q);
	$assoc_res = $res->fetch_assoc();
	
	dbClose($db);

	return $assoc_res['total'];
}
