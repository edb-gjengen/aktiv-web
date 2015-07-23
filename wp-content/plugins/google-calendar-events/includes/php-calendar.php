<?php
# PHP Calendar (version 2.3), written by Keith Devens
# http://keithdevens.com/software/php_calendar
#  see example at http://keithdevens.com/weblog
# License: http://keithdevens.com/software/license

/*
Changes made to original PHP Calendar script by me (Ross Hanney):

- Renamed CSS classes to fit with my plugin
- Slight modification of lines 63-71 to use Unix timestamp rather than day number
- Renamed function to prevent conflicts
- Replaced strftime with date_i18n
- Use of $wp_locale to get weekday initials
- Replaced htmlentities() with esc_attr() and esc_html()
- Other small markup changes
- Replaced gmmktime() with mktime()
*/

function gce_generate_calendar( $year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array(), $widget = false ) {
	global $wp_locale;
	
	$paging = false;
	
	if( ! empty( $pn ) ) {
		$paging = true;
	}

	$first_of_month = mktime( 0, 0, 0, $month, 1, $year );
	#remember that mktime will automatically correct if invalid dates are entered
	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
	# this provides a built in "rounding" feature to generate_calendar()

	$day_names = array(); #generate all the day names according to the current locale
	for ( $n = 0, $t = ( 3 + $first_day ) * 86400; $n < 7; $n++, $t += 86400 ) { #January 4, 1970 was a Sunday
		$day_names[$n]['full'] = date_i18n( 'l', $t, true );
		$day_names[$n]['initial'] = $wp_locale->get_weekday_initial( date_i18n( 'l', $t, true ) );
	}

	list( $month, $year, $month_name, $weekday ) = explode( ',', date_i18n( 'm, Y, F, w', $first_of_month ) );
	$weekday = ( $weekday + 7 - $first_day ) % 7; #adjust for $first_day
	$title = esc_html( $month_name ) . '&nbsp;' . $year;  #note that some locales don't capitalize month and day names

	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
	list( $p, $pl ) = each( $pn );
	list( $n, $nl ) = each( $pn ); #previous and next links, if applicable
	
	// Previous filter
	$p = apply_filters( 'gce_prev_text', $p );
	
	// Next filter
	$n = apply_filters( 'gce_next_text', $n );
	
	if( $widget ) {
		$p = '<div class="gce-prev">' . ( ( $pl ) ? ( '<a href="#" class="gce-change-month" title="' . esc_attr__( 'Previous', 'gce' ) . '" name="' . esc_attr( $pl ) . '" data-gce-grid-paging="' . esc_attr( $paging ) . '">' . $p . '</a>' ) : $p ) . '</div>';
	} else {
		$p = '<div class="gce-prev">' . ( ( $pl ) ? ( '<a href="#" class="gce-change-month" title="' . esc_attr__( 'Previous', 'gce' ) . '" name="' . esc_attr( $pl ) . '" data-gce-grid-paging="' . esc_attr( $paging ) . '">' . $p . '</a>' ) : $p ) . '</div>';
	}
	
	if( $widget ) {
		$n = '<div class="gce-next">' . ( ( $nl ) ? ( '<a href="#" class="gce-change-month" title="' . esc_attr__( 'Next', 'gce' ) . '" name="' . esc_attr( $nl ) . '" data-gce-grid-paging="' . esc_attr( $paging ) . '">' . $n . '</a>' ) : $n ) . '</div>';
	} else {
		$n = '<div class="gce-next">' . ( ( $nl ) ? ( '<a href="#" class="gce-change-month" title="' . esc_attr__( 'Next', 'gce' ) . '" name="' . esc_attr( $nl ) . '" data-gce-grid-paging="' . esc_attr( $paging ) . '">' . $n . '</a>' ) : $n ) . '</div>';
	}
	
	$calendar = '<table class="gce-calendar">' . "\n" .
				'<caption class="gce-caption">' .
				'<div class="gce-navbar">' .
				$p .
	            $n .
	            '<div class="gce-month-title">' . ( ( $month_href ) ? ( '<a href="' . esc_attr( $month_href ) . '">' . esc_html( $title ) . '</a>' ) : esc_html( $title ) ) . '</div>' .
				'</div>' .
				'</caption>' . "\n" .
				'<tr>' . "\n";

	if ( $day_name_length ) { #if the day names should be shown ($day_name_length > 0)
		#if day_name_length is >3, the full name of the day will be printed
		foreach ( $day_names as $d ) {
			$calendar .= '<th><abbr title="' . esc_attr( $d['full'] ) . '">' . esc_html( $d['initial'] ) . '</abbr></th>';
		}

		$calendar .= "</tr>\n<tr>";
	}

	$time_now = current_time( 'timestamp' );
	$today = mktime( 0, 0, 0, date( 'm', $time_now ), date( 'd', $time_now ), date( 'Y', $time_now ) );

	if ( $weekday > 0 ) $calendar .= '<td colspan="' . esc_attr( $weekday ) . '">&nbsp;</td>'; #initial 'empty' days
	for ( $day = 1, $days_in_month = date( 't', $first_of_month ); $day <= $days_in_month; $day++, $weekday++ ) {
		if ( 7 == $weekday ) {
			$weekday = 0; #start a new week
			$calendar .= "</tr>\n<tr>";
		}

		$timestamp = mktime( 0, 0, 0, $month, $day, $year );

		if ( isset( $days[$timestamp] ) && is_array( $days[$timestamp] ) ) {
			list( $link, $classes, $content ) = $days[$timestamp];
			$calendar .= '<td' . ( ( $classes ) ? ( ' class="' . esc_attr( $classes ) . '">' ) : '>' ) . ( ( $link ) ? ( '<a href="' . esc_url( $link ) . '"><span class="gce-day-number">' . esc_html( $day ) . '</span></a>' . $content ) : '<span class="gce-day-number">' . esc_html( $day ) . '</span>' . $content ) . '</td>';
		}else{
			$css_class = ( $timestamp < $time_now ) ? 'gce-day-past' : 'gce-day-future';
			$calendar .= '<td class="' . esc_attr( $css_class ) . '"><span class="gce-day-number">' . esc_html( $day ) . '</span></td>';
		}
	}

	if ( 7 != $weekday ) $calendar .= '<td colspan="' . esc_attr( ( 7 - $weekday ) ) . '">&nbsp;</td>'; #remaining "empty" days

	return $calendar . "</tr>\n</table>\n";
}