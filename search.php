<!DOCTYPE html> 
<html>
<head>
<title>tk的博客</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Zhong Wei">
<link href="favicon.ico" rel="SHORTCUT ICON">
<script type="text/javascript" src="plugin/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="resource/search/search.js"></script>
<!--search bar-->
<link href="plugin/bootstrap/bootstrap.min.css" rel="stylesheet">
<!-- this external css is only to import anchor color css -->
<link type="text/css" rel="stylesheet" href="resource/blog_frame/blog_frame.css"></link>

<style>
hr {
	margin: auto;
	display: block;
	border-style: inset;
	border-width: 1px;
	margin-bottom: 20px;
}
</style>
<script>
$( document ).ready(function() {
<?php
	if(isset($_GET["q"])) {
		$query = $_GET["q"];
		// echo "alert('query: $query')";
		echo "init_search('$query');";
	}
?>
});
</script>
</head>

<body>

<?php
require_once('resource/search/search_box.php');
echoSearchBox(300, "input_box", "whateveritis", 'button');
?>

<hr>

<div id="container">
<!-- THIS IS A TEMPLATE
<ol>
<li><a href="index.php?id=1067">index.php?id=1067</a><br/>
<pre>
A working code for CLucence index/search.
A working code for CLucence index/search.
</pre>
</li>
<li><a href="index.php?id=1067">index.php?id=1067</a><br/>
<pre>
A working code for CLucence index/search.
A working code for CLucence index/search.
</pre>
</li>
</ol>
<hr>
<table style="width:400px">
<tr>
<td>prev</td> <td>page 1/3</td> <td>next</td>
</tr>
</table>
-->
</div>
</body>
</html>
