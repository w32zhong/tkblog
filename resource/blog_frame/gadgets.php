<?php

function echoDonate()
{
	echoColumnTitle("欢迎来自支付宝的宠幸");
	echo '<br/>';
	echo '<img id="thanks" src="resource/icon/thanks.png"/>';
	echo '<img id="alipay" src="resource/icon/alipay.png"/>';
}

function echoAD()
{
	echoColumnTitle('广告');
	//
	echo '<span class="main_font">';
	echo '博主还在努力支撑独立博客，感谢支持。';
	echo '</span>';
	//
	echo '</br><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- my-ad --> <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-1125169447018538" data-ad-slot="9753417407"></ins> <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>';
}

function echoNotice()
{
	echoColumnTitle('通告');
	echo '<ul class="main_font">';
	echo '<li>站内搜索暂时用<a target="_blank" href="https://www.google.com.hk/?q=site:thoughts-of.me/entry.php">Google</a>。</li>';
	echo '</ul>';
}

function echoFriendsUrls()
{
	echoColumnTitle('Blogroll');
	echo '<ul class="main_font">';
	echo '<li><img src="resource/icon/heart.png"/>的<a href="http://www.lvqunbai.blog.sohu.com/" target="_blank">博客</a></li>';
	echo '<li>小虎的<a href="http://weibo.com/debrashuang" target="_blank">新浪微博</a></li>';
	echo '<li>贴膜的小jie（大湿）的<a href="http://blog.163.com/jilianglijie@126/" target="_blank">博客</a></li>';
	echo '<li>章文嵩的<a href="http://weibo.com/wensong8" target="_blank">新浪微博</a></li>';
	echo '<li>Tianli Yu(俞天力)的<a href="http://tianliresearch.blogspot.com/" target="_blank">blogspot</a></li>';
	echo '<li>kingsamchen的<a href="http://blog.kingsamchen.com/" target="_blank">博客</a></li>';
	echo '<li>Fabrice Bellard的<a href="http://bellard.org/" target="_blank">主页</a></li>';
	echo '<li>peng的<a href="http://pengliu.me/" target="_blank">博客</a></li>';
	echo '</ul>';
}

function echoTags()
{
	echoColumnTitle('标签');
	echo '<ul class="main_font">';
	echo '<li id="tag_life_id"><a href="index.php?tag=life" target="_Blank">生活篇</a></li>';
	echo '<li id="tag_work_id"><a href="index.php?tag=work" target="_Blank">工作篇</a></li>';
	echo '<li id="tag_all_id"><a href="index.php?tag=all" target="_Blank">全部文章</a></li>';
	echo '</ul>';
}

function echoSubscribe($img_src, $link)
{
	echo '<div style="margin-top:10px;">';
	echo "<a href=\"${link}\" target=\"_blank\">";
	echo "<img border=\"0\" src=\"${img_src}\"/></a><br/>";
	echo '</div>';
}

function echoAbout()
{
	echoColumnTitle('<img style="margin-bottom: -10px;padding-right: 20px;" src="resource/icon/icon.jpeg"/> tk 的博客', true);
	echo '<span class="main_font">';
	//echo '<br/>联系方式:';
	echo '<br/>';
	
	echo '<img class="icon" src="resource/icon/mail.png"/>&nbsp;&nbsp;<img src="resource/icon/email_addr.png"/>'
	.'<br/>';
	echo '<br/>';
	echo '<img class="icon" src="resource/icon/github.png"/>&nbsp;&nbsp;请有空常来 <a href="https://github.com/t-k-/" target="_Blank">Github </a> 探望我。'
	.'<br/>';
//	echo '<img class="icon" src="resource/icon/vi_icon.png"/>&nbsp;&nbsp;如果你是 Chrome 用户，欢迎试用<br/>我的英语阅读插件<a href="https://chrome.google.com/webstore/detail/voice-instead/kphdioekpiaekpmlkhpaicehepbkccbf/" target="_Blank"> VI</a>。';
	
	echo '</span>';
}

function echoFeed()
{
	echoColumnTitle('订阅本站');
	//echoColumnTitle('<img class="icon" src="resource/icon/rss.png"/>&nbsp;&nbsp;订阅本站');
	echo '<span class="main_font">';

	echoSubscribe('resource/icon/btn_rss.gif',
	'http://feeds.feedburner.com/despicabletk');
	echo '<br/>';

echo '<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh"><img alt="知识共享许可协议" style="border-width:0" src="resource/icon/cc.png" /></a><br />';
echo '<span class="little_font">本博客涉及的原创内容使用<a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh">by-nc-nd协议</a>进行许可。</span>';

	echo '</span>';
}

