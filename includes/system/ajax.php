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

    add_action( 'wp_ajax_searchUserById', [ $this, 'searchUserById' ] );
    add_action( 'wp_ajax_nopriv_searchUserById', [ $this, 'searchUserById' ] );

    add_action( 'wp_ajax_getEvents', [ $this, 'getEvents' ] );
    add_action( 'wp_ajax_nopriv_getEvents', [ $this, 'getEvents' ] );

    add_action( 'wp_ajax_getAttendanceEvents', [ $this, 'getAttendanceEvents' ] );
    add_action( 'wp_ajax_nopriv_getAttendanceEvents', [ $this, 'getAttendanceEvents' ] );

    add_action( 'wp_ajax_getTodayEvents', [ $this, 'getTodayEvents' ] );
    add_action( 'wp_ajax_nopriv_getTodayEvents', [ $this, 'getTodayEvents' ] );

    add_action( 'wp_ajax_getMemberEvents', [ $this, 'getMemberEvents' ] );
    add_action( 'wp_ajax_nopriv_getMemberEvents', [ $this, 'getMemberEvents' ] );

    add_action( 'wp_ajax_checkInUser', [ $this, 'checkInUser' ] );
    add_action( 'wp_ajax_nopriv_checkInUser', [ $this, 'checkInUser' ] );

    add_action( 'wp_ajax_processOAuth', [ $this, 'processOAuth' ] );
    add_action( 'wp_ajax_nopriv_processOAuth', [ $this, 'processOAuth' ] );

    add_action( 'wp_ajax_processOAuthEmail', [ $this, 'processOAuthEmail' ] );
    add_action( 'wp_ajax_nopriv_processOAuthEmail', [ $this, 'processOAuthEmail' ] );

    add_action( 'wp_ajax_getLastError', [ $this, 'getLastError' ] );
    add_action( 'wp_ajax_nopriv_getLastError', [ $this, 'getLastError' ] );

    add_action( 'wp_ajax_getLastError', [ $this, 'getLastError' ] );
    add_action( 'wp_ajax_nopriv_getLastError', [ $this, 'getLastError' ] );

    add_action( 'wp_ajax_sendMemberNotification', [ $this, 'sendMemberNotification' ] );
    add_action( 'wp_ajax_nopriv_sendMemberNotification', [ $this, 'sendMemberNotification' ] );
  }

  public function processOAuth()
  {
    if( isset( $_REQUEST['code'] ) && !empty( $_REQUEST['code'] ) ){
      $tokens = $this->try_get_token( [ 'action' => 'processOAuth' ] );
      $user_id = $this->try_get_userID( $tokens );
      $redirect = [
        'login' => $user_id,
      ];

      $this->success_user_redirect( $redirect, $user_id, $tokens );
    }

    if( isset( $_REQUEST['error'] ) && $_REQUEST['error'] === 'access_denied' ){
      $this->throw_error_redirect( 'access_denied', __( 'User denied access.', 'wpabcf' ) );
    }
  }

  public function processOAuthEmail()
  {
    if( isset( $_REQUEST['code'] ) && !empty( $_REQUEST['code'] ) ){

      $tokens = $this->try_get_token( [ 'action' => 'processOAuthEmail', 'eventId' => $_REQUEST['eventId'] ] );
      $user_id = $this->try_get_userID( $tokens );
      $redirect = [
        'emailNoty' => $user_id,
        'eventId' => $_REQUEST['eventId']
      ];

      $this->success_user_redirect( $redirect, $user_id, $tokens );
    }

    if( isset( $_REQUEST['error'] ) && $_REQUEST['error'] === 'access_denied' ){
      $this->throw_error_redirect( 'access_denied', __( 'User denied access.', 'wpabcf' ) );
    }
  }

  private function try_get_userID( $tokens )
  {
    $client_id = get_option( 'abcf_client_id', false );
    $client_secret = get_option( 'abcf_client_secret', false );
    $app_id = get_option( 'abcf_id', false );
    $app_key = get_option( 'abcf_key', false );

    if( !$client_id || !$client_secret || !$app_id || !$app_key ){
      $this->throw_error_redirect( 'no_keys', __( 'No client keys added.', 'wpabcf' ) );
    }

    $url = 'https://api.abcfinancial.com/uaa/oauth/validateToken';

    $url = add_query_arg( [
      'access_token' => $tokens['access_token'],
      'user' => $app_id,
    ], $url );

    $args = [
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers' => [
        'Accept' => 'application/json',
        'app_id' => $app_id,
        'app_key' => $app_key,
        'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
      ],
    ];

    $response = wp_remote_post( $url, $args );

    if ( is_wp_error( $response ) ) {
      $this->throw_error_redirect( 'access_denied', __( 'API return error.', 'wpabcf' ) );
    } else {
      return json_decode( $response['body'], true )['oauthMemberId'];
    }
  }

  private function try_get_token( $from_link )
  {
    $client_id = get_option( 'abcf_client_id', false );
    $client_secret = get_option( 'abcf_client_secret', false );
    $app_id = get_option( 'abcf_id', false );
    $app_key = get_option( 'abcf_key', false );

    if( !$client_id || !$client_secret || !$app_id || !$app_key ){
      $this->throw_error_redirect( 'no_keys', __( 'No client keys added.', 'wpabcf' ) );
    }

    $url = 'https://api.abcfinancial.com/uaa/oauth/token';

    $url = add_query_arg( [
      'grant_type' => 'authorization_code',
      'code' => $_REQUEST['code'],
      'redirect_uri' => urlencode( add_query_arg( $from_link, admin_url( 'admin-ajax.php' ) ) ),
    ], $url );

    $args = [
      'timeout'     => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking'    => true,
      'headers' => [
        'Accept' => 'application/json',
        'app_id' => $app_id,
        'app_key' => $app_key,
        'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
      ],
    ];

    $response = wp_remote_post( $url, $args );

    if ( is_wp_error( $response ) ) {
      $this->throw_error_redirect( 'access_denied', __( 'API return error.', 'wpabcf' ) );
    } else {

      return json_decode( $response['body'], true );
    }
  }

  private function success_user_redirect( $redirect, $user_id, $tokens )
  {
    $data = [
      'user_id' => $user_id,
      'tokens' => $tokens
    ];

    set_transient( 'oauth_user', $data, 6000);
    wp_redirect( add_query_arg( $redirect, get_option( 'siteurl', false ) ), 303 );
    exit;
  }

  private function throw_error_redirect( $code, $message )
  {
    set_transient( 'oauth_error', $message, 120);
    wp_redirect( add_query_arg( [ 'error' => $code ], get_option( 'siteurl', false ) ), 303 );
    exit;
  }

  public function getLastError()
  {
    $error = get_transient( 'oauth_error' );
    if ( $error ) {
      delete_transient( 'oauth_error' );
      wp_send_json_success( [ 'data' => $error ], 200 );
    }
    
    wp_send_json_error( [ 'code' => 100, 'message' => 'No data was found.' ], 404 );
  }

  public function getSelect()
  {
    $date = time();
    $eventsName = [];
    $eventsEmployee = [];

    if ( $events = search_events_by_range( [ date('Y-m-d', $date ), date('Y-m-d', strtotime( '+30 day', $date ) ) ] ) ) {
      foreach( $events as $event ) {
        $eventsName[$event['eventName']] = $event['eventName'];

        if( $event['employer'] ){
          $eventsEmployee[$event['employeeId']] = $event['employer']['personal']['firstName'] . ' ' . $event['employer']['personal']['lastName'];
        }
      }

      $response = [
        'eventsName' => array_values( $eventsName ),
        'eventsEmployee' => $eventsEmployee
      ];

      wp_send_json_success( [ 'data' => $response ], 200 );
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No data was found.' ], 404 );
  }

  public function searchUSer()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) ) {
      if ( $users = search_member( 'personal/memberMisc1', $_REQUEST['input'] ) ) {
        wp_send_json_success( [ 'data' => $users ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No user was found.' ], 404 );
  }

  public function searchUserById()
  {
    if ( isset( $_REQUEST['input'] ) && !empty( $_REQUEST['input'] ) ) {
      if ( $users = search_member( 'memberId', $_REQUEST['input'] ) ) {
        wp_send_json_success( [ 'data' => $users ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 100, 'message' => 'No user was found.' ], 404 );
  }

  public function getAttendanceEvents()
  {
    if ( $events = search_events_by_range( [ date('Y-m-d'), date('Y-m-d', strtotime( '+1 day', time() ) ) ] ) ) {

      if ( $events ) {
        $events = $this->prepare_response( $events );
        wp_send_json_success( [ 'data' => $events ], 200 );
      }
    }

    wp_send_json_error( [ 'code' => 200, 'message' => 'No events was found.' ], 404 );
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
                  'employee' => $event['employer']['personal'],
                  'location' => $location_name,
                  'duration' => $event['duration'],
                  'attendedStatus' => $member['attendedStatus']
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
    if ( isset( $_REQUEST['eventId'] ) && !empty( $_REQUEST['eventId'] ) && isset( $_REQUEST['memberId'] ) ) {
      $event_id = sanitize_text_field( $_REQUEST['eventId'] );
      $member_id = sanitize_text_field( $_REQUEST['memberId'] );
      if ( $result = subscribeUser( $event_id, $member_id ) ) {
        wp_send_json_success( [ 'data' => $result ], 200 );
      } else {
        wp_send_json_error( [ 'code' => 301, 'message' => 'Api return error.' ], 405 );
      }
    }

    wp_send_json_error( [ 'code' => 300, 'message' => 'Some error happens.' ], 409 );
  }

  public function sendMemberNotification()
  {
    if ( empty( $_POST['email'] ) || empty( $_POST['eventId'] ) || empty( $_POST['memberId'] ) ) {
      wp_send_json_error( [ 'code' => 300, 'message' => 'Some error happens.' ], 409 );
    }

    $target_email = sanitize_email( $_POST['email'] );

    $subject = __( 'New Reminder', 'wpabcf' );
    $headers = [ 'content-type: text/html' ];
    $attachments = false;

    wpabcf()->email->init();

    $html = get_template_html( P_PATH . 'templates/emails/notification-email.php', [ 'subject' => $subject ] );

    var_dump( $target_email, $subject, $html, $headers, $attachments );die();

    $result = wp_mail( $target_email, $subject, $html, $headers, $attachments );

    wp_send_json_success( [ 'data' => $result ], 200 );
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
        'employee' => $location['employer']['personal'],
        'location' => $location_name,
        'duration' => $location['duration'],
        'memberCount' => count( $location['members'] )
      ];

      if ( $location['members'] ) {
        foreach( $location['members'] as $member ){
          $response[ $timestamp ]['events'][$key]['members'][] = [
            'memberId' => $member['memberId'],
            'memberData' => search_member( 'memberId', $member['memberId'] ),
            'attendedStatus' => ( $member['attendedStatus'] == 'Attended' ? true : false ),
          ];
        }
      } else {
        $response[ $timestamp ]['events'][$key]['members'] = false;
      }
    }

    return $response;
  }

}
