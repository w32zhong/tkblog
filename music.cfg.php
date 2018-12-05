<?php
$g_playlist_arr = array();
$files = scandir("./music");
foreach ($files as $key => $fname) {
	$parts = explode('.', $fname);
	$ext = array_pop($parts);
	if ($ext == "mp3") {
		$songname = basename($fname,".mp3");
		$g_playlist_arr[$songname] = $songname;
	}
}

/* Example $g_playlist_arr:
'Clean Bandit' => 'Clean Bandit',
'Maroon 5 - Girls Like You' => 'Girls Like You',
'taishan' => '隔壁泰山'
*/

$g_playlist_auto_play = 'false';
?>
