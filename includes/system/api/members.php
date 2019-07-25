<?php

namespace system\api;

trait Members
{
  public function get_members()
  {
    $this->set_request_type( 'members' );
    return $this->get_responce();
  }
}
