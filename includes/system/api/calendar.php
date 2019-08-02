<?php

namespace system\api;

trait Calendar
{
  public function get_events( $eventDateRange )
  {
    if( is_array( $eventDateRange ) ){
      $date_range = $eventDateRange[0] . ',' . $eventDateRange[1];
    }else{
      $date_range = $eventDateRange;
    }

    $request = md5( $date_range );

    $data = wpabcf()->cache->get( 'api_calendar-' . $request );

    if( !$data ){
      $this->set_method( 'GET' );
      $this->set_request_type( 'calendars/events?eventDateRange=' . $date_range );
      $data = $this->get_responce();
      wpabcf()->cache->set( 'api_calendar-' . $request, $data, 10800 );
    }

    return $data;
  }

  public function subscribe_to_event( $event_id, $member_id )
  {
    $this->set_method( 'POST' );
    $this->set_request_type( "calendars/secured/events/{$event_id}/members/{$member_id}" );
    return $this->get_responce();
  }
}
