<?php
/*
 * Template Name: Email Proxy
 */

function return_json($result) {
    die(json_encode($result));
}

if(!defined('EMAIL_API_KEY') || !defined('EMAIL_URL') ) {
    return_json(array("error" => "Looks like youre missing EMAIL_API_KEY or EMAIL_URL in wp-config.php"));
}

$nonce_action = 'inside-api';
if(!wp_verify_nonce($_GET['_wpnonce'], $nonce_action)) {
    $result = array('error' => 'invalid nonce');
    return_json($result);
}

$args = $_GET;
$args = array_merge($args, array('apikey' => EMAIL_API_KEY));

// FIXME on error
//wp_mail( $to, $subject, $message, $headers);
$ch = curl_init(EMAIL_URL.'email_search.php?'.http_build_query($args));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));

$result = curl_exec($ch);
echo $result;
