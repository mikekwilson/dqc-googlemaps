<?php
/**
 * Interact with the Google Directions API
 *
 * @author Mike Wilson <mwilson@deliveryquotecompare>
 */

use App_GoogleMaps_Manager AS GoogleMapsManager;
use App_GoogleMaps_Services_Payload AS Payload;

/**
 * Class App_GoogleMaps_Services_Places_Details
 */
class App_GoogleMaps_Services_Routes_Directions
  extends App_GoogleMaps_Services_ServiceAbstract {

  /** @var App_GoogleMaps_Location $origin */
  private $origin;

  /** @var App_GoogleMaps_Location $destination */
  private $destination;

  /** 
   * Google API url
   *
   * @var string $apiUrl
   * */
  private $apiUrl = "/maps/api/directions/";


  /**
   * Get Directions from Google API
   *
   * @return App_GoogleMaps_Services_Payload
   */
  public function getDirections() {
    try {
      $cacheId = "googleRoutesDirections-"
        . $this->getOriginLocation()->getPlaceId()
        . "-"
        . $this->getDestinationLocation()->getPlaceId();

      if ( !($responseBody = $this->cache->get($cacheId)) ) {
        //Cache miss, make call to Google API and decode response
        $response = $this->client->request('GET', $this->getFullUrl());
        $responseBody = json_decode($response->getBody(), true);

        //Cache response for 30 days
        $this->cache->set($cacheId, $responseBody, (60*60*24*30));

      }

      //Grab fields for payload
      $status                = $responseBody['status'];
      $result['route']       = $responseBody['routes'][0] ?? null; //Grab first route or null
      $result['origin']      = $this->getOriginLocation();
      $result['destination'] = $this->getDestinationLocation();

      if ($status !== Payload::STATUS_OK) {
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

  }//end getDirections()


  public function getFullUrl() : string {
    $originPlaceId      = $this->getOriginLocation()->getPlaceId();
    $destinationPlaceId = $this->getDestinationLocation()->getPlaceId();

    $params = http_build_query([
      'origin' => 'place_id:' . $originPlaceId,
      'destination' => 'place_id:' . $destinationPlaceId,
      'key' => $this->getApiKey(),
    ]);

    $url = $this->apiUrl . GoogleMapsManager::OUTPUT . '?' . $params;

    return $url;

  }//end getFullUrl()


  /**
   * Set origin to location
   *
   * @param App_GoogleMaps_Location $origin
   *
   * @return App_GoogleMaps_Services_Routes_Directions
   */
  public function setOriginLocation(App_GoogleMaps_Location $origin) {
    $this->origin = $origin;

    return $this;

  }//end setOriginLocation()


  /**
   * Get origin location
   *
   * @return App_GoogleMaps_Location
   */
  public function getOriginLocation() {
    return $this->origin;

  }//end getOriginLocation()


  /**
   * Set destination to location
   *
   * @param App_GoogleMaps_Location $destination
   *
   * @return App_GoogleMaps_Services_Routes_Directions
   */
  public function setDestinationLocation(App_GoogleMaps_Location $destination) {
    $this->destination = $destination;

    return $this;

  }//end setDestinationLocation()


  /**
   * Get destination location
   *
   * @return App_GoogleMaps_Location
   */
  public function getDestinationLocation() {
    return $this->destination;

  }//end getDestinationLocation()


}//end class

