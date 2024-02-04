<?php


namespace User;

use Instagram;
use Request\Params;
use Request\Fields;
use Request\Request;
use User\Media;

/**
 * Business Discovery
 *
 * Get info on the Instagram users business account such as info and posts.
 *     - Endpoint Format: GET /{ig-user-id}?fields=business_discovery.username({username})&access_token={access-token}
 *     - Facebook docs: https://developers.facebook.com/docs/instagram-api/reference/ig-user/business_discovery
 */
class BusinessDiscovery extends User {
    /**
     * @var integer $username Instagram username to get info on.
     */
    protected $username;

    /**
     * @var array $fields a list of all the fields we are requesting to get back for the business.
     */
    protected $fields = array(
        Fields::USERNAME,
        Fields::WEBSITE,
        Fields::NAME,
        Fields::IG_ID,
        Fields::ID,
        Fields::PROFILE_PICTURE_URL,
        Fields::BIOGRAPHY,
        Fields::FOLLOWS_COUNT,
        Fields::FOLLOWERS_COUNT,
        Fields::MEDIA_COUNT
    );

    /**
     * @var array $mediaFields a list of all the fields we are requesting to get back for each media object.
     */
    protected $mediaFields = array(
        Fields::ID,
        Fields::USERNAME,
        Fields::CAPTION,
        Fields::LIKE_COUNT,
        Fields::COMMENTS_COUNT,
        Fields::TIMESTAMP,
        Fields::MEDIA_PRODUCT_TYPE,
        Fields::MEDIA_TYPE,
        Fields::OWNER,
        Fields::PERMALINK,
        Fields::MEDIA_URL,
        Fields::THUMBNAIL_URL
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
        
        // set the username
        $this->username = $config['username'];
    }

    /**
     * Get the users account business discovery information and posts.
     *
     * @param array $params Params for the GET request.
     * @return Instagram Response.
     */
    public function getSelf( $params = array() ) {
        $getParams = array( // parameters for our endpoint
            'endpoint' => '/' . $this->userId,
            'params' => $this->getParams( $params )
        );

        // ig get request
        $response = $this->get( $getParams );

        // calculate the next link for paging
        $this->calcNextLink( $response );

        // set prev and next links
        $this->setPrevNextLinks( $response );

        // return response
        return $response;
    }

    /**
     * Calculate next link based on the cursors.
     *
     * @param array $response Instagram api response.
     * @return void
     */
    public function calcNextLink( &$response ) {
        if ( isset( $response[Fields::BUSINESS_DISCOVERY][Fields::MEDIA][Fields::PAGING][Fields::CURSORS][Params::BEFORE] ) ) { // we have previous page
            // get fields string
            $fieldsString = $this->getParams();

            // calculate after string with cursor
            $snippet = Fields::MEDIA . '.' . Params::BEFORE . '(' . $response[Fields::BUSINESS_DISCOVERY][Fields::MEDIA][Fields::PAGING][Fields::CURSORS][Params::BEFORE] . '){';
            
            // update old fields with cursor
            $newFieldsParams = str_replace( Fields::MEDIA . '{', $snippet, $fieldsString );

            // create our media endpoint
            $endpoint = '/' . $this->userId . '/';

            // create our request
            $request = new Request( Request::METHOD_GET, $endpoint, $newFieldsParams, $this->graphVersion, $this->accessToken );

            // set paging next to the url for the next request
            $response[Fields::PAGING][Params::PREVIOUS] = $request->getUrl();
        }

        if ( isset( $response[Fields::BUSINESS_DISCOVERY][Fields::MEDIA][Fields::PAGING][Fields::CURSORS][Params::AFTER] ) ) { // we have another page
            // get fields string
            $fieldsString = $this->getParams();

            // calculate after string with cursor
            $snippet = Fields::MEDIA . '.' . Params::AFTER . '(' . $response[Fields::BUSINESS_DISCOVERY][Fields::MEDIA][Fields::PAGING][Fields::CURSORS][Params::AFTER] . '){';
            
            // update old fields with cursor
            $newFieldsParams = str_replace( Fields::MEDIA . '{', $snippet, $fieldsString );

            // create our media endpoint
            $endpoint = '/' . $this->userId . '/';

            // create our request
            $request = new Request( Request::METHOD_GET, $endpoint, $newFieldsParams, $this->graphVersion, $this->accessToken );

            // set paging next to the url for the next request
            $response[Fields::PAGING][Params::NEXT] = $request->getUrl();
        }
    }

    /**
     * Request previous or next page data.
     *
     * @param string $page specific page to request.
     * @return array of previous or next page data..
     */
    public function getMediaPage( $page ) {
        // get the page to use
        $pageUrl = Params::NEXT == $page ? $this->pagingNextLink : $this->pagingPreviousLink;

        // return the response from the request
        $mediaPageRequest = $this->sendCustomRequest( $pageUrl );

        // calculate the next link for paging
        $this->calcNextLink( $mediaPageRequest );

        // set prev and next links
        $this->setPrevNextLinks( $mediaPageRequest );

        return $mediaPageRequest;
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
            // create our fields string for business discovery
            $fieldsString = Fields::BUSINESS_DISCOVERY . '.' . Fields::USERNAME . '(' . $this->username . '){' . 
                Params::commaImplodeArray( $this->fields ) . ',' . 
                Fields::MEDIA . '{' .
                    Params::commaImplodeArray( $this->mediaFields ) . ',' . 
                    Fields::CHILDREN . '{' .
                        Fields::getDefaultMediaChildrenFields() .
                    '}' .
                '}' .
            '}';

            // return our params
            return Params::getFieldsParam( $fieldsString, false );
        }
    }
}

?>