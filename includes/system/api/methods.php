<?php

namespace system\api;

class Methods extends Request{

    use \system\Instance;
    use \system\api\Members;
    use \system\api\Calendar;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Send custom api request
     *
     * @param string $type
     * @return array
     * @since 1.0.0
     */
    public function custom_request( $type )
    {
        $this->set_request_type( $type );

        return $this->get_responce();
    }

}