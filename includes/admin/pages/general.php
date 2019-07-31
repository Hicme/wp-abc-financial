<?php

namespace admin\pages;

class General
{
  public static function sanitize_return( $value )
  {
    return esc_attr( $value );
  }

  public static function sanitize_checkbox( $value )
  {
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
    register_setting( 'p-settings', 'abcf_id', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_key', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_cNumber', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_logo', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_screensaver_logo', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_background', [ __CLASS__, 'sanitize_return' ] );
    register_setting( 'p-settings', 'abcf_debug', [ __CLASS__, 'sanitize_checkbox' ] );

    add_settings_section(
      'id_p_general',
      'General Settings',
      [ __CLASS__, 'settings_html' ],
      'p_general_settings'
    );

    add_settings_field(
      'id_title',
      'Location Title',
      [ __CLASS__, 'id_title_html' ],
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
      ?>
      <div class="image_wrapper">
        <?php
          render_input( [
            'id'          => 'id_logo',
            'type'        => 'hidden',
            'label'       => '',
            'name'        => 'abcf_logo',
            'value'       => get_option( 'abcf_logo', '' ),
          ] );
        ?>
        <img src="<?php echo P_URL_FOLDER . '/assets/images/image-placeholder.jpg' ?>" alt="logo" width="150px" height="150px" />
      </div>
      <?php
  }

  public static function id_sreensaver_logo_html()
  {
      ?>
      <div class="image_wrapper">
        <?php
          render_input( [
            'id'          => 'id_sreensaver_logo',
            'type'        => 'hidden',
            'label'       => '',
            'name'        => 'abcf_screensaver_logo',
            'value'       => get_option( 'abcf_screensaver_logo', '' ),
          ] );
        ?>
        <img src="<?php echo P_URL_FOLDER . '/assets/images/image-placeholder.jpg' ?>" alt="logo" width="150px" height="150px" />
      </div>
      <?php
  }

  public static function id_background_html()
  {
      ?>
      <div class="image_wrapper">
        <?php
          render_input( [
            'id'          => 'id_background',
            'type'        => 'hidden',
            'label'       => '',
            'name'        => 'abcf_background',
            'value'       => get_option( 'abcf_background', '' ),
          ] );
        ?>
        <img src="<?php echo P_URL_FOLDER . '/assets/images/image-placeholder.jpg' ?>" alt="logo" width="150px" height="150px" />
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
          var custom_uploader;
          $('#upload_image_button').click(function(e) {
              e.preventDefault();
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
                  attachment = custom_uploader.state().get('selection').first().toJSON();
                  $('#upload_image').val(attachment.url);
              });
              //Open the uploader dialog
              custom_uploader.open();
          });
      });
      </script>
		<?php
  }
}
