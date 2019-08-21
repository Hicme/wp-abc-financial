<?php

namespace system;

class Email
{
  use \system\Instance;

  public function init()
  {
    add_action( 'notification_header', [ $this, 'notification_header' ] );
    add_action( 'notification_content', [ $this, 'notification_content' ] );
    // add_action( 'notification_middle', [ $this, '' ] );
    add_action( 'notification_footer', [ $this, 'notification_footer' ] );
  }

  public function notification_header()
  {
    if( get_option( 'abcf_logo', false ) ){
      ?>
      <p style="margin-bottom: 0; margin-top: 0;">
        <?php echo wp_get_attachment_image( get_option( 'abcf_logo', false ), 'medium', false, ['style' => 'border: none; display: inline; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; font-size: 14px; line-height: 24px; width: 100%; max-width: 300px;' ] ); ?>
      </p>
      <?php
    }
  }

  public function notification_content()
  {
    $member_id = sanitize_text_field( $_POST['memberId'] );
    $event_id = sanitize_text_field( $_POST['eventId'] );
    $member_name = ( search_member( 'memberId', $member_id ) ? search_member( 'memberId', $member_id )['personal']['firstName'] : '' );
    $class = get_event( $event_id );
    $timestamp = strtotime( $class['eventTimestamp'] );

    ?>
      <h2 style="text-align: center;">Hello <?php echo $member_name; ?>!</h2>
      <p style="text-align: center;">Here’s your reminder for <?php echo $class['eventName']; ?>.</p>
      <p style="text-align: center; font-family: monospace; font-size: 20px; font-weight: bold; background: #e8f3ff; padding: 15px;"><?php echo date('l m/d/Y', $timestamp ); ?> at <?php echo date('h:i a', $timestamp ); ?></p>
      <p style="text-align: center; font-size: 15px;">Download the <a href="https://apps.apple.com/us/app/6th-sense-fitness-newport/id1465085274" style="color: #39c; font-weight: bold;">6th Sense App</a></p>
    <?php
  }

  public function notification_footer()
  {
    echo '<p>© '. date('Y') .' '. get_option( 'abcf_title', '' ) .'. All Rights Reserved.</p>';
  }
}
