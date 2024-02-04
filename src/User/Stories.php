<?php

namespace User;

use Instagram;

/**
 * Stories
 *
 * Get stories on the IG user.
 *     - Endpoint Format: GET /{ig-user-id}/stories?access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-user/stories
 * 
 */
class Stories extends User {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'stories';

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
     * Get the users stories.
     *
     * @param array $params params for the GET request.
     * @return Instagram Response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->userId . '/' . self::ENDPOINT
        );

        // ig get request
        $response = $this->get( $getParams );

        // return response
        return $response;
    }
}

?>