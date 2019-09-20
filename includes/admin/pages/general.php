<?php

namespace admin\pages;

class General
{
  public static function sanitize_return( $value )
  {
    wpabcf()->cache->delete( 'main_settings' );
    return esc_attr( $value );
  }

  public static function sanitize_checkbox( $value )
  {
    wpabcf()->cache->delete( 'main_settings' );
    if( is_null( $value ) ){
      return false;
    }else{
      return esc_attr( $value );
    }
  }

  public static function render_content()
  {
    add_action('admin_init', [ __CLASS__, 'register_options' ]);
    add_action('wpadbcf_settings_tab_content', [ __CLASS__, 'get_template' ]);
    add_action( 'admin_footer', [ __CLASS__, 'init_image_picker' ], 99 );
  }

  public static function register_options()
  {
    wp_enqueue_media();

    register_setting( 'p-settings', 'abcf_title', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_location', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_presearch', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_fornt_password', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_id', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_client_id', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_client_secret', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_authorization', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_key', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_cNumber', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_logo', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_screensaver_logo', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_background', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_video', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_debug', [ __CLASS__, 'sanitize_checkbox' ] );

    add_settings_section(
      'id_p_general',
      'General Settings',
      [ __CLASS__, 'settings_html' ],
      'p_general_settings'
    );

    add_settings_field(
      'id_title',
      'Site Title',
      [ __CLASS__, 'id_title_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_location',
      'Location',
      [ __CLASS__, 'id_location_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_preshearch',
      'Pre-populated search',
      [ __CLASS__, 'id_preshearch_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_password',
      'Admin front password',
      [ __CLASS__, 'id_password_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_client_id',
      'Client ID',
      [ __CLASS__, 'id_client_id_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_client_secret',
      'Client Secret',
      [ __CLASS__, 'id_client_secret_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_authorization',
      'Client Authorization',
      [ __CLASS__, 'id_authorization_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_key',
      'Application ID',
      [ __CLASS__, 'id_key_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'application_key',
      'Application Key',
      [ __CLASS__, 'application_key_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'cnumber_key',
      'Club Number',
      [ __CLASS__, 'cnumber_key_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_logo',
      'Site logo',
      [ __CLASS__, 'id_logo_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_sreensaver_logo',
      'Screen saver logo',
      [ __CLASS__, 'id_sreensaver_logo_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_background',
      'Site background image',
      [ __CLASS__, 'id_background_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_video',
      'Site screensaver video',
      [ __CLASS__, 'id_video_html' ],
      'p_general_settings',
      'id_p_general'
    );

    add_settings_field(
      'id_debug',
      'Debug',
      [ __CLASS__, 'id_debug_html' ],
      'p_general_settings',
      'id_p_general'
    );
  }

  public static function get_template()
  {
    include P_PATH . 'includes/admin/templates/general.php';
  }

  public static function settings_html()
  {
    echo '<p>Here you can set up API keys and others.</p>';
  }

  public static function id_title_html()
  {
    render_input( [
      'id'          => 'id_title',
      'label'       => '',
      'name'        => 'abcf_title',
      'value'       => get_option( 'abcf_title', '' ),
      'description' => 'This title will use on frontend.',
    ] );
  }

  public static function id_location_html()
  {
    render_input( [
      'id'          => 'id_location',
      'label'       => '',
      'name'        => 'abcf_location',
      'value'       => get_option( 'abcf_location', '' ),
      'description' => 'This location name will be used in Events.',
    ] );
  }

  public static function id_preshearch_html()
  {
    render_input( [
      'id'          => 'id_preshearch',
      'label'       => '',
      'name'        => 'abcf_presearch',
      'value'       => get_option( 'abcf_presearch', '' ),
      'description' => 'This will be used like pre-entered search in Schedule Search.',
    ] );
  }

  public static function id_password_html()
  {
    render_input( [
      'id'          => 'id_password',
      'label'       => '',
      'name'        => 'abcf_fornt_password',
      'value'       => get_option( 'abcf_fornt_password', '' ),
      'description' => 'This password will be used on frontend.',
    ] );
  }

  public static function id_key_html()
  {
    render_input( [
      'id'          => 'id_key',
      'label'       => '',
      'name'        => 'abcf_id',
      'value'       => get_option( 'abcf_id', '' ),
      'description' => 'The application ID for authenticating the request.',
    ] );
  }

  public static function application_key_html()
  {
    render_input( [
      'id'          => 'application_key',
      'label'       => '',
      'name'        => 'abcf_key',
      'value'       => get_option( 'abcf_key', '' ),
      'description' => 'The application key for authenticating the request.',
    ] );
  }

  public static function id_authorization_html()
  {
    render_input( [
      'id'          => 'abcf_authorization',
      'label'       => '',
      'name'        => 'abcf_authorization',
      'value'       => get_option( 'abcf_authorization', '' ),
    ] );
  }

  public static function id_client_id_html()
  {
    render_input( [
      'id'          => 'abcf_client_id',
      'label'       => '',
      'name'        => 'abcf_client_id',
      'value'       => get_option( 'abcf_client_id', '' ),
    ] );
  }

  public static function id_client_secret_html()
  {
    render_input( [
      'id'          => 'abcf_client_secret',
      'label'       => '',
      'name'        => 'abcf_client_secret',
      'value'       => get_option( 'abcf_client_secret', '' ),
    ] );
  }

  public static function cnumber_key_html()
  {
      render_input( [
          'id'          => 'cnumber_key',
          'label'       => '',
          'name'        => 'abcf_cNumber',
          'value'       => get_option( 'abcf_cNumber', '' ),
          'description' => 'The club number for the requested information.',
      ] );
  }

  public static function id_logo_html()
  {
    $val = get_option( 'abcf_logo', '' );
    $src = '';
    if( $val ){
      $image = wp_get_attachment_image_src($val, 'thumbnail');
      if( $image ){
        list($src, $width, $height) = $image;
      }
    }

      ?>
      <div class="image_wrapper <?php echo( $val ? 'has-value' : '' ); ?>">
        <?php
          render_input( [
            'id'          => 'id_logo',
            'type'        => 'hidden',
            'label'       => '',
            'name'        => 'abcf_logo',
            'value'       => $val,
            'attributes'  => [ 'data-name' => 'settings' ]
          ] );
        ?>
        <div class="hide-if-value">
          <p>
            <?php _e( 'No image', 'wpabcf' ); ?>
          </p>
          <p>
            <a data-name="add" class="acf-button button" href="#">
              <?php _e( 'Add image', 'wpabcf' ); ?>
            </a>
          </p>
        </div>
        <div class="show-if-value">
          <img data-name="image" src="<?php echo $src; ?>" alt="logo" width="150px" height="150px" />
          <p>
            <a data-name="remove" class="acf-button button" href="#"><?php _e( 'Remove image', 'wpabcf' ); ?></a>
          </p>
        </div>
      </div>
      <?php
  }

  public static function id_sreensaver_logo_html()
  {
    $val = get_option( 'abcf_screensaver_logo', '' );
    $src = '';
    if( $val ){
      $image = wp_get_attachment_image_src($val, 'thumbnail');
      if( $image ){
        list($src, $width, $height) = $image;
      }
    }

      ?>
      <div class="image_wrapper <?php echo( $val ? 'has-value' : '' ); ?>">
        <?php
          render_input( [
            'id'          => 'id_sreensaver_logo',
            'type'        => 'hidden',
            'label'       => '',
            'name'        => 'abcf_screensaver_logo',
            'value'       => $val,
            'attributes'  => [ 'data-name' => 'settings' ]
          ] );
        ?>
        <div class="hide-if-value">
          <p>
            <?php _e( 'No image', 'wpabcf' ); ?>
          </p>
          <p>
            <a data-name="add" class="acf-button button" href="#">
              <?php _e( 'Add image', 'wpabcf' ); ?>
            </a>
          </p>
        </div>
        <div class="show-if-value">
          <img data-name="image" src="<?php echo $src; ?>" alt="logo" width="150px" height="150px" />
          <p>
            <a data-name="remove" class="acf-button button" href="#"><?php _e( 'Remove image', 'wpabcf' ); ?></a>
          </p>
        </div>
      </div>
      <?php
  }

  public static function id_background_html()
  {
    $val = get_option( 'abcf_background', '' );
    $src = '';
    if( $val ){
      $image = wp_get_attachment_image_src($val, 'thumbnail');
      if( $image ){
        list($src, $width, $height) = $image;
      }
    }

    ?>
    <div class="image_wrapper <?php echo( $val ? 'has-value' : '' ); ?>">
      <?php
        render_input( [
          'id'          => 'id_video',
          'type'        => 'hidden',
          'label'       => '',
          'name'        => 'abcf_background',
          'value'       => $val,
          'attributes'  => [ 'data-name' => 'settings' ]
        ] );
      ?>
      <div class="hide-if-value">
        <p>
          <?php _e( 'No video', 'wpabcf' ); ?>
        </p>
        <p>
          <a data-name="add" class="acf-button button" href="#">
            <?php _e( 'Add image', 'wpabcf' ); ?>
          </a>
        </p>
      </div>
      <div class="show-if-value">
        <div class="video_wrapper">
          <img src="/wp-includes/images/media/video.png" alt="logo" />
        </div>
        <p>
          <a data-name="remove" class="acf-button button" href="#"><?php _e( 'Remove image', 'wpabcf' ); ?></a>
        </p>
      </div>
    </div>
    <?php
  }

  /*
  public static function id_background_html()
  {
    $val = get_option( 'abcf_background', '' );
    $src = '';
    if( $val ){
      $image = wp_get_attachment_image_src($val, 'thumbnail');
      if( $image ){
        list($src, $width, $height) = $image;
      }
    }

    ?>
    <div class="image_wrapper <?php echo( $val ? 'has-value' : '' ); ?>">
      <?php
        render_input( [
          'id'          => 'id_background',
          'type'        => 'hidden',
          'label'       => '',
          'name'        => 'abcf_background',
          'value'       => $val,
          'attributes'  => [ 'data-name' => 'settings' ]
        ] );
      ?>
      <div class="hide-if-value">
        <p>
          <?php _e( 'No image', 'wpabcf' ); ?>
        </p>
        <p>
          <a data-name="add" class="acf-button button" href="#">
            <?php _e( 'Add image', 'wpabcf' ); ?>
          </a>
        </p>
      </div>
      <div class="show-if-value">
        <img data-name="image" src="<?php echo $src; ?>" alt="logo" width="150px" height="150px" />
        <p>
          <a data-name="remove" class="acf-button button" href="#"><?php _e( 'Remove image', 'wpabcf' ); ?></a>
        </p>
      </div>
    </div>
    <?php
  }
  */

  public static function id_video_html()
  {
    $val = get_option( 'abcf_video', '' );
    $src = '';
    if( $val ){
      $image = wp_get_attachment_image_src($val, 'thumbnail');
      if( $image ){
        list($src, $width, $height) = $image;
      }
    }

    ?>
    <div class="image_wrapper <?php echo( $val ? 'has-value' : '' ); ?>">
      <?php
        render_input( [
          'id'          => 'id_video',
          'type'        => 'hidden',
          'label'       => '',
          'name'        => 'abcf_video',
          'value'       => $val,
          'attributes'  => [ 'data-name' => 'settings' ]
        ] );
      ?>
      <div class="hide-if-value">
        <p>
          <?php _e( 'No video', 'wpabcf' ); ?>
        </p>
        <p>
          <a data-name="add" class="acf-button button" href="#">
            <?php _e( 'Add image', 'wpabcf' ); ?>
          </a>
        </p>
      </div>
      <div class="show-if-value">
        <div class="video_wrapper">
          <img src="/wp-includes/images/media/video.png" alt="logo" />
        </div>
        <p>
          <a data-name="remove" class="acf-button button" href="#"><?php _e( 'Remove image', 'wpabcf' ); ?></a>
        </p>
      </div>
    </div>
    <?php
  }

  public static function id_debug_html()
  {
      render_input( [
          'id'          => 'id_debug',
          'label'       => '',
          'type'        => 'checkbox',
          'name'        => 'abcf_debug',
          'value'       => '1',
          'attributes'  => ( get_option( 'abcf_debug', false ) ? [ 'checked' => 'checked' ] : [] ) ,
          'description' => 'Enable debug mode?',
      ] );
  }

  public static function init_image_picker()
  {
    ?>
      <script type="text/javascript">
      jQuery(document).ready(function($){
          $('[data-name="add"]').click(function(e) {
              e.preventDefault();
              var custom_uploader;
              let ths = $(this);
              //If the uploader object has already been created, reopen the dialog
              if (custom_uploader) {
                  custom_uploader.open();
                  return;
              }
              //Extend the wp.media object
              custom_uploader = wp.media.frames.file_frame = wp.media({
                  title: 'Choose Image',
                  button: {
                      text: 'Choose Image'
                  },
                  multiple: false
              });
              //When a file is selected, grab the URL and set it as the text field's value
              custom_uploader.on('select', function() {
                let parent = ths.parents('.image_wrapper');
                  attachment = custom_uploader.state().get('selection').first().toJSON();
                  parent.addClass('has-value');
                  parent.find('[data-name="settings"]').val(attachment.id);
                  if(attachment.sizes){
                    parent.find('[data-name="image"]').attr('src', attachment.sizes.thumbnail.url);
                  }
              });
              //Open the uploader dialog
              custom_uploader.open();
          });
          $('[data-name="remove"]').click(function(e) {
            e.preventDefault();
            var parent = $(this).parents('.image_wrapper');
            parent.removeClass('has-value');
            parent.find('[data-name="settings"]').val('');
            parent.find('[data-name="image"]').attr('src', '');
          });
      });
      </script>
		<?php
  }
}
