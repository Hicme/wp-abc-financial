<?php

namespace admin;

class Admin_Startup
{

  private $settings_class = null;

  public function __construct()
  {
    add_action( 'init', [ $this, 'admin_inits' ] );

    add_action( 'admin_head', [ $this, 'admin_menus_reorder' ] );
    add_action( 'admin_menu', [ $this, 'admin_menus' ], 9 );

    add_action('admin_bar_menu', [ $this, 'clear_cache' ], 100);

    add_action( 'init', [ $this, 'detect_clear_cache' ] );
  }

  public function admin_inits()
  {
    $this->init_settings_pages();
    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
  }

  private function init_settings_pages()
  {
    $this->settings_class = new \admin\Settings_Pages();
  }

  public function enqueue_assets()
  {
      wp_enqueue_style( 'pstyles-admin', P_URL_FOLDER . 'assets/css/admin_styles.css', [], P_VERSION, 'screen' );
      wp_enqueue_script( 'pscripts-admin', P_URL_FOLDER . 'assets/js/admin_js.js', [], P_VERSION, true );
      wp_localize_script('pscripts-admin', 'WPABCFAJAX',
      [
        'url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('wpabcf-admin-ajax-nonce'),
        ]
    );
  }

  public function admin_menus_reorder()
  {
    global $submenu;

    if( isset( $submenu['wpabcf'] ) ){
      unset( $submenu['wpabcf'][0] );

      // $post_types = $submenu['wpabcf'][3];
      // unset( $submenu['wpabcf'][3] );
      // array_unshift( $submenu['wpabcf'], $post_types );
    }
  }

  public function admin_menus()
  {
    add_menu_page( __( 'abcFinancial', 'wpabcf' ), __( 'abcFinancial', 'wpabcf' ), 'manage_wpabcf', 'wpabcf', null, 'dashicons-book-alt', '45' );
    add_submenu_page( 'wpabcf', __( 'Settings', 'wpabcf' ), __( 'Settings', 'wpabcf' ), 'manage_wpabcf', 'wpabcf_settings', [ $this->settings_class, 'render_content' ] );
  }

  public function clear_cache( $admin_bar )
  {
    $admin_bar->add_menu( array(
      'id'    => 'abc_f',
      'title' => 'ABC Finansical',
      'href'  => '/wp-admin/admin.php?page=wpabcf_settings',
      'meta'  => array(
        'title' => __( 'ABC Finansical', 'wpabcf' ),
      ),
    ));

    $admin_bar->add_menu( array(
      'id'    => 'abc_cache',
      'parent' => 'abc_f',
      'title' => 'Clear Cache',
      'href'  => add_query_arg( ['abc_nonce' => wp_create_nonce( 'abcf_clear_cahce' ), 'abcf-clear-cahce' => 1], $_SERVER['REQUEST_URI'] ),
      'meta'  => array(
          'title' => __( 'Clear Cache',' wpabcf' ),
          'class' => 'abcf_clear_cache'
      ),
    ));
  }

  public function detect_clear_cache()
  {
    if ( isset( $_GET['abcf-clear-cahce'] ) ) {
      if ( wp_verify_nonce( $_GET['abc_nonce'], 'abcf_clear_cahce' ) ) {
        wpabcf()->cache->delete_all();
      }
    }
  }
}
