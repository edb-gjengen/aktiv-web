<?php
/*
 * Template Name: Email Proxy
 */

function return_json($result) {
    die(json_encode($result));
}

$required_settings = ['EMAIL_API_USERNAME', 'EMAIL_API_PASSWORD', 'EMAIL_URL', 'MAILMAN_API_USERNAME',
    'MAILMAN_API_PASSWORD', 'MAILMAN_URL'];

foreach ($required_settings as $setting) {
    if( !defined($setting) ) {
        return_json(array("error" => "Looks like you are missing $setting or more settings in wp-config.php"));
    }
}

$nonce_action = 'inside-api';
if(!wp_verify_nonce($_GET['_wpnonce'], $nonce_action)) {
    $result = array('error' => 'invalid nonce');
    return_json($result);
}

/* Aliases */
$args = array(
    'domain__name' => 'studentersamfundet.no',
);
if(isset($_GET['source'])) {
    $args['source__iregex'] = '^'.$_GET['source'].'$';
}
if(isset($_GET['destination'])) {
    $args['destination__iregex'] = '^'.$_GET['destination'].'$';
}

$ch = curl_init(EMAIL_URL."aliases/".'?'.http_build_query($args));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_USERPWD, EMAIL_API_USERNAME.":".EMAIL_API_PASSWORD);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));

$aliases = json_decode(curl_exec($ch), true);

/* Mailman */
$ch = curl_init(MAILMAN_URL."lists/".'?'.http_build_query($args));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_USERPWD, MAILMAN_API_USERNAME.":".MAILMAN_API_PASSWORD);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));

$mailman_lists = json_decode(curl_exec($ch), true);

// Note: $mailman_list is a list of objects like below
$result = $mailman_lists['lists'];

foreach ($aliases as $alias) {
    if(array_key_exists($alias['source'], $result) && $result[$alias['source']]['type'] == 'aliases') {
        $result[$alias['source']]['num'] += 1;
        $result[$alias['source']]['destinations'][] = $alias['destination'];
    } else {
        // Add a new object with meta-data
        $result[$alias['source']] = array(
            'name' => $alias['source'],
            'num' => 1,
            'type' => 'aliases',
            'admin_url' => 'https://lister.neuf.no/lists/?q='. $alias['source'],
            'admin_type' => 'selfservice',
            'destinations' => array($alias['source'])
        );
    }
}
sort($result);
return_json(array(
    'meta' => array('num' => count($result)),
    'lists' => $result
));
