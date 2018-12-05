<?php

function echoCalendar($Y, $m, $j, $id)
{
	$m_str = GetMonthStr($m);

	echo '<div class="calendar">';
	echo "<span class=\"month\">$m_str</span>";
	echo "<a href=\"entry.php?id=${id}\" title=\"${Y}年${m}月${j}日,ID=${id}\">";
	echo "<span class=\"day\">$j</span>";
	echo '</a>';
	echo '</div>';
}

function GetMonthStr($m)
{
	$month = 'NUL';

	switch($m)
	{
	case 1:
		$month = 'Jan';
		break;
	case 2:
		$month = 'Feb';
		break;
	case 3:
		$month = 'Mar';
		break;
	case 4:
		$month = 'Apr';
		break;
	case 5:
		$month = 'May';
		break;
	case 6:
		$month = 'Jun';
		break;
	case 7:
		$month = 'Jul';
		break;
	case 8:
		$month = 'Aug';
		break;
	case 9:
		$month = 'Sep';
		break;
	case 10:
		$month = 'Oct';
		break;
	case 11:
		$month = 'Nov';
		break;
	case 12:
		$month = 'Dec';
		break;
	}
	
	return $month;
}
?>
