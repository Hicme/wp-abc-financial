<?php

namespace admin\pages;

class Events
{

  public static function search_event()
  {
    if( isset( $_REQUEST['is_event_search'] ) ){
      if( wp_verify_nonce( $_REQUEST['event_search_nonce'], 'user_search_nonce_validation') ){
        $range = explode( ' - ', str_replace( '/', '-', $_REQUEST['search_event'] ) );
        dump( search_events_by_range( $range ) );
      } else {
        echo sprintf( '<h3>%s</h3>', __('Please, update page and try again.', 'wpabcf') );
      }
    }
  }

  public static function add_assets()
  {
    wp_enqueue_script( 'moment', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', ['jquery'] );
    wp_enqueue_script( 'rangedatepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', ['jquery', 'moment'] );
    wp_enqueue_style( 'daterangepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css', false, null );
  }

  public static function init_datepicker()
  {
    ?>
      <script type="text/javascript">
      jQuery(document).ready(function($){
        $('input[name="search_event"]').daterangepicker({
            opens: 'right',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="search_event"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        });

        $('input[name="search_event"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
      });

      </script>
		<?php
  }

  public static function render_content()
  {
    add_action( 'search_result_for_events', [ __CLASS__, 'search_event' ]);
    add_action( 'admin_enqueue_scripts', [ __CLASS__, 'add_assets' ] );
    add_action( 'admin_footer', [ __CLASS__, 'init_datepicker' ], 99 );
    add_action( 'wpadbcf_settings_tab_content', [ __CLASS__, 'get_template' ]);
  }

  public static function get_template()
  {
    include P_PATH . 'includes/admin/templates/events.php';
  }
}
