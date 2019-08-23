<?php

namespace system;

class Email
{
  use \system\Instance;

  public $subject;

  public $header;

  public $footer;

  public $content;

  public $middle;

  public $template_html;

  public $recipient = [];

  public function add_recepient( $recipient )
  {
    $this->recipient[] = sanitize_email( $recipient );
  }

  public function get_recipient()
  {
    return implode( ', ', apply_filters( 'p_email_recipient', $this->recipient ) );
  }
  
  public function set_subject( $data )
  {
    $this->subject = $data;
  }

  public function get_subject()
  {
    return $this->subject;
  }

  public function set_header( $content )
  {
    $this->header = $content;
  }

  public function get_header()
  {
    if ( !empty( $this->header ) ) {
      return $this->header;
    } else {
      return $this->get_default_header();
    }
  }

  public function get_default_header()
  {
    ob_start();
    if( get_option( 'abcf_logo', false ) ){
      ?>
      <p style="margin-bottom: 0; margin-top: 0;">
        <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, ['style' => 'border: none; display: inline; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; font-size: 14px; line-height: 24px; width: 100%; max-width: 300px;' ] ); ?>
      </p>
      <?php
    }

    return ob_get_clean();
  }

  public function set_content( $content )
  {
    $this->content = $content;
  }

  public function get_content()
  {
    return $this->content;
  }

  public function set_middle( $content )
  {
    $this->middle = $content;
  }

  public function get_middle()
  {
    return $this->middle;
  }

  public function get_footer()
  {
    if ( !empty( $this->footer ) ) {
      return $this->footer;
    } else {
      return $this->get_default_footer();
    }
  }

  public function get_default_footer()
  {
    ob_start();
    ?>
      <p>© <?php echo date('Y') . ' ' . get_option( 'blogname', '' ); ?> All Rights Reserved.</p>
    <?php
    return ob_get_clean();
  }

  public function get_html()
  {
    return get_template_html( $this->get_template(), [
      'subject' => $this->get_subject(),
      'header'  => $this->get_header(),
      'content' => $this->get_content(),
      'middle'  => $this->get_middle(),
      'footer'  => $this->get_footer(),
    ] );
  }

  public function get_template()
  {
    if ( !empty( $this->template_html ) ) {
      return $this->template_html;
    } else {
      return $this->get_default_template_html();
    }
  }

  public function get_default_template_html()
  {
    return apply_filters( 'p_default_email_template_html', P_PATH . 'templates/emails/notification-email.php' );
  }

  public function get_headers()
  {
    return [ 
      'content-type: text/html',
      'From: '. get_option( 'blogname', '' ) .' <'. get_option( 'admin_email', '' ) .'>'
    ];
  }

  public function get_attachments()
  {
    return false;
  }

  public function send() {
    if ( $this->get_recipient() ) {
      return wp_mail( $this->get_recipient(), $this->get_subject(), $this->get_html(), $this->get_headers(), $this->get_attachments() );
    }
  }
}
