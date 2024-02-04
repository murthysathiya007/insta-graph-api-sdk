<?php

namespace Media;

use Instagram;
use Request\Params;
use Request\Fields;

/**
 * Children
 *
 * Get children of a specific media.
 *     - Endpoint Format: GET /{ig-media-id}/children?access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-media/children
 * 
 */
class Children extends Media {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'children';

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
     * Get children on a media post.
     *
     * @param array $params params for the GET request.
     * @return Instagram response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->mediaId . '/' . self::ENDPOINT,
            'params' => $params ? $params : Params::getFieldsParam( Fields::getDefaultMediaChildrenFields( false ) )
        );

        // ig get request
        $response = $this->get( $getParams );

        // return response
        return $response;
    }
}

?>