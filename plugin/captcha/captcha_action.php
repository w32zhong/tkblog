<?php
session_start();
$Code = $_POST["Turing"]; 

if ( !isset( $_SESSION['turing_string'] ) ) { $ok = 1; }
else if ( strtoupper($_SESSION['turing_string']) == strtoupper($Code) ) { $ok = 1; }
else { 	$ok = 0;}

echo $ok;
?>
