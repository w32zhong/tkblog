<?php
require_once('search_curl.php');
function ret_client($error, $desc) {
	$return_json = array();
	$return_json['error'] = $error;
	$return_json['desc'] = $desc;
	echo json_encode($return_json);
	exit;
}


/* debug only */
if (false) {
# $request = array("action" => 'index', 'path' => '../blog/2015/8/blog/2015-08-25-01-25-1074.txt');
# $request = array("action" => 'search', 'query' => 'gcc', 'page' => 1);
echo search_request($request);
exit;
}
/* ---------- */

$request = array();
if(!isset($_GET['action'])) {
	ret_client(true, 'no action specified');
}
$request['action'] = $_GET['action'];

if ($request['action'] == 'search') {
	if (!isset($_GET['query'])) {
		ret_client(true, 'no query specified');
	} else if (!isset($_GET['page'])) {
		ret_client(true, 'no page specified');
	}

	$request['query'] = $_GET['query'];
	$request['page'] = intval($_GET['page']);
	$ret_json = search_request($request);
	echo $ret_json;
	exit;
} else if ($request['action'] == 'index') {
	if (!isset($_GET['path'])) {
		ret_client(true, 'no path specified');
	} else {
		$request['path'] = $_GET['path'];
		$ret_json = search_request($request);
		echo $ret_json;
		exit;
	}
} else if ($request['action'] == 'index_all') {
	$ret_json = search_request($request);
	echo $ret_json;
	exit;
} else if ($request['action'] == 'clear') {
	$ret_json = search_request($request);
	echo $ret_json;
	exit;
} else {
	ret_client(true, 'not supported option');
}
?>
