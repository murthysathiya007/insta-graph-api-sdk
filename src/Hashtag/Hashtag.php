<?php

namespace Hashtag;

use Instagram;
use Request\Params;
use Request\Fields;

/**
 * Hashtag.
 *
 * Get the info for a hashtag.
 *     - Endpoint Format: GET /{ig-hashtag-id}?access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-hashtag
 * 
 */
class Hashtag extends Instagram {
    /**
     * @var integer $userId Instagram user id making the api request.
     */
    protected $hashtagId;

    /**
     * @var array $fields a list of all the fields we are requesting to get back.
     */
    protected $fields = array(
        Fields::ID,
        Fields::NAME
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

        // store the user id
        $this->hashtagId = $config['hashtag_id'];
    }

    /**
     * Get info on a hashtag.
     *
     * @param array $params params for the GET request.
     * @return Instagram response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->hashtagId,
            'params' => $params ? $params : Params::getFieldsParam( $this->fields )
        );

        // ig get request
        $response = $this->get( $getParams );

        // return response
        return $response;
    }
}

?>