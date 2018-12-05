<!DOCTYPE html>
<html lang="zh-CN">

<head>
<title>卑鄙的 t.k.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Zhong Wei">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<link href="favicon.ico" rel="SHORTCUT ICON">

<!--syntaxhighlighter-->
<link type="text/css" rel="stylesheet" href="plugin/syntaxhighlighter/styles/shCore.css"></link>
<link type="text/css" rel="stylesheet" href="plugin/syntaxhighlighter/styles/shThemeEclipse.css"></link>
<link type="text/css" rel="stylesheet" href="plugin/syntaxhighlighter/styles/shCoreEclipse.css"></link>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shCore.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushBash.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushXml.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushSql.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushPython.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushCpp.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushAsm.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushLatex.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushMake.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushPlain.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushLisp.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushVhdl.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushPerl.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushDiff.js"></script>
<script language="javascript" src="plugin/syntaxhighlighter/scripts/shBrushLua.js"></script>

<!-- mermaid.js -->
<script src="plugin/mermaid/mermaid.min.js"></script>
<script>mermaid.initialize({startOnLoad:true});</script>

<!--blog_frame-->
<link type="text/css" rel="stylesheet" href="resource/blog_frame/blog_frame.css"></link>
<!--mathjax CDN-->
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
	tex2jax: {
		inlineMath: [['[imath]','[/imath]']],
		displayMath: [['[dmath]','[/dmath]']]
	},

	// showMathMenu: false, /* do not show menu */

	/* all I want is to disable fast-preview */
	// CHTMLpreview: false,
	// 'fast-preview': {disabled: true},
	menuSettings: {CHTMLpreview: false}
});

MathJax.Hub.Register.StartupHook("End Jax",function () {
		var jax = "SVG"; /* use SVG to avoid chrome trailing space vertical line bug */
		return MathJax.Hub.setRenderer(jax);
});
</script>
<script type="text/javascript"
src="plugin/MathJax/MathJax.js?config=TeX-AMS_HTML">
</script>
<!-- 
We use mathjax 2.5 latest, this version does not have double-render disappear problem.
src="https://cdn.mathjax.org/mathjax/2.5-latest/MathJax.js?config=TeX-AMS_HTML"> 
-->
<!--CSS-->
<style>
div.entry
{
font:normal 12px sans-serif;
/*white-space:nowrap; */
}
@media print {
	div.pagebreak {page-break-after:always}
}
</style>
</head>
<?php
require_once('resource/blog_frame/blog_frame.php');
require_once('resource/comment/comments.php');
require_once('resource/phrase/phrase.php');
require_once('access_sql.php');

echo '<body onload="SyntaxHighlighter.highlight();">';

if(isset($_GET['id']))
{
	$blog_id = $_GET['id'];
	$max_id = dbGetMaxBlogID();
	$min_id = 1;

	$blog_id = intval($blog_id);
	$prev_id = $blog_id - 1;
	$next_id = $blog_id + 1;

	echo '<div style="width: 210mm; margin-left: 2cm; margin-right: 2cm;">';
	echo '<div class="entry">';
	echo '<div class="title" style="text-align: center;">';
	
	// echo the raw text file link
	$blog = dbGetBlogByID($blog_id);
	$blog_file_addr = $blog['path'].'/'.$blog['f_name'];
	//echo $blog_file_addr;
	/*echo "<a href=\"${blog_file_addr}\">raw</a> | ";
	
	// echo `previous' and `next' link
	if($blog_id > $min_id)
	{
		echo "<a href=\"entry.php?id=${prev_id}\">Previous ID</a>";
		echo ' | ';
	}
	
	if($blog_id < $max_id)
	{
		echo "<a href=\"entry.php?id=${next_id}\">Next ID</a>";
		echo ' | ';
	}
	
	// echo author info 
	echo ' posted by <a href="index.php">t.k.</a>';
	*/
	echo "<a href=\"${blog_file_addr}\">_ </a>";

	echo '</div>';
	echo '<br />';
	echoBlogEntry($blog, 1);
	echo '</div>';
	echo '</div>';
} else if(isset($_GET['recent_comments'])) {
	$recent_comments = $_GET['recent_comments'];
	$res = dbGetRecentComments($recent_comments);
	for($i=0; $i< $res->num_rows; $i++)
	{
		$comment = $res->fetch_assoc();
		$content = IgnoreFileHead(file_get_contents(
			$absDirPrefix.$comment['path'].'/'.$comment['f_name']));
		echoComment($comment, $content, true);
		echo '====================';
	}
}else
	echo 'ID unset';
?>
