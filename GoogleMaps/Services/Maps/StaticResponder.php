<?php

class App_GoogleMaps_Services_Maps_StaticResponder
  extends App_GoogleMaps_Services_ResponderAbstract {

  protected function ok() {
    $result = $this->payload->getResult();

    $destination = $result['destination'];
    $origin      = $result['origin'];
    $route       = $result['route'];

    $map = new App_GoogleMaps_Map();
    $map->setPath($route->getPolyline())
        ->setOrigin(
          $origin->getLatitude(),
          $origin->getLongitude()
        )
        ->setDestination(
          $destination->getLatitude(),
          $destination->getLongitude()
        )
        ->setApiKey($result['apiKey']);

    return $map;

  }//end ok()

  /**
   * Zero Results should never be the status for this service
   *
   * @throws Exception
   */
  protected function zeroresults() {
    throw new Exception('Zero results is not a valid status');

  }

}//end class
