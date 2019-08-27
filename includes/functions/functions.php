<?php

function wpabcf()
{
  return \system\StartUp::instance();
}

if( ! function_exists( 'generate_string' ) ){
  function generate_string( $length = 4 )
  {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

if( ! function_exists( 'clean_styles' ) ){
  function clean_styles( $str )
  {
    if( empty( $str ) ){
      return '';
    }
    return preg_replace('/(style=".*?"|style=\'.*?\')/', '', $str );
  }
}

if( ! function_exists( 'render_input' ) ){
  function render_input( array $args )
  {
    if( empty( $args['id'] ) ){
        return;
    }

    $args['type'] = isset( $args['type'] ) ? $args['type'] : 'text';
    $args['name'] = isset( $args['name'] ) ? $args['name'] : $args['id'];
    $args['class'] = isset( $args['class'] ) ? $args['class'] : '';
    $args['value'] = isset( $args['value'] ) ? $args['value'] : '';
    $args['description'] = isset( $args['description'] ) ? $args['description'] : '';

    $attributes = [];

    if ( ! empty( $args['attributes'] ) && is_array( $args['attributes'] ) ) {

      foreach ( $args['attributes'] as $attribute => $value ) {
        $attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
      }
    }

    ?>
    <p class="input-field-wrapper input-<?php echo $args['id'] ?>">
      <label for="input_filed_<?php echo $args['id'] ?>">
        <?php echo wp_kses_post( $args['label'] ); ?>
      </label>

      <input type="<?php echo esc_attr( $args['type'] ); ?>" id="input_filed_<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" value="<?php echo esc_attr( $args['value'] ); ?>" <?php echo implode( ' ', $attributes ); ?> />

      <?php
        if( !empty( $args['description'] ) ){
          echo '<span class="description">'. wp_kses_post( $args['description'] ) .'</span>';
        }
      ?>
    </p>
    <?php
  }
}

function search_member( string $key, string $search, bool $return_all = false )
{
  if( $user_data = wpabcf()->methods->get_members() ) {

    $t_search = trim( $search );
    $p_key = explode( '/', $key );

    if( is_array( $user_data ) && isset( $user_data['members'] ) ){

      if ( $return_all ) {
        return $user_data['members'];
      }

      foreach( $user_data['members'] as $member ){
        if( count( $p_key ) > 1 ){
          if( isset( $member[$p_key[0]][$p_key[1]] ) && $member[$p_key[0]][$p_key[1]] == $t_search ){
            return $member;
          }
        }else{
          if( isset( $member[$p_key[0]] ) && $member[$p_key[0]] == $t_search ){
            return $member;
          }
        }
      }

    }
  }

  return false;
}

function search_events_by_range( $arg )
{

  if ( is_array( $arg ) ) {
    $date1 = strtotime( $arg[0] );
    $date2 = strtotime( $arg[1] );

    if ( $date1 > $date2 || $date1 === $date2 ) {
      $valid = $arg[0];
    } else {
      $valid = $arg;
    }
  } else {
    $valid = $arg;
  }

  if ( $events = wpabcf()->methods->get_events( $valid ) ) {
    foreach($events['events'] as $key => $event){
      if( !empty( $event['employeeId'] ) ){
        $events['events'][$key]['employer'] = getEmployer( $event['employeeId'] );
      }
    }

    return $events['events'];
  } else {
    return false;
  }
}

function get_event( $id )
{
  if( $event = wpabcf()->methods->get_event( $id ) ){
    return $event['events'][0];
  }

  return false;
}

function attendanceUser( $event_id, $member_id )
{
  if ( $response = wpabcf()->methods->attendance_event( $event_id, $member_id ) ) {
    return $response;
  } else {
    return false;
  }
}

function subscribeToEvent( $event_id, $member_id )
{
  if ( $response = wpabcf()->methods->subscribe_event( $event_id, $member_id ) ) {
    return $response;
  } else {
    return false;
  }
}

function getEmployer( $id )
{
  if ( $response = wpabcf()->methods->get_employees( $id ) ) {
    if( isset( $response['employees'] ) ){
      return array_shift( $response['employees'] );
    }
      return false;
  } else {
    return false;
  }
}

function get_template_html( $template_path, $args = array() )
{
    if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
    }
    
    if ( ! file_exists( $template_path ) ) {
        return false;
    }

    ob_start();
    include $template_path;
    return ob_get_clean();
}
