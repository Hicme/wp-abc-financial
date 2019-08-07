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

      if( $data ){
        wpabcf()->cache->set( 'api_calendar-' . $request, $data, 10800 );
      }
    }

    return $data;
  }

  public function get_employees( $employe_id )
  {
    $request = $employe_id;
    $data = wpabcf()->cache->get( 'api_employe-' . $request );

    if( !$data ){
      $this->set_method( 'GET' );
      $this->set_request_type( 'employees/' . $employe_id );
      $data = $this->get_responce();

      if( $data ){
        wpabcf()->cache->set( 'api_employe-' . $request, $data, 10800 );
      }
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
