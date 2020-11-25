<?php

use App_GoogleMaps_Exceptions_InvalidRequest AS InvalidRequestException;
use App_GoogleMaps_Exceptions_NotFound       AS NotFoundException;
use App_GoogleMaps_Exceptions_RequestDenied  AS RequestDeniedException;

use App_GoogleMaps_Services_Payload AS Payload;

abstract class App_GoogleMaps_Services_ResponderAbstract {

  /** @var App_GoogleMaps_Services_Payload $payload */
  protected $payload;

  public function __invoke(Payload $payload) {
    $this->payload = $payload;

    $method = $this->getMethod();
    return $this->$method();

  }//end __construct()

  /**
   * Declare abstracts methods for all sub classes
   */
  abstract protected function ok();
  abstract protected function zeroresults();

  /**
   * Create method name from payload status
   *
   * @return string
   */
  protected function getMethod() : string {
    $method = str_replace('_', '', strtolower($this->payload->getStatus()));
    return (true === method_exists($this, $method)) ? $method : 'notRecognised';

  }//end getMethod()


  /**
   * Respond to request errors. Throws original exception
   *
   * @throws Exception
   */
  protected function error() {
    $results = $this->payload->getResult();
    throw $results['exception'];

  }//end error()


  /**
   * Handle errors when the method doesn't exist
   *
   * @throws Exception
   */
  protected function notRecognised() {
    $status  = $this->payload->getStatus();
    $message = "Unknown return status " . $status;

    throw new Exception($message);

  }//end notRecognised()


  /**
   * Handle Request denied status
   *
   * @throws RequestDeniedException
   */
  protected function requestdenied() {
    $result = $this->payload->getResult();

    throw new RequestDeniedException($result['error_message']);

  }//end requestdenied()


  /**
   * Handle Invalid Request status
   *
   * @throws InvalidRequestException
   */
  protected function invalidrequest() {
    $result = $this->payload->getResult();

    throw new InvalidRequestException($result['error_message']);

  }


  /**
   * Handle Not Found status
   *
   * @throws NotFoundException
   */
  protected function notfound() {
    $result = $this->payload->getResult();

    throw new NotFoundException($result['error_message']);

  }//end notfound()


}//end class
