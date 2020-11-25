<?php

use App_GoogleMaps_Services_Payload AS Payload;

class App_GoogleMaps_Exceptions_InvalidRequest
  extends Exception {

  /**
   * Custom constructor to add standard message
   *
   * @param string $message
   */
  public function __construct(string $message=null) {
    parent::__construct(Payload::STATUS_INVALID_REQUEST .": ". $message);

  }//end __construct()


}//end class

