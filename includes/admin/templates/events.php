<h2>View all Calendars events</h2>
<form method="POST" class="form_settings">
    <?php

      render_input( [
        'id'          => 'is_event_search',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => 'is_event_search',
        'value'       => true,
      ] );

      render_input( [
        'id'          => 'event_search_nonce',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => 'event_search_nonce',
        'value'       => wp_create_nonce('user_search_nonce_validation'),
      ] );

      render_input( [
        'id'          => 'search_event',
        'label'       => 'Date Range',
        'name'        => 'search_event',
        'value'       => ( isset( $_REQUEST['search_event'] ) ? $_REQUEST['search_event'] : '' ),
        'description' => 'Please specify date range for search.',
        'attributes'  => [ 'readonly' => true ]
      ] );

      submit_button(__('Search', 'wpabcf'));
    ?>
</form>

<div class="search_result">
  <?php do_action('search_result_for_events'); ?>
</div>