<?php
/**
 * Interact with the Google Places Geocoding API
 *
 * @author Mike Wilson <mwilson@deliveryquotecompare>
 */

use App_GoogleMaps_Manager AS GoogleMapsManager;
use App_GoogleMaps_Services_Payload AS Payload;

/**
 * Class App_GoogleMaps_Services_Places_Geocoding
 */
class App_GoogleMaps_Services_Places_Geocoding
  extends App_GoogleMaps_Services_ServiceAbstract {

  /** @var float */
  private $latitude;

  /** @var float */
  private $longitude;

  /** @var string */
  private $address;

  /** 
   * Google API url
   *
   * @var string $apiUrl
   * */
  private $apiUrl = "/maps/api/geocode/";


  /**
   * Get Location info from reverse Geocoding API
   *
   * @return App_GoogleMaps_Services_Payload
   */
  public function getLocation() : Payload {
    try {
      $cacheId = $this->getCacheId();

      if (!$responseBody = $this->cache->get($cacheId)) {
        //Cache miss, make call to Google API and decode response
        $response = $this->client->request('GET', $this->getFullUrl());
        $responseBody = json_decode($response->getBody(), true);

        //Cache response
        $this->cache->set($cacheId, $responseBody, (60*60*24*30));

      }

      //Grab fields for payload
      $status = $responseBody['status'];

      //Grab first result
      $result['details'] = $responseBody['results'][0] ?? null;

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

  }//end getLocation()


  /**
   * Get the cacheId
   *
   * @return string
   */
  public function getCacheId() {
    if ($this->getAddress()) {
      return str_replace(' ', '+', "googlePlacesGeocoding-" . $this->getAddress());
    }

    if ($this->getLatitude() && $this->getLatitude()) {
      return "googlePlacesGeocoding-"
        . $this->getLatitude()
        . '-'
        . $this->getLongitude();

    }

  }//end getCacheId()


  /**
   * Get the full url with query params
   *
   * @return string
   */
  public function getFullUrl() : string {
    if ($this->getAddress()) {
      $params['address'] = $this->getAddress();

    }

    elseif ($this->getLatitude() && $this->getLatitude()) {
      $params['latlng'] = sprintf("%s,%s", $this->getLatitude(), $this->getLongitude()); 

    }

    $params['key'] = $this->getApiKey();

    return $this->apiUrl . GoogleMapsManager::OUTPUT . '?' . http_build_query($params);

  }//end getFullUrl()


  /**
   * Get the latitude
   *
   * @return float
   */
  public function getLatitude() {
    return $this->latitude;

  }//end getLatitude()


  /**
   * Set the latitude
   *
   * @param float $latitude
   *
   * @return App_GoogleMaps_Services_Places_Geocoding
   */
  public function setLatitude(float $latitude) : App_GoogleMaps_Services_Places_Geocoding {
    $this->latitude = $latitude;

    return $this;

  }//end setLatitude()


  /**
   * Get the longitude
   *
   * @return float
   */
  public function getLongitude() {
    return $this->longitude;

  }//end getLongitude()


  /**
   * Set the longitude
   *
   * @param float $longitude
   *
   * @return App_GoogleMaps_Services_Places_Geocoding
   */
  public function setLongitude(float $longitude) : App_GoogleMaps_Services_Places_Geocoding {
    $this->longitude = $longitude;

    return $this;

  }//end setLongitude()


  /**
   * Get the Address
   *
   * @return string
   */
  public function getAddress() {
    return $this->address;

  }//end getAddress()


  /**
   * Set the Address
   *
   * @param string $address
   *
   * @return App_GoogleMaps_Services_Places_Geocoding
   */
  public function setAddress(string $address) : App_GoogleMaps_Services_Places_Geocoding {
    $this->address = $address;

    return $this;

  }//end setAddress()


}//end class
