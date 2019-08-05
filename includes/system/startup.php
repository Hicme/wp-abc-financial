<?php

namespace system;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

final class StartUp
{

  use \system\Instance;
  

  public function __get( $key )
  {
    if ( in_array( $key, array( 'parser', 'methods', 'logger', 'cache' ), true ) ) {
      return $this->$key();
    }
  }

  public function __construct()
  {
    $this->includes();

    do_action( 'p_loaded' );

    add_action( 'after_setup_theme', [ $this, 'load_carbon' ] );
    add_filter( 'allowed_http_origins', [ $this, 'add_allowed_origins' ] );
    add_action('init', [$this, 'add_cors_http_header']);
  }

  public function is_request( $type )
  {
    switch ( $type ) {
      case 'admin':
        return is_admin();
      case 'ajax':
        return defined( 'DOING_AJAX' );
      case 'cron':
        return defined( 'DOING_CRON' );
      case 'frontend':
        return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
    }
  }

  
  private function includes()
  {
    \system\Post_Types::init();
    \system\rest\Menu::instance();
    \system\rest\Settings::instance();
    new \system\Carbon();

    if( $this->is_request( 'cron' ) ){
        new \system\Cron();
    }

    if( $this->is_request( 'ajax' ) ){
        new \system\Ajax();
    }

    if( $this->is_request( 'admin' ) ){
        new \admin\Admin_Startup();
    }
  }

  public function load_carbon()
  {
    \Carbon_Fields\Carbon_Fields::boot();
  }

  public function add_allowed_origins( $origins )
  {
      $origins[] = 'http://localhost:3000';
      $origins[] = 'https://localhost:3000';
      return $origins;
  }

  public function add_cors_http_header()
  {
    header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Action, Authorization, multipart/form-data");
    header("Access-Control-Allow-Credentials: true");
  }



  public function parser()
  {
    return \system\parser\Processor::instance();
  }

  public function methods()
  {
    return \system\api\Methods::instance();
  }

  public function logger( $chanel )
  {
    $log_file = P_PATH . 'logs' . DIRECTORY_SEPARATOR . $chanel . '.log';
    $log = new Logger( $chanel );
    $log->pushHandler( new StreamHandler( $log_file, Logger::DEBUG ) );

    return $log;
  }

  public function cache()
  {
    return \system\Cache::instance();
  }
}
