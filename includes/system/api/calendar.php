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
    $client_id = get_option( 'abcf_client_id', false );
    $client_secret = get_option( 'abcf_client_secret', false );
    $app_id = get_option( 'abcf_id', false );
    $app_key = get_option( 'abcf_key', false );
    $token = get_transient( 'oauth_user')['tokens']['access_token'];
    $club_number = get_option( 'abcf_cNumber', null );

    if( !$client_id || !$client_secret || !$app_id || !$app_key || !$token ){
      return false;
    }

    $url = "https://api.abcfinancial.com/rest/{$club_number}/calendars/secured/events/{$event_id}/members/{$member_id}/attendance";

    $args = [
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'method' => "PUT",
      'body'  => '{ "source": "MEM" }',
      'headers' => [
        'Accept' => 'application/json;charset=UTF-8',
        'Content-Type' => 'application/json',
        'app_id' => $app_id,
        'app_key' => $app_key,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'token' => $token,
      ],
    ];

    $request = wp_remote_request( $url, $args );

    if( isset( $request['response']['code'] ) && $request['response']['code'] == 404 ){
      return false;
    }

    wpabcf()->cache->delete_all();
    return json_decode( $request['body'], true );
  }
}