function echoMyDog()
{
echoColumnTitle("善良的狗");
	echo '<!--http://abowman.com-->
<object type="application/x-shockwave-flash" style="outline:none;" data="http://hosting.gmodules.com/ig/gadgets/file/102399522366632716596/dog.swf?up_feetColor=FFFFFF&up_waterColor=DAF1F5&up_legLength=5&up_collarColor=ED4251&up_backgroundColor=FFFFFF&up_eyeColor=444444&up_foodBowlColor=FF0000&up_ballColor=FF0000&up_foodColor=C48218&up_earColor=EBD88D&up_treatColor=EEEEEE&up_tailTipColor=FFFFFF&up_bodyColor=EBD88D&up_tongueColor=FFCCCC&up_boneColor=EEEEEE&up_waterBowlColor=B4DDF0&up_dogName=aBowman Dog&up_noseColor=333333&" width="300" height="225"><param name="movie" value="http://hosting.gmodules.com/ig/gadgets/file/102399522366632716596/dog.swf?up_feetColor=FFFFFF&up_waterColor=DAF1F5&up_legLength=5&up_collarColor=ED4251&up_backgroundColor=FFFFFF&up_eyeColor=444444&up_foodBowlColor=FF0000&up_ballColor=FF0000&up_foodColor=C48218&up_earColor=EBD88D&up_treatColor=EEEEEE&up_tailTipColor=FFFFFF&up_bodyColor=EBD88D&up_tongueColor=FFCCCC&up_boneColor=EEEEEE&up_waterBowlColor=B4DDF0&up_dogName=aBowman Dog&up_noseColor=333333&"></param><param name="AllowScriptAccess" value="always"></param><param name="wmode" value="opaque"></param><param name="bgcolor" value="FFFFFF"/></object>';
}
function echoCommons()
{
echoColumnTitle("署名");
echo '<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh"><img alt="知识共享许可协议" style="border-width:0" src="resource/icon/cc.png" /></a><br />';
echo '<span class="little_font">本博客涉及的原创内容使用<a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh">by-nc-nd协议</a>进行许可。</span>';
}

function echoMusicPlayer()
{
	echoColumnTitle("音乐播放器");
	echo '<br/>';
	# echo '<span class="main_font">音乐</span><br/>';
	echo '
<div id="tkblog_j_player" class="jp-jplayer"></div>
<div id="tkblog_j_html_container" style="width:250px;" class="jp-audio" role="application" aria-label="media player">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<div class="jp-controls" style="padding-left:5px;">
				<button class="jp-play" role="button" tabindex="0">play</button>
			</div>
			<div class="jp-progress" style="left:50px; width:110px">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>
			<div class="jp-time-holder" style="left:50px; width:125px">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
			</div>
			<div class="jp-volume-controls" style="left:175px;">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-toggles" style="left:185px;">
				<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>JPlayer Update Required</span>
		</div>
	</div>
</div>
	';
}

function echoBlogArchives()
{
	$arc = dbBlogArchives();
	echo '<span class="main_font">';
	echoColumnTitle("时间点");
	echo '某一个时间点以前的文章<br/>';
	echo '<ul>';
	for($i=0; $i< $arc->num_rows; $i++)
	{
		$row = $arc->fetch_assoc();
		$y = $row['year'];
		$m = $row['month'];
		$c = $row['count'];
		echo "<li><a href=\"index.php?time=${y}-${m}-31\" target=\"_Blank\">${y}年${m}月</a> (${c})</li>";
	}

	echo '</ul>';
	echo '</span>';
}

function echoRecentComments($num = 12)
{
	global $absDirPrefix;

	echoColumnTitle("最新评论");
	echo '<br/>';
	$res = dbGetRecentComments($num);

	for($i=0; $i< $res->num_rows; $i++)
	{
		$comment = $res->fetch_assoc();
		$content = IgnoreFileHead(
				file_get_contents($absDirPrefix.$comment['path'].'/'.$comment['f_name']) );
		echoComment($comment, $content, true);
	}
}

function echoTranslate()
{
	echoColumnTitle('Translate');
	echo '<script type="text/javascript">
	function _echoTranslate() {
		var srpt1 = "<" + "script ";
		var srpt2 = "<" + "/script" + ">";
		$("#google_translate_anchor").hide();
		$("#google_translate_script").html(srpt1 + \'type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">\' + srpt2);
	}
	function googleTranslateElementInit() {
		new google.translate.TranslateElement({pageLanguage: \'zh-CN\', includedLanguages: \'de,en,fr,hy,it,ja,ko,ru,zh-TW\', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, \'google_translate_element\');
	}
	</script>';

	echo '<div id="google_translate_script"></div>';
	echo '<div id="google_translate_element"></div>';
	echo '<a id="google_translate_anchor" href="javascript:void(0)" onclick="_echoTranslate();"> click here to request translation.</a>';
}

function echoSearch()
{
	//echoColumnTitle('站内搜索');
	echo '<br/>';
	echo '
	<form action="search.php" class="mysearch" method="get" target="_blank">
	<div style="padding-top: 10px;">
	  <button type="submit" style="left: 0px"></button>
	  <input type="text" name="q" placeholder="站内搜索...">
	</div>
	</form>
	';
	echo '<br/>';
}

function echoReaderRank()
{
	$res = dbReaderRank(10);

	echo '<span class="main_font">';
	echoColumnTitle("读者 qiang");
	echo '</br>';

	for($i=0; $i< $res->num_rows; $i++)
	{
		$row = $res->fetch_assoc();
		$name = $row['name'];
		$email = $row['email'];
		$url = $row['url'];
		$comments = $row['comments'];
		$email_md5 = md5(strtolower(trim($email)));
		$name_url = '';

		if($url == 'no')
			$name_url = $name;
		else
			$name_url = '<a href="http://'.$url.'" title="访问他/她的主页" target="_Blank">'.$name."</a>";

		$img = '<div class="comment_image"><img src="resource/icon/default-gravatar32.jpg"/></div>';
		
		echo '<div style="height:40px">';
		echo "${img}${name_url} (${comments})";
		echo '</div>';
	}
	
	echo '</span>';
}

function echoColumnTitle($title,$ifTopTitle = false)
{
	if($ifTopTitle)
		echo '<div class="main_font column_top_title">'.$title.'</div>';
	else
		echo '<div class="main_font column_title">'.$title.'</div>';
}

function echoStatistic()
{
	echoColumnTitle('访问统计');
	echo '<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id=\'cnzz_stat_icon_1257759448\'%3E%3C/span%3E%3Cscript src=\'" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1257759448%26show%3Dpic2\' type=\'text/javascript\'%3E%3C/script%3E"));</script>';
}
?>
