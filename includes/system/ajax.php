<?php

namespace system;

class Ajax
{

  public function __construct()
  {
    add_action( 'wp_ajax_searchUSer', [ $this, 'searchUSer' ] );
    add_action( 'wp_ajax_nopriv_searchUSer', [ $this, 'searchUSer' ] );

    add_action( 'wp_ajax_getEvents', [ $this, 'getEvents' ] );
    add_action( 'wp_ajax_nopriv_getEvents', [ $this, 'getEvents' ] );

    add_action( 'wp_ajax_getMemberEvents', [ $this, 'getMemberEvents' ] );
    add_action( 'wp_ajax_nopriv_getMemberEvents', [ $this, 'getMemberEvents' ] );

    add_action( 'wp_ajax_checkInUser', [ $this, 'checkInUser' ] );
    add_action( 'wp_ajax_nopriv_checkInUser', [ $this, 'checkInUser' ] );
  }

  public function searchUSer()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) ) {
      if ( $users = search_member( 'memberMisc1', $_REQUEST['input'] ) ) {
        wp_send_json_success( [ 'data' => $users ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No user was found.' ], 405 );
  }

  public function getEvents()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) ) {
      $range = explode( ' - ', str_replace( '/', '-', $_REQUEST['input'] ) );
      if ( $events = search_events_by_range( $range ) ) {
        wp_send_json_success( [ 'data' => $events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 405 );
  }

  public function getMemberEvents()
  {
    $user_events = [];
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) && isset( $_REQUEST['memberId'] ) ) {
      $range = explode( ' - ', str_replace( '/', '-', $_REQUEST['input'] ) );

      if ( $events = search_events_by_range( $range ) ) {
        foreach ( $events as $event ) {
          if ( isset( $event['members'] ) ) {
            foreach( $event['members'] as $member ) {
              if ( $member['memberId'] == $_REQUEST['memberId'] ) {
                $user_events[] = $event;
              }
            }
          }
        }

        wp_send_json_success( [ 'data' => $user_events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 405 );
  }

  public function checkInUser()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) && isset( $_REQUEST['memberId'] ) ) {
      $event_id = intval( sanitize_text_field( $_REQUEST['input'] ) );
      $member_id = intval( sanitize_text_field( $_REQUEST['memberId'] ) );
      if ( $result = subscribeUser( $event_id, $member_id ) ) {
        wp_send_json_success( [ 'data' => $result ], 200 );
      } else {
        wp_send_json_error( [ 'code' => 301, 'message' => 'Api return error.' ], 405 );
      }
    }

    wp_send_json_error( [ 'code' => 300, 'message' => 'Some error happens.' ], 405 );
  }

}
