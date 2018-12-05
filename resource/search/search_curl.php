<?php
function search_request($request)
{
	$port = '8901';
	$request_json = json_encode($request);
	$request_head = array('Content-Type: application/json',
						  'Content-Length: '.strlen($request_json));
	$c = curl_init('http://localhost:'.$port);
	curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($c, CURLOPT_POSTFIELDS, $request_json);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_HTTPHEADER, $request_head);

	# echo 'request: '.$request_json."\n";
	$return = curl_exec($c);
	# print_r(json_decode($return));
	if (curl_errno($c)) {
		$return = array("error" => true, 'desc' => curl_error($c));
		$return = json_encode($return);
	}
	return $return;
}
?>
