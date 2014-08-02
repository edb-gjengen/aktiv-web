<?php
/*
 * Template Name: Inside Proxy
 */
define('INSIDE_URL', 'https://inside.studentersamfundet.no/api/');

function return_json($result) {
    die(json_encode($result));
}

if( !isset($_GET['q']) ) {
    return_json(array('error' => 'Missing arg q'));
}

$nonce_action = 'inside-api';
if(!wp_verify_nonce($_GET['_wpnonce'], $nonce_action)) {
    $result = array('error' => 'invalid nonce');
    return_json($result);
}
$args = array(
    'q' => $_GET['q'],
    'apikey' => INSIDE_API_KEY
);

//$send_result = wp_mail( $to, $subject, $message, $headers);
$ch = curl_init(INSIDE_URL.'/user.php?'.http_build_query($args));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));

$result = curl_exec($ch);
echo $result;
