<html>
<head>
<title>PHPMailer - SMTP test</title>
</head>
<body>

<?php
error_reporting(E_STRICT);
date_default_timezone_set('Asia/Shanghai');
require_once('class.phpmailer.php');

$mail             = new PHPMailer();
$body             = 'aaaa好';

$mail->IsSMTP();
$mail->Host       = "smtp.126.com";
$mail->SMTPDebug  = 2;
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "ssl";
$mail->Port       = 465;
$mail->Username   = "clock126";
$mail->Password   = "g131517131517";

$mail->CharSet = "UTF-8";
$mail->SetFrom('clock126@126.com', 'Zhong Wei');
$mail->Subject    = "感谢";
$mail->MsgHTML($body);

$address = "914963117@qq.com";
$mail->AddAddress($address, "visitor");

$mail->Send();
echo 'OK';
?>
</body>
</html>
