<?php

class App_GoogleMaps_Map {

  const MARKER_COLOR_ORIGIN      = "0x39B54A";
  const MARKER_COLOR_DESTINATION = "red";
  const MARKER_LABEL_ORIGIN      = "A";
  const MARKER_LABEL_DESTINATION = "B";

  const IMAGE_FORMAT_JPG = 'jpg';
  const IMAGE_FORMAT_PNG = 'png';
  const IMAGE_FORMAT_GIF = 'gif';

  const IMAGE_SCALE_VALUE = '2';

  const SIZE_SMALL   = '250x250';
  const SIZE_MEDIUM  = '472x340';
  const SIZE_DEFAULT = self::SIZE_MEDIUM;

  const TRAVEL_MODE = 'driving';

  /** @var string */
  private $path;

  /** @var string */
  private $destination;

  /** @var string */
  private $origin;

  /** @var string */
  private $apiKey;

  /** @var string */
  private $size;

  /**
   * Google Maps URL API
   *
   * @var string $mapsBaseUrl
   */
  private $mapsBaseUrl = "https://www.google.com/maps/dir/?api=1&";


  /**
   * Google StaticMaps API url
   *
   * @var string $staticMapBaseUrl
   */
  private $staticMapBaseUrl = "https://maps.googleapis.com/maps/api/staticmap?";


  /**
   * Get a map with the size set to small
   *
   * @return string
   */
  public function getSmallMap() {
    $this->setSize(self::SIZE_SMALL);

    return $this->getMapImage();

  }//end getSmallMap()


  /**
   * Get a map with the size set to medium
   *
   * @return string
   */
  public function getMediumMap() {
    $this->setSize(self::SIZE_MEDIUM);

    return $this->getMapImage();

  }//end getMediumMap()


  /**
   * Builds and returns the map image url
   *
   * @return string
   */
  public function getMapImage() : string {
    //Markers queries need to be built seperately to each other
    $originMarker = http_build_query([
      'markers' => $this->getOriginMarker()
    ]);

    $destination = http_build_query([
      'markers' => $this->getDestinationMarker(),
    ]);

    $params = http_build_query([
      'size'   => $this->getSize(),
      'path'   => $this->getPath(),
      'format' => self::IMAGE_FORMAT_PNG,
      'scale'  => self::IMAGE_SCALE_VALUE,
      'key'    => $this->getApiKey(),
    ]);

    return $this->staticMapBaseUrl . $originMarker . '&'. $destination . '&'. $params;

  }//end getMapImage()


  /**
   * Build and returns the map url
   *
   * @return string
   */
  public function getMapUrl() : string {
    $params = http_build_query([
      'origin'      => $this->getOrigin(),
      'destination' => $this->getDestination(),
      'travelmode'  => self::TRAVEL_MODE,
    ]);

    return $this->mapsBaseUrl . $params;

  }//end getMapUrl()


  /**
   * Set the path
   *
   * @param string $polyline
   *
   * @return App_GoogleMaps_Map
   */
  public function setPath(string $polyline=null) {
    if (null !== $polyline) {
      $this->path = "enc:$polyline";
    }

    return $this;

  }//end setPath()


  /**
   * Get the path
   *
   * @return string
   */
  public function getPath() {
    return $this->path;

  }//end getPath()


  /**
   * Set the origin marker
   *
   * @param float $latitude
   * @param float $longitude
   *
   * @return App_GoogleMaps_Map
   */
  public function setOrigin(float $latitude, float $longitude) {
    $this->origin = $latitude . "," . $longitude;

    return $this;

  }//end setOrigin()


  /**
   * Get the origin
   *
   * @return string|null
   */
  public function getOrigin() {
    return $this->origin;

  }//end getOrigin()


  /**
   * Get origin marker
   *
   * @return string
   */
  public function getOriginMarker() {
    if (!$this->origin) {
      return null;

    }

    return "color:" . self::MARKER_COLOR_ORIGIN . "|"
      . "label:" . self::MARKER_LABEL_ORIGIN . "|"
      . $this->origin;

  }//end getOriginMarker()


  /**
   * Set the destination marker
   *
   * @param float $latitude
   * @param float $longitude
   *
   * @return App_GoogleMaps_Map
   */
  public function setDestination(float $latitude, float $longitude) {
    $this->destination = $latitude . "," . $longitude;

    return $this;

  }//end setDestination()


  /**
   * Get the destination
   *
   * @return string|null
   */
  public function getDestination() {
    return $this->destination;

  }//end getDestination()


  /**
   * Get destination marker
   *
   * @return string
   */
  public function getDestinationMarker() {
    if (!$this->destination) {
      return null;

    }

    return "color:" . self::MARKER_COLOR_DESTINATION . "|"
      . "label:" . self::MARKER_LABEL_DESTINATION . "|"
      . $this->destination;

  }//end getDestinationMarker()


  /**
   * Set the api key
   *
   * @param string $apiKey
   *
   * @return App_GoogleMaps_Map
   */
  public function setApiKey(string $apiKey) : App_GoogleMaps_Map {
    $this->apiKey = $apiKey;

    return $this;

  }//end setApiKey()


  /**
   * Get the api key
   *
   * @return string
   */
  public function getApiKey() {
    return $this->apiKey;

  }//end getApiKey()


  /**
   * Set the size of map required
   *
   * @param string $size Size as an x & y string i.e. 100x100
   *
   * @return App_GoogleMaps_Map
   */
  public function setSize(string $size) : App_GoogleMaps_Map {
    $this->size = $size;

    return $this;

  }//end setSize()


  /**
   * Get the current size.
   *
   * Returns the default if the size hasn't been set explicitly
   *
   * @return string
   */
  public function getSize() {
    if (!$this->size) {
      return self::SIZE_DEFAULT;
    }

    return $this->size;

  }//end getSize()


}//end class
