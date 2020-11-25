<?php

class App_GoogleMaps_Route {

  /** @var App_GoogleMaps_Location $origin */
  private $origin;

  /** @var App_GoogleMaps_Location $destination */
  private $destination;

  /** @var int $distance */
  private $distance;

  /** @var $polyline */
  private $polyline;


  /**
   * Set origin
   *
   * @param App_GoogleMaps_Location $origin
   */
  public function setOrigin(App_GoogleMaps_Location $origin) {
    $this->origin = $origin;

    return $this;

  }//end setOrigin()


  /**
   * Get Origin
   *
   * @return App_GoogleMaps_Location
   */
  public function getOrigin() {
    return $this->origin;

  }//end getOrigin()


  /**
   * Set destination
   *
   * @param App_GoogleMaps_Location $destination
   */
  public function setDestination(App_GoogleMaps_Location $destination) {
    $this->destination = $destination;

    return $this;

  }//end setDestination()


  /**
   * Get Destination
   *
   * @return App_GoogleMaps_Location
   */
  public function getDestination() {
    return $this->destination;

  }//end getDestination()


  /**
   * Get distance in metres
   *
   * @return int
   */
  public function getDistance() {
    return $this->distance;

  }//end getDistance()

  /**
   * Set distance 
   *
   * @param int $distance
   *
   * @return App_GoogleMaps_Route
   */
  public function setDistance(int $distance) : App_GoogleMaps_Route {
    $this->distance = $distance;

    return $this;

  }//end setDistance()


  /**
   * Get polyline
   *
   * @return string
   */
  public function getPolyline() {
    return $this->polyline;

  }//end getPolyline()


  /**
   * Set polyline
   *
   * @param string $polyline
   *
   * @return App_GoogleMaps_Route
   */
  public function setPolyline(string $polyline) : App_GoogleMaps_Route {
    $this->polyline = $polyline;

    return $this;

  }//end setPolyline()


  /**
   * @return bool
   */
  public function isRouteExists() {
    return null != $this->getDistance();

  }//end isRouteExists()


}//end class
