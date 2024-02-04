<?php

namespace User;

use Instagram;
use Request\Params;
use Request\Fields;

/**
 * Live Media
 *
 * Get live media on the IG user.
 *     - Endpoint Format: GET /{ig-user-id}/live_media?fields={fields}&since={since}&until={until}&access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-user/live_media
 * 
 */
class LiveMedia extends User {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'live_media';

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
     * Get live media for a user.
     *
     * @param array $params params for the GET request.
     * @return Instagram response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->userId . '/' . self::ENDPOINT,
            'params' => $params ? $params : Params::getFieldsParam( Fields::getDefaultMediaFields( false ) )
        );

        // ig get request
        $response = $this->get( $getParams );

        // return response
        return $response;
    }
}

?>