<?php

use Tribe\Events\Views\V2\Utils;
use Tribe__Date_Utils as Dates;

$should_have_time_separator = Utils\Separators::should_have_time( $this->get( 'events' ), $event );

if ( ! $should_have_time_separator || ! empty( $event->timeslot ) ) {
	return;
}

$event_start_hour = strtotime( Dates::round_nearest_half_hour( $event->dates->start_display->format( Dates::DBDATETIMEFORMAT ) ) );

$display_date = empty( $is_past ) && ! empty( $request_date )
	? max( $event->dates->start_display, $request_date )
	: $event->dates->start_display;

$event_week_day  = $display_date->format_i18n( 'l' );
$event_day_num   = $display_date->format_i18n( 'j' );
$event_date_attr = $display_date->format( Dates::DBDATEFORMAT );
$event_start_hour= $display_date->format_i18n( 'h:i A' );

?>
<div class='event-details'>
  <?php echo $event_week_day . ' at ' . $event_start_hour ?>
</div>
