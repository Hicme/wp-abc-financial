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

function search_member( $key, $search )
{
  if( $user_data = wpabcf()->methods->get_members() ) {

    $t_search = trim( $search );

    if( is_array( $user_data ) && isset( $user_data['members'] ) ){
      foreach( $user_data['members'] as $member ){
        switch( $key ) {
          case 'all':
            if( (
              isset( $member['personal']['email'] ) && $member['personal']['email'] == $t_search )
              || ( isset( $member['personal']['primaryPhone'] ) && $member['personal']['primaryPhone'] == $t_search )
            ){
              return $member;
            }
          break;
          
          case 'email':
            if( $member['personal']['email'] == $t_search ){
              return $member;
            }
          break;
    
          case 'phone':
            if( $member['personal']['primaryPhone'] == $t_search ){
              return $member;
            }
          break;
        }
      }

      return $user_data['members'];

      }else{
        return false;
      }
  }else{
    return false;
  }
}

function search_events_by_range( array $arg )
{

  $date1 = strtotime( $arg[0] );
  $date2 = strtotime( $arg[1] );

  if ( $date1 > $date2 || $date1 === $date2 ) {
    $valid = $arg[0];
  } else {
    $valid = $arg;
  }

  if ( $events = wpabcf()->methods->get_events( $valid ) ) {
    return $events;
  } else {
    return false;
  }
}
