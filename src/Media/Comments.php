<?php

namespace Media;

use Instagram;
use Request\Params;
use Request\Fields;

/**
 * Comments
 *
 * Get comments on a specific media.
 *     - Endpoint Format: GET /{ig-media-id}/comments?access_token={access-token}
 *     - Endpoint Format: POST /{ig-media-id}/comments?message={message}&access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-media/comments
 * 
 */
class Comments extends Media {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'comments';

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
     * Create a comment on a media post.
     *
     * @param string $message comment to post.
     * @return Instagram Response.
     */
    public function create( $message ) {
         $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->mediaId . '/' . self::ENDPOINT,
            'params' => array(
                Params::MESSAGE => $message
            )
        );

        // ig get request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }

    /**
     * Get comments on a media post.
     *
     * @param array $params params for the GET request.
     * @return Instagram response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->mediaId . '/' . self::ENDPOINT,
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
     * @param array $params Specific params for the request.
     * @return array of params for the request.
     */
    public function getParams( $params = array() ) {
        if ( $params ) { // specific params have been requested
            return $params;
        } else { // get all params
            // get field params
            $params[Params::FIELDS] = Fields::getDefaultCommentFields() . ',' .
                Fields::REPLIES . '{' .
                    Fields::getDefaultCommentFields() .
                '}'
            ;

            // return our params
            return $params;
        }
    }
}

?>