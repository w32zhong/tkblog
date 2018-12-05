/* ========== old version music player ==========
function echoMusicItem($appear_name, $mp3_file_name)
{
echo '<li><a href="javascript:void(null);" onclick="changeMusicTo(this, '.
	"'$mp3_file_name'".')">'.$appear_name.'</a></li>';
}

function echoMusicList()
{
global $g_playlist_arr;
echoColumnTitle("音乐列表");
echo '<span class="main_font">时不时收集几首难听的歌曲骚扰你';
echo '<ol>';

foreach ($g_playlist_arr as $mp3_file_name => $appear_name) {
	echoMusicItem('♪'.$appear_name.'♪', $mp3_file_name);
}

echo '</ol>';
echo '</span>';
}

function echoMusicPlayer($first_mp3_name = 'xiaoxingyun')
{
$if_auto_play = $GLOBALS['g_playlist_auto_play'];
echoColumnTitle("播放器");
echo '<br />';
echo '<div id="music_player_div">';
echo '<embed src="plugin/musicplayer.swf?soundFile=music/'.$first_mp3_name.'.mp3&amp;';
echo 'bg=0x550000&amp;';
echo 'leftbg=0xAA0000&amp;';
echo 'lefticon=0xFFFFFF&amp;';
echo 'rightbg=0xDD4B39&amp;';
echo 'rightbghover=0x4499EE&amp;';
echo 'righticon=0xF2F2F2&amp;';
echo 'righticonhover=0xFFFFFF&amp;';
echo 'text=0xDD4B39&amp;';
echo 'slider=0xDD4B39&amp;';
echo 'track=0xFFFFFF&amp;';
echo 'border=0xFFFFFF&amp;';
echo 'loader=0xFFFFFF&amp;';
echo 'autostart='.$if_auto_play.'&amp;loop=yes" type="application/x-shockwave-flash" quality="high" width="290" height="24"></embed>';
echo '</div>';
echo '<script type="text/javascript">
function changeMusicTo(obj, name)
{
	var mp3_name = name;
    var str = document.getElementById(\'music_player_div\').innerHTML;
	var now = GetDocumentScrollOffset();
	str = str.replace(/autostart=no/,\'autostart=yes\');
	str = str.replace(/soundFile=.*?&/,\'soundFile=music/\' + mp3_name + \'.mp3&\');
	document.getElementById(\'music_player_div\').innerHTML = str;
}
</script>';
}
========================================================= */
