<?php
/**
 * Interact with the Google Places Detail API
 *
 * @author Mike Wilson <mwilson@deliveryquotecompare>
 */

use App_GoogleMaps_Manager AS GoogleMapsManager;
use App_GoogleMaps_Services_Payload AS Payload;

/**
 * Class App_GoogleMaps_Services_Places_Details
 */
class App_GoogleMaps_Services_Places_Details
  extends App_GoogleMaps_Services_ServiceAbstract {

  /** @var string $placeId */
  private $placeId;

  /** @var string $sessionId */
  private $sessionId;

  /** 
   * Google API url
   *
   * @var string $apiUrl
   * */
  private $apiUrl = "/maps/api/place/details/";

  /** 
   * List of fields to request
   *
   * @var array
   */
  private $fields = [
    'address_component',
    'adr_address',
    'alt_id',
    'formatted_address',
    'geometry',
    'icon',
    'id',
    'name',
    'place_id',
  ];


  /**
   * Get Places detail from Place Id.
   *
   * @return App_GoogleMaps_Services_Payload
   */
  public function getDetails() {
    try {
      //Check for response in Cache before making API call
      $cacheId = "googlePlacesDetails-{$this->getPlaceId()}-" . implode(",", $this->fields);
      if ( !($responseBody = $this->cache->get($cacheId)) ) {
        //Cache miss, make call to Google API and decode response
        $response = $this->client->request('GET', $this->getFullUrl());
        $responseBody = json_decode($response->getBody(), true);

        //Cache response for 30 days
        $this->cache->set($cacheId, $responseBody, (60*60*24*30));

      }

      //Grab fields for payload
      $status = $responseBody['status'];
      unset($responseBody['status']);
      $result['details'] = $responseBody['result'] ?? null;

      if ($status !== Payload::STATUS_OK && $status !== Payload::STATUS_ZERO_RESULTS) {
        //Check for errors returned by the API
        $result['error_message'] = $responseBody['error_message'] ?? null;

      }

    } catch (Exception $e) {
      //Catches all non 2xx responses
      $status = Payload::STATUS_ERROR;
      $result = [
        'exception' => $e,
        'error'     => $e->getMessage(),
      ];

    }

    return $this->payload($status, $result);

  }//end getDetails()


  /**
   * Get the full url with query params
   *
   * @return string
   */
  public function getFullUrl() : string {
    $params = http_build_query([
      'placeid'       => $this->getPlaceId(),
      'key'           => $this->getApiKey(),
      'fields'        => implode(",", $this->fields),
      'session_token' => $this->getSessionId(),
    ]);

    $url = $this->apiUrl . GoogleMapsManager::OUTPUT .'?' . $params;

    return $url;
  }



  /**
   * Set place Id
   *
   * @param string $placeId
   *
   * @return App_GoogleMaps_Services_Places_Details
   */
  public function setPlaceId(string $placeId) {
    $this->placeId = $placeId;

    return $this;

  }//end setPlaceId()


  /**
   * Get Place Id
   *
   * @return string
   */
  public function getPlaceId() {
    return $this->placeId;

  }//end getPlaceId()


  /**
   * Set session Id
   *
   * @param string $sessionId
   *
   * @return App_GoogleMaps_Services_Places_Details
   */
  public function setSessionId(string $sessionId=null) {
    $this->sessionId = $sessionId;

    return $this;

  }//end setSessionId()


  /**
   * Get session Id
   *
   * @return string
   */
  public function getSessionId() {
    return $this->sessionId;

  }//end getSessionId()


}//end class
