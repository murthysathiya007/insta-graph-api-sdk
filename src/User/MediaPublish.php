<?php


namespace User;

use Instagram;
use Request\Params;

/**
 * Media Publish
 *
 * Handle publishing media.
 *     - Endpoint Format: POST /{ig-user-id}/media_publish?creation_id={creation_id}&access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-user/media_publish
 * 
 */
class MediaPublish extends User {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'media_publish';

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
     * Publish media to users IG account.
     *
     * @param string $containerId id of the container for posting the media.
     * @return Instagram response.
     */
    public function create( $containerId ) {
        $postParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->userId . '/' . self::ENDPOINT,
            'params' => array(
                Params::CREATION_ID => $containerId
            )
        );

        // ig get request
        $response = $this->post( $postParams );

        // return response
        return $response;
    }
}

?>