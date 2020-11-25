<?php
/**
 * Interact with the Google Places Autocomplete API
 *
 * @author Mike Wilson <mwilson@deliveryquotecompare>
 */

use App_GoogleMaps_Manager AS GoogleMapsManager;
use App_GoogleMaps_Services_Payload AS Payload;

/**
 * Class App_GoogleMaps_Services_Places_Details
 */
class App_GoogleMaps_Services_Places_Autocomplete
  extends App_GoogleMaps_Services_ServiceAbstract {

  /**
   * Google Places Autocomplete API types
   * https://developers.google.com/places/web-service/autocomplete#place_types
   */
  const TYPE_GEOCODE        = 'geocode';
  const TYPE_ADDRESS        = 'address';
  const TYPE_ESTABLISHMENT  = 'establishment';
  const TYPE_REGIONS        = '(regions)';
  const TYPE_CITIES         = '(cities)';

  /** @var Array */
  protected $type = self::TYPE_GEOCODE;

  /** @var string $input */
  private $input;

  /** @var array $countries */
  private $countries=[];

  /** @var string $sessionId */
  private $sessionId;

  /** 
   * Google API url
   *
   * @var string $apiUrl
   * */
  private $apiUrl = "/maps/api/place/autocomplete/";


  /**
   * Get autocomplete predictions from google API
   *
   * @return App_GoogleMaps_Services_Payload
   */
  public function getAutocomplete() {
    try {
      //Check for response in cache before making API call
      $cacheId = "googleAutocomplete-"
        . str_replace(' ', '', $this->getInput())
        . "-$this->type-"
        . implode(",", $this->countries);

      if ( !($responseBody = $this->cache->get($cacheId)) ) {
        //Cache miss, make API call and store response
        $response = $this->client->request('GET', $this->getFullUrl());
        $responseBody = json_decode($response->getBody(), true);

        //Cache for 30 days
        $this->cache->set($cacheId, $responseBody, (60*60*24*30));

      }

      //Grab fields for payload
      $status                = $responseBody['status'];
      $result['input']       = $this->getInput();
      $result['predictions'] = $responseBody['predictions'];

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
        'input'     => $this->getInput(),
      ];

    }

    return $this->payload($status, $result);

  }//end getAutocomplete()


  /**
   * Get the full url with query params
   *
   * @return string
   */
  public function getFullUrl() {
    // Country codes must be sent as 'country:xx'
    $components = [];
    foreach( $this->getCountries() as $country ) {
      $components[] = 'country:' . $country;
    }

    $params = http_build_query([
      'input' => $this->getInput(),
      'types' => $this->type,
      'components' => implode("|", $components),
      'key' => $this->getApiKey(),
      'session_token' => $this->getSessionId(),
    ]);

    $url = $this->apiUrl . GoogleMapsManager::OUTPUT .'?' . $params;

    return $url;

  }//end getFullUrl()


  /**
   * Set input string
   *
   * @param string $input
   *
   * @return App_GoogleMaps_Services_Places_Autocomplete
   */
  public function setInput(string $input=null) : App_GoogleMaps_Services_Places_Autocomplete {
    $this->input = $input;

    return $this;

  }//end setInput()


  /**
   * Get input string
   *
   * @return string
   */
  public function getInput() {
    return $this->input;

  }//end getInput()


  /**
   * Set countries array
   *
   * @param array $countries
   *
   * @return App_GoogleMaps_Services_Places_Autocomplete
   */
  public function setCountries(array $countries=[]) : App_GoogleMaps_Services_Places_Autocomplete {
    $this->countries = $countries;

    return $this;

  }//end setCountries()


  /**
   * Get countries array
   *
   * @return array
   */
  public function getCountries() {
    return $this->countries;

  }//end getCountries()

  
  /**
   * Set session Id
   *
   * @param string $sessionId
   *
   * @return App_GoogleMaps_Services_Places_Autocomplete
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
