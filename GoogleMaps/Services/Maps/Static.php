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
class App_GoogleMaps_Services_Maps_Static
  extends App_GoogleMaps_Services_ServiceAbstract {

  /** @var App_GoogleMaps_Location $origin */
  private $origin;

  /** @var App_GoogleMaps_Location $destination */
  private $destination;

  /** @var App_GoogleMaps_Route $route */
  private $route;


  /**
   * Check all required objects and prep response
   *
   * @return App_GoogleMaps_Services_Payload
   */
  public function getMap() {
    //Check if required parameters are available
    if (!$origin = $this->getOrigin()) {
      $status = Payload::STATUS_ERROR;
      $result = [
        'error'     => 'Origin not set',
        'exception' => new Exception('Origin not set'),
      ];

    } elseif (!$destination = $this->getDestination()) {
      $status = Payload::STATUS_ERROR;
      $result = [
        'error'     => 'Destination not set',
        'exception' => new Exception('Destination not set'),
      ];

    } elseif( !$route = $this->getRoute()) {
      $status = Payload::STATUS_ERROR;
      $result = [
        'error'     => 'Route not set',
        'exception' => new Exception('Route not set'),
      ];

    } else {
      $status = Payload::STATUS_OK;
      $result = [
          'destination' => $destination,
          'origin'      => $origin,
          'route'       => $route,
          'apiKey'      => $this->getApiKey(),
      ];
    }

    return $this->payload($status, $result);

  }//end getMap()


  /**
   * Returns a url for API class. Not used by this sub class.
   *
   * @return string
   */
  public function getFullUrl() : string {
    return "Method not implemented for this class";

  }//end getFullUrl()


  /**
   * Set the origin location
   *
   * @param App_GoogleMaps_Location $origin
   *
   * @return App_GoogleMaps_Services_Maps_Static
   */
  public function setOrigin(App_GoogleMaps_Location $origin) {
    $this->origin = $origin;

    return $this;

  }//end setOrigin()


  /**
   * Get the origin location
   *
   * @return App_GoogleMaps_Location
   */
  public function getOrigin() {
    return $this->origin;

  }//end getOrigin()


  /**
   * Set the destination location
   *
   * @param App_GoogleMaps_Location $destination
   *
   * @return App_GoogleMaps_Services_Maps_Static
   */
  public function setDestination(App_GoogleMaps_Location $destination) {
    $this->destination = $destination;

    return $this;

  }//end setDestination()


  /**
   * Get the destination location
   *
   * @return App_GoogleMaps_Location
   */
  public function getDestination() {
    return $this->destination;

  }//end getDestination()


  /**
   * Set the route
   *
   * @param App_GoogleMaps_Route $route
   *
   * @return App_GoogleMaps_Services_Maps_Static
   */
  public function setRoute(App_GoogleMaps_Route $route) {
    $this->route = $route;

    return $this;

  }//end setRoute()


  /**
   * Get the route
   *
   * @return App_GoogleMaps_Route
   */
  public function getRoute() {
    return $this->route;

  }//end getRoute()


}//end class
