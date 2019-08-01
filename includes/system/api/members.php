<?php

namespace system\api;

trait Members
{
  public function get_members()
  {
    $data = get_transient( 'api/members' );

    if( !$data ){
      $this->set_method( 'GET' );
      $this->set_request_type( 'members' );
      $data = $this->get_responce();
      set_transient( 'api/members', $data, 10800 );
    }

    return $data;
  }
}
