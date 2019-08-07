<?php

namespace system\api;

trait Members
{
  public function get_members()
  {
    $data = wpabcf()->cache->get( 'api_members' );

    if( !$data ){
      $this->set_method( 'GET' );
      $this->set_request_type( 'members' );
      $data = $this->get_responce();

      if( $data ){
        wpabcf()->cache->set( 'api_members', $data, 10800 );
      }
    }

    return $data;
  }
}
