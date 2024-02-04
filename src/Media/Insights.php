<?php


namespace Media;

use Instagram;
use Request\Params;
use Request\Fields;
use Request\Metric;

/**
 * Insights
 *
 * Get insights on a specific media.
 *     - Endpoint Format: GET /{ig-media-id}/insights?access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-media/insights
 * 
 */
class Insights extends Media {
    /**
     * @const Instagram endpoint for the request.
     */
    const ENDPOINT = 'insights';

    /**
     * @var Instagram endpoint for the request.
     */
    protected $mediaType;

    /**
     * @var array $metric a list of all the metrics we are requesting to get back on a certain media type.
     */
    protected $metrics = array(
        Metric::MEDIA_TYPE_VIDEO => array(
            Metric::IMPRESSIONS,
            Metric::REACH,
            Metric::VIDEO_VIEWS,
            Metric::SAVED,
            Metric::TOTAL_INTERACTIONS
        ),
        Metric::MEDIA_TYPE_STORY => array(
            Metric::IMPRESSIONS,
            Metric::REACH,
            Metric::REPLIES,
            Metric::LIKES,
            Metric::PLAYS
        )
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

        // set media type so we know what metrics to call for
        $this->mediaType = strtolower( $config['media_type'] );
    }

    /**
     * Get insights on a media post.
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
     * @param array $params specific params for the request.
     * @return array of params for the request.
     */
    public function getParams( $params = array() ) {
        if ( $params ) { // specific params have been requested
            return $params;
        } else { // get all params
            // set the metrics param
            $params[Params::METRIC] = Params::commaImplodeArray( $this->metrics[$this->mediaType] );

            // return our params
            return $params;
        }
    } 
}

?>