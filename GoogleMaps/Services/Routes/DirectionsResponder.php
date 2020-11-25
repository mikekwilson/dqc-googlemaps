<?php

class App_GoogleMaps_Services_Routes_DirectionsResponder
  extends App_GoogleMaps_Services_ResponderAbstract {

  protected function ok() {
    //Get result from payload and grab route details array
    $result      = $this->payload->getResult();
    $googleRoute = $result['route'];

    $route = new App_GoogleMaps_Route();
    $route->setOrigin($result['origin'])
          ->setDestination($result['destination'])
          ->setDistance($googleRoute['legs'][0]['distance']['value'])
          ->setPolyline($googleRoute['overview_polyline']['points']);

    return $route;

  }//end ok()


  protected function zeroresults() {
    //Get result from payload and grab route details array
    $result = $this->payload->getResult();

    $route = new App_GoogleMaps_Route();
    $route->setOrigin($result['origin'])
          ->setDestination($result['destination']);

    return $route;

  }//end zeroresults()


}//end class

