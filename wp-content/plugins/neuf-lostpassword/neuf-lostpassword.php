<?php
/*
Plugin Name: Neuf Lost Password
Plugin URI: http://studentersamfundet.no/
Description: Changes the lost password URL.
Version: 1.0
Author: Just Thomas Misund
Author URI: http://hemmeligadresse.com/
License: GPLv2 or later
*/

/**
 * Send users away.
 */

function neuf_lostpassword_url( $pwd ) {
	$pwd = 'https://brukerinfo.neuf.no/accounts/password/reset';
	return $pwd;
}
add_filter( 'lostpassword_url' , 'neuf_lostpassword_url');
?>
