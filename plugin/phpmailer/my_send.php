<?php
require_once('class.phpmailer.php');

function MySend($to_addr, $url, $mail_notify)
{
	date_default_timezone_set('Asia/Shanghai');
	ob_start();

	if($mail_notify)
	{
		$mail             = new PHPMailer();
		$body             = '您在t.k.博客里的留言有人@，您可以访问下面链接查看：'.'<br/>';
		$body            .= "<a href=\"${url}\">${url}</a>".'<br/>';

		$mail->IsSMTP();

$mail->Host       = "smtp.126.com";
$mail->Port       = 465;
$mail->Username   = "clock126";
$mail->Password   = "g131517131517";
$mail->SetFrom('clock126@126.com', 'Zhong Wei');

		$mail->SMTPDebug  = 2;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "ssl";

		$mail->CharSet = "UTF-8";
		$mail->Subject = "来自t.k.的通知";
		$mail->MsgHTML($body);

		$mail->AddAddress($to_addr, "visitor");

		$mail->Send();

		$log_content = ob_get_contents()."\n";
		$log_content .= "--------- ${to_addr} ---------- END.\n";
	}
	else
		$log_content = "--------- ${to_addr} -------- refused.\n";

	ob_end_clean();
	
	$h_file = fopen("../../my_send.log" , "a");
	fwrite($h_file,$log_content);
	fclose($h_file);
}
?>
