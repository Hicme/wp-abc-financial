<?php

namespace admin\pages;

class Users
{

  public static function search_user()
  {
    if( isset( $_REQUEST['is_user_search'] ) ){
      if( wp_verify_nonce( $_REQUEST['user_search_nonce'], 'user_search_nonce_validation') ){
        dump( search_member( 'memberMisc1', $_REQUEST['search_user'], true ) );
      } else {
        echo sprintf( '<h3>%s</h3>', __('Please, update page and try again.', 'wpabcf') );
      }
    }
  }

  public static function render_content()
  {
    add_action('search_result_for_user', [ __CLASS__, 'search_user' ]);
    add_action('wpadbcf_settings_tab_content', [ __CLASS__, 'get_template' ]);
  }

  public static function get_template()
  {
    include P_PATH . 'includes/admin/templates/users.php';
  }
}
