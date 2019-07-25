<h2>Search user in abc Financial</h2>
<form method="POST" class="form_settings">
    <?php

      render_input( [
        'id'          => 'is_user_search',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => 'is_user_search',
        'value'       => true,
      ] );

      render_input( [
        'id'          => 'user_search_nonce',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => 'user_search_nonce',
        'value'       => wp_create_nonce('user_search_nonce_validation'),
      ] );

      render_input( [
        'id'          => 'search_user',
        'label'       => 'Email or phone number',
        'name'        => 'search_user',
        'value'       => ( isset( $_REQUEST['search_user'] ) ? $_REQUEST['search_user'] : '' ),
        'description' => 'Please specify user email or phone number.',
      ] );

      submit_button(__('Search', 'wpabcf'));
    ?>
</form>

<div class="search_result">
  <?php do_action('search_result_for_user'); ?>
</div>