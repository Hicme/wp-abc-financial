<?php

namespace system\api;

class Request{

    protected $debug = false;

    /**
     * What we want from API
     *
     * @var string
     * @since 1.0.0
     */
    protected $request_type = null;

    protected $club_number = null;

    /**
     * Temp save of current curl
     *
     * @var object
     * @since 1.0.0
     */
    protected static $curl = null;

    protected static $method = 'GET';

    protected static $allow_methods = [ "GET", "POST", "PUT", "DELETE", "PATCH" ];

    protected $headers = [];

    protected $post_fields = [];

    /**
     * Class constructor
     */
    public function __construct()
    {
      $this->set_debug();
      add_action( 'shutdown', [ $this , 'shutdown_curl' ] );
    }

    /**
     * Set debug mode on or off
     *
     * @return boolean
     * @since 1.1.0
     */
    private function set_debug()
    {
      if( get_option( 'tempo_debug', false ) ){
        $this->debug = wpabcf()->logger( 'REQUEST' );
      }
    }

    /**
     * Set request type
     *
     * @param string $type
     * @return void
     * @since 1.0.0
     */
    public function set_request_type( $type )
    {
      $this->request_type = esc_attr( $type );
    }

    /**
     * Return current request type
     *
     * @return string
     * @since 1.1.0
     */
    public function get_request_type()
    {
      return ( !empty( $this->request_type ) ? $this->club_number . '/' . $this->request_type : false );
    }

    public function set_method( $method )
    {
      if( in_array( $method, self::$allow_methods ) ){
        self::$method = $method;
      }
    }

    public function get_method()
    {
      return self::$method;
    }

    public function clean_headers()
    {
      $this->headers = [];
    }

    public function append_headers( $arg = [] )
    {
      foreach( $arg as $key => $val ){
        $this->headers[] = "{$key}: {$val}";
      }
    }

    public function get_headers()
    {
      return $this->headers;
    }

    private function set_default_headers()
    {
      $this->append_headers([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Accept-Charset' => 'UTF-8',
      ]);
    }

    /**
     * Set API and Syndicate keys from db to temp variable
     *
     * @return boolean
     * @since 1.0.0
     */
    private function set_keys()
    {
      $app_id = $app_key = $club_number = false;

      if( $option = get_option( 'abcf_id', null ) ){
        $this->append_headers([
          'app_id' => $option,
        ]);

        $app_id = true;
      }

      if( $option = get_option( 'abcf_key', null ) ){
        $this->append_headers([
          'app_key' => $option,
        ]);

        $app_key = true;
      }

      if( $option = get_option( 'abcf_cNumber', null ) ){
        $this->append_headers([
          'clubNumber' => $option,
        ]);

        $this->club_number = $option;

        $club_number = true;
      }

      return ( $app_id && $app_key && $club_number );
    }

    /**
     * Check if curl enabled on serwer
     *
     * @return boolean
     * @since 1.0.0
     */
    private static function is_curl()
    {
      return function_exists( 'curl_version' );
    }

    /**
     * Set new curl
     *
     * @return boolean
     * @since 1.0.0
     */
    private function set_curl()
    {
      if( self::is_curl() ){
        if( is_null( self::$curl ) ){
          self::$curl = curl_init();
        }

        return true;

      }else{
        return false;
      }
    }

    /**
     * Return curl class
     *
     * @return object
     * @since 1.1.0
     */
    private function get_curl()
    {
      if( is_null( self::$curl ) ){
        $this->set_curl();
      }

      return self::$curl;
    }

    /**
     * Close current curl
     *
     * @return void
     * @since 1.0.0
     */
    public function shutdown_curl()
    {
      if( ! is_null( $this->get_curl() ) ){
        curl_close( $this->get_curl() );
      }
    }
    
    /**
     * Trigger request and return response
     *
     * @return mixed
     * @since 1.0.0
     */
    public function get_responce()
    {
      $this->set_default_headers();

      if( $this->set_curl() && $this->set_keys() ){
        return $this->exec();
      }

        return false;

    }

    /**
     * Get readble response from api
     *
     * @return array
     * @since 1.0.0
     */
    private function exec()
    {
        $this->request();

        if( curl_error( $this->get_curl() ) ){
            return false;
        }else{

            $responce = curl_exec( $this->get_curl() );
            $httpcode = curl_getinfo( $this->get_curl(), CURLINFO_HTTP_CODE );

            if( $this->debug ){
              $this->debug->debug( 'Response: ' . $this->get_request_type(), [ $responce ] );
            }

            if( ! empty( ( $responce ) ) && $httpcode != 401 && $httpcode != 403 ){

              $decoded_response = json_decode( $responce, true );

              return $decoded_response;
                
            }else{
                return false;
            }
        }

    }

    /**
     * Send request
     *
     * @return void
     * @since 1.0.0
     * @see https://abcfinancial.3scale.net/docs/
     */
    private function request()
    {
        curl_setopt_array(
            $this->get_curl(), 
            [
                CURLOPT_URL => 'https://api.abcfinancial.com/rest/' . $this->get_request_type(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $this->get_method(),
                CURLOPT_HTTPHEADER => $this->get_headers(),
            ]
        );
    }
}
