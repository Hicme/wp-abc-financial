<?php

namespace system;

class Ajax
{

  public function __construct()
  {
    add_action( 'wp_ajax_getSelect', [ $this, 'getSelect' ] );
    add_action( 'wp_ajax_nopriv_getSelect', [ $this, 'getSelect' ] );

    add_action( 'wp_ajax_searchUSer', [ $this, 'searchUSer' ] );
    add_action( 'wp_ajax_nopriv_searchUSer', [ $this, 'searchUSer' ] );

    add_action( 'wp_ajax_getEvents', [ $this, 'getEvents' ] );
    add_action( 'wp_ajax_nopriv_getEvents', [ $this, 'getEvents' ] );

    add_action( 'wp_ajax_getTodayEvents', [ $this, 'getTodayEvents' ] );
    add_action( 'wp_ajax_nopriv_getTodayEvents', [ $this, 'getTodayEvents' ] );

    add_action( 'wp_ajax_getMemberEvents', [ $this, 'getMemberEvents' ] );
    add_action( 'wp_ajax_nopriv_getMemberEvents', [ $this, 'getMemberEvents' ] );

    add_action( 'wp_ajax_checkInUser', [ $this, 'checkInUser' ] );
    add_action( 'wp_ajax_nopriv_checkInUser', [ $this, 'checkInUser' ] );
  }

