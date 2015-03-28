<?php
/*
 * Template Name: Email Proxy
 */

function return_json($result) {
    die(json_encode($result));
}

if(!defined('EMAIL_API_USERNAME') || !defined('EMAIL_API_PASSWORD') || !defined('EMAIL_URL')) {
    return_json(array("error" => "Looks like you are missing a setting in wp-config.php"));
}

$nonce_action = 'inside-api';
if(!wp_verify_nonce($_GET['_wpnonce'], $nonce_action)) {
    $result = array('error' => 'invalid nonce');
    return_json($result);
}

$args = array(
    'domain__name' => 'studentersamfundet.no',
);
if(isset($_GET['q'])) {
    $args['source_regex'] = $_GET['q'];
}
// FIXME on error
//wp_mail( $to, $subject, $message, $headers);
$ch = curl_init(EMAIL_URL."aliases/".'?'.http_build_query($args));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_USERPWD, EMAIL_API_USERNAME.":".EMAIL_API_PASSWORD);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));

$aliases = json_decode(curl_exec($ch));

$result = array();

foreach ($aliases as $alias) {
    if(array_key_exists($alias->source, $result)) {
        $result[$alias->source]['num'] += 1;
        $result[$alias->source]['destinations'][] = $alias->destination;
    } else {
        $result[$alias->source] = array(
            'name' => $alias->source,
            'href' => '&q=' . $alias->source,
            'num' => 1,
            'type' => 'aliases',
            'admin_url' => 'https://lister.neuf.no',
            'admin_type' => 'selfservice',
            'destinations' => array($alias->source)
        );
    }
}
sort($result);
echo json_encode(array(
    'meta' => array('num' => count($result)),
    'lists' => $result
));
