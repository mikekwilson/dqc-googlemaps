<?php

abstract class App_GoogleMaps_Services_ServiceAbstract {

  /** @var string $apiKey */
  protected $apiKey;

  /** @var GuzzleHttp\Client $client */
  protected $client;

  /** @var Clickalicious\Memcached\Client $cache */
  protected $cache;


  /**
   * @param GuzzleHttp\Client $client
   * @param Clickalicious\Memcached\Client $cache
   */
  public function __construct($client, $cache) {
    $this->client = $client;
    $this->cache  = $cache;

  }//end __construct()

  /**
   * Get the full url with query params
   *
   * @return string
   */
  abstract public function getFullUrl();

  /**
   * Return new instance of Payload
   *
   * @param string $status Status of API call
   * @param array  $result Response values
   *
   * @return App_GoogleMaps_Services_Payload
   */
  protected function payload(string $status, array $result) : App_GoogleMaps_Services_Payload {
    return new App_GoogleMaps_Services_Payload($status, $result);

  }//end payload()


  /**
   * Set api key
   *
   * @param string $apiKey
   *
   * @return App_GoogleMaps_Services_ServiceAbtract
   */
  public function setApiKey(string $apiKey=null) {
    $this->apiKey = $apiKey;

    return $this;

  }//end setApiKey()


  /**
   * Get Api Key
   *
   * @return string
   */
  public function getApiKey() {
    return $this->apiKey;

  }//end getApiKey()


}
