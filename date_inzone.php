<?php 
// $zone = 'Asia/Shanghai';
function display_time_in($zone)
{
	date_default_timezone_set($zone);
	$dt = new DateTime();
	echo "$zone: ".$dt->format('Y-m-d H:i:s')."<br />";
}

function date_inzone($zone, $format) 
{
	date_default_timezone_set($zone);
	$dt = new DateTime();
	return $dt->format($format);
}
?>
