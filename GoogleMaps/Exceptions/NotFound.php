<?php

use App_GoogleMaps_Services_Payload AS Payload;

class App_GoogleMaps_Exceptions_NotFound
  extends Exception {

  /**
   * Custom constructor to add standard message
   *
   * @param string $message
   */
  public function __construct(string $message=null) {
    parent::__construct(Payload::STATUS_NOT_FOUND .": ". $message);

  }//end __construct()


}//end class

