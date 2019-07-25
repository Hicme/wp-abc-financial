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

    $this->set_request_type( 'calendars/events?eventDateRange=' . $date_range );
    return $this->get_responce();
  }
}