  public function getSelect()
  {
    $date = time();
    $eventsName = [];
    $eventsEmployee = [];

    if ( $events = search_events_by_range( [ date('Y-m-d', $date ), date('Y-m-d', strtotime( '+30 day', $date ) ) ] ) ) {
      foreach( $events as $event ) {
        $eventsName[$event['eventName']] = $event['eventName'];

        if( !empty( $event['employeeId'] ) ){
          $eventsEmployee[$event['employeeId']] = $event['employeeId'];
        }
      }

      $response = [
        'eventsName' => array_values( $eventsName ),
        'eventsEmployee' => array_values( $eventsEmployee )
      ];

      wp_send_json_success( [ 'data' => $response ], 200 );
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No data was found.' ], 404 );
  }

  public function searchUSer()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) ) {
      if ( $users = search_member( 'memberMisc1', $_REQUEST['input'] ) ) {
        wp_send_json_success( [ 'data' => $users ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No user was found.' ], 404 );
  }

  public function getEvents()
  {
    $date = isset( $_REQUEST['date'] ) && !empty( $_REQUEST['date'] ) ? strtotime( $_REQUEST['date'] ) : time();
    $time = isset( $_REQUEST['time'] ) && !empty( $_REQUEST['time'] ) ? intval( sanitize_text_field( $_REQUEST['time'] ) ) : 11;
    $instructor = isset( $_REQUEST['instructor'] ) && !empty( $_REQUEST['instructor'] ) ? sanitize_text_field( $_REQUEST['instructor'] ) : false;
    $keyword = isset( $_REQUEST['keyword'] ) && !empty( $_REQUEST['keyword'] ) ? sanitize_text_field( $_REQUEST['keyword'] ) : false;

    if ( $events = search_events_by_range( [ date('Y-m-d', $date ), date('Y-m-d', strtotime( '+1 day', $date ) ) ] ) ) {
      
      $events = $this->filter_by_time( $events, $time );

      if ( $instructor ) {
        $events = $this->filter_by_instructor( $events, $instructor );
      }

      if ( $keyword ) {
        $events = $this->filter_by_keyword( $events, $keyword );
      }

      if ( $events ) {
        $events = $this->prepare_response( $events );
        wp_send_json_success( [ 'data' => $events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 404 );
  }

  public function getTodayEvents()
  {
    $keyword = get_option( 'abcf_presearch', '' );
    $time = 11;

    if ( $events = search_events_by_range( [ date('Y-m-d'), date('Y-m-d', strtotime( '+1 day', time() ) ) ] ) ) {
      $events = $this->filter_by_time( $events, $time );

      if ( $keyword ) {
        $events = $this->filter_by_keyword( $events, $keyword );
      }

      if ( $events ) {
        $events = $this->prepare_response( $events );
        wp_send_json_success( [ 'data' => $events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 404 );
  }

  public function getMemberEvents()
  {
    $user_events = [
      'this_week' => [],
      'next_weeks' => []
    ];

    if ( isset( $_REQUEST['memberId'] ) && !empty( $_REQUEST['memberId'] ) ) {
      $range = [];
      $location_name = get_option( 'abcf_location', '' );
      $range[] = date('Y-m-d');
      $range[] = date('Y-m-d', strtotime( '+31 days', time() ));
      $week_end = strtotime( "next sunday", time() );

      if ( $events = search_events_by_range( $range ) ) {
        foreach ( $events as $event ) {
          if ( isset( $event['members'] ) ) {
            foreach( $event['members'] as $member ) {
              if ( $member['memberId'] == $_REQUEST['memberId'] ) {
                $location_time = strtotime( $event['eventTimestamp'] );
                $parsed = [
                  'eventId' => $event['eventId'],
                  'day' => date( 'm/d/y', $location_time ),
                  'time' => date( 'h:i a', $location_time ),
                  'eventName' => $event['eventName'],
                  'employee' => $event['employeeId'],
                  'location' => $location_name,
                  'duration' => $event['duration'],
                ];
                if ( $location_time > $week_end ) {
                  $user_events['next_weeks'][] = $parsed;
                }else{
                  $user_events['this_week'][] = $parsed;
                }
              }
            }
          }
        }

        wp_send_json_success( [ 'data' => $user_events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 404 );
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

    wp_send_json_error( [ 'code' => 300, 'message' => 'Some error happens.' ], 409 );
  }

  private function filter_by_time( array $locations, int $time )
  {
    $response = [];
    foreach( $locations as $location ){
      $location_time = date( 'H', strtotime( $location['eventTimestamp'] ) );

      switch( $time ){
        case 11:
          if ( $location_time <= 11 ) {
            $response[] = $location;
          }
          break;

        case 14:
          if ( $location_time > 11 && $location_time < 16 ) {
            $response[] = $location;
          }
          break;

        case 16:
          if ( $location_time >= 16 ) {
            $response[] = $location;
          }
          break;
      }
    }

    return $response;
  }

  private function filter_by_instructor( array $locations, string $instructor )
  {
    $response = [];
    foreach( $locations as $location ){
      if ( stripos( strtolower( $location['employeeId'] ), strtolower( $instructor ) ) !== false ) {
        $response[] = $location;
      }
    }

    return $response;
  }

  private function filter_by_keyword( array $locations, string $keyword )
  {
    $response = [];
    foreach( $locations as $location ){
      if ( stripos( strtolower( $location['eventName'] ), strtolower( $keyword ) ) !== false ) {
        $response[] = $location;
      }
    }

    return $response;
  }

  private function prepare_response( array $locations )
  {
    $response = [];

    $location_name = get_option( 'abcf_location', '' );

    foreach( $locations as $key => $location ){
      $location_time = strtotime( $location['eventTimestamp'] );
      $timestamp = date('l m/d/Y', $location_time );
      $response[ $timestamp ]['date'] =  $timestamp;
      $response[ $timestamp ]['events'][$key] = [
        'eventId' => $location['eventId'],
        'day' => date( 'm/d/y', $location_time ),
        'time' => date( 'h:i a', $location_time ),
        'eventName' => $location['eventName'],
        'employee' => $location['employeeId'],
        'location' => $location_name,
        'duration' => $location['duration'],
      ];

      if ( $location['members'] ) {
        foreach( $location['members'] as $member ){
          $response[ $timestamp ]['events'][$key]['members'][] = $member['memberId'];
        }
      } else {
        $response[ $timestamp ]['events'][$key]['members'] = false;
      }
    }

    return $response;
  }

}
