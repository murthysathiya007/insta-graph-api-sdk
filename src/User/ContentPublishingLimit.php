<?php

namespace User;


use Instagram;
use Request\Params;
use Request\Fields;

/**
 * Content Publishing Limit
 *
 * Get the IG Users info.
 *     - Endpoint Format: GET /{ig-user-id}?fields={fields}&access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-user
 * 
 */
class ContentPublishingLimit extends User {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'content_publishing_limit';

    /**
     * @var array $fields a list of all the fields we are requesting to get back.
     */
    protected $fields = array(
        Fields::CONFIG,
        Fields::QUOTA_USAGE
    );

    /**
     * Contructor for instantiating a new object.
     *
     * @param array $config for the class.
     * @return void
     */
    public function __construct( $config ) {
        // call parent for setup
        parent::__construct( $config );
    }

    /**
     * Get info on the users content publishing limits.
     *
     * @param array $params params for the GET request.
     * @return Instagram response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->userId . '/' . self::ENDPOINT,
            'params' => $this->getParams( $params )
        );

        // ig get request
        $response = $this->get( $getParams );

        // return response
        return $response;
    }

    /**
     * Get params for the request.
     *
     * @param array $params specific params for the request.
     * @return array of params for the request.
     */
    public function getParams( $params = array() ) {
        if ( $params ) { // specific params have been requested
            return $params;
        } else { // get all params
            // get field params
            $params = Params::getFieldsParam( $this->fields );

            // add on the since query param farthest back it can be set is 24 hours
            $params[Params::SINCE] = ( new \DateTime())->modify( '-23 hours' )->getTimestamp();

            // return our params
            return $params;
        }
    }
}

?>