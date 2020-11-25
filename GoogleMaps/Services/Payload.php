<?php
/**
 * Stores results from API calls made by services
 *
 * @author Mike Wilson <mwilson@deliveryquotecompare.com>
 */

/**
 * Class App_GoogleMaps_Services_Payload
 */
class App_GoogleMaps_Services_Payload {

  //API call returned successfully
  const STATUS_OK = 'OK';

  //API call successful by no results found
  const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';

  //Error
  const STATUS_ERROR = "ERROR";

  //Unknown server-side error
  const STATUS_UNKNOWN_ERROR = 'UNKNOWN_ERROR';

  //Request was denined by the server
  const STATUS_REQUEST_DENIED = 'REQUEST_DENIED';

  //Indicates that the query is missing
  const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';

  //The referenced location was not found
  const STATUS_NOT_FOUND = 'NOT_FOUND';


  /** @var string $status */
  private $status;

  /** @var array $result */
  private $result;

  /**
   * @param string $status Status of the service response.
   * @param array  $result Result of the service.
   */
  public function __construct(string $status, array $result) {
        $this->status = $status;
        $this->result = $result;

  }//end __construct


  /**
   * Return the current status
   *
   * @return string
   */
  public function getStatus() : string {
    return $this->status;

  }//end getStatus()
  

  /**
   * Return the result
   *
   * @return array
   */
  public function getResult() : array {
    return $this->result;

  }//end getResult()


}//end class
