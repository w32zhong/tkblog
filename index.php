<!DOCTYPE html>
<html lang="zh-CN">

<head>
<title>tk 的博客</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Zhong Wei">
<link href="favicon.ico" rel="SHORTCUT ICON">

<?php
require_once('resource/calendar/calendar.php');
require_once('resource/comment/comments.php');
require_once('resource/leave_comment/leave_comment.php');
require_once('resource/blog_frame/blog_frame.php');
require_once('resource/blog_frame/gadgets.php');
require_once('resource/phrase/phrase.php');
require_once('music.cfg.php');
require_once('access_sql.php');
?>

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

<!--jquery-->
<script src="plugin/jplayer/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<!--jplayer-->
<link href="plugin/jplayer/css/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script src="plugin/jplayer/jquery.jplayer.min.js" type="text/javascript" charset="utf-8"></script>
<script src="plugin/jplayer/jplayer.playlist.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
//	$(document).snowfall({shadow : true, round : true, flakeCount : 50,minSize: 2, maxSize:4});
	window.onscroll = function() {
		updateFixedBar();
	}

	var beg_money = false;
	$('#alipay').hide().click(function () {
		$('#alipay').hide();
		$('#thanks').show();
	});
	$('#thanks').show().click(function () {
		$('#alipay').show();
		$('#thanks').hide();
	});

	var tkblogPlaylist = new jPlayerPlaylist({
		jPlayer: "#tkblog_j_player",
		cssSelectorAncestor: "#tkblog_j_html_container"
	}, 
	[
		<?php
			global $g_playlist_arr;
			$comma_lock = true;
			foreach ($g_playlist_arr as $mp3_file_name => $appear_name) {
				if (!$comma_lock) {echo ',';}
				echo '{';
				echo "title: '${appear_name}', ";
				echo "mp3: 'music/${mp3_file_name}.mp3'";
				echo '}';
				$comma_lock = false;
			}
		?>
	],
	{
		playlistOptions: {
			autoPlay: <?php global $g_playlist_auto_play; echo "$g_playlist_auto_play";?>,
			enableRemoveControls: true 
		},
		swfPath: "plugin/jplayer/bin/jquery.jplayer.swf",
		// solution: "flash, html", //uncomment to test flash
		supplied: "mp3",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: false,
		audioFullScreen: false
	});
});
</script>
<!--calendar-->
<link type="text/css" rel="stylesheet" href="resource/calendar/calendar.css"></link>
<!--comment-->
<link type="text/css" rel="stylesheet" href="resource/comment/comments.css"></link>
<!--search-->
<link type="text/css" rel="stylesheet" href="resource/search/mysearch.css"></link>
<!--leave_comment-->
<script language="javascript" src="resource/leave_comment/leave_comment.js"></script>
<link type="text/css" rel="stylesheet" href="resource/leave_comment/leave_comment.css"></link>
<!--MD5 function-->
<script language="javascript" src="plugin/md5.js"></script>
<!--Ajax-->
<script language="javascript" src="resource/ajax/post.js"></script>
<!--load button-->
<script language="javascript" src="resource/loadbutton/loadbutton.js"></script>
<link type="text/css" rel="stylesheet" href="resource/loadbutton/loadbutton.css"></link>
<!--blog_frame-->
<script language="javascript" src="resource/blog_frame/blog_frame.js"></script>
<link type="text/css" rel="stylesheet" href="resource/blog_frame/blog_frame.css"></link>
<!--mathjax CDN-->
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
	tex2jax: {
		inlineMath: [['[imath]','[/imath]']],
		displayMath: [['[dmath]','[/dmath]']]
	},

	showMathMenu: false, /* do not show menu */

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
<!-- 
We use mathjax 2.5 latest.
-->
<script type="text/javascript"
src="plugin/MathJax/MathJax.js?config=TeX-AMS-MML_SVG">
</script>
<!--CSS-->
<style>
/*style*/
img.icon {
max-width:32px;
max-height:32px;
}
/* Desktop and landscape tablets */
.desktop-visible { display: none; }
@media (min-width: 768px) {
.desktop-visible { display: block; }
}
</style>
</head>
<!--frame_init will load the initial posts-->
<body onload="frame_init();">
<div class="layout">
<div class="left_column desktop-visible">
<div id="left_column_top">
<?php 
echoAbout();
//echoNotice();
//echoAD();
//echoFriendsUrls();
echoSearch();
echo '<div id="reader_rank">';
echoReaderRank();
echo '</div>';
echoTranslate();
echoBlogArchives();
echoTags();
echo '<div id="recent_comments" style="font: normal 12px sans-serif;">';
echoRecentComments(); 
echo '</div>';
//echoStatistic();
?>
</div>
<div id="left_column_bottom">
<?php
echoMusicPlayer();
echoDonate();
echoFeed();
?>
</div>
</div>

<div class="right_column">
<div id="blogs_column"></div>
<?php
echoMoreBlogsButton();
?>
</div>

</div>
</body>
</html>
