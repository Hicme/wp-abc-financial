<h2>Nuxtjs Control Panel</h2>
<form method="POST" class="form_settings">
    <?php
      render_input( [
        'id'          => '_action',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => '_action',
        'value'       => 'console_commands',
      ] );

      render_input( [
        'id'          => '_nonce',
        'type'        => 'hidden',
        'label'       => '',
        'name'        => '_nonce',
        'value'       => wp_create_nonce('console_nonce'),
      ] );

      if( $responce = self::check_npm() ){
        ?>
        <div class="version_console">
          NPM V: <?php echo $responce; ?>
        </div>
        <div class="console_button_wrapper">
          <button type="submit" name="console_action" value="generate-build" class="button button-secondary"><?php _e( 'Generate Build', 'wpabcf' ); ?></button>
          <button type="submit" name="console_action" value="install" class="button button-secondary"><?php _e( 'Install Modules', 'wpabcf' ); ?></button>
        </div>
        <?php
      }
    ?>
    
</form>

<?php do_action('console_result'); ?>