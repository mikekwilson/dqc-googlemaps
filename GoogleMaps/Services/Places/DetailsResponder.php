<?php

class App_GoogleMaps_Services_Places_DetailsResponder 
  extends App_GoogleMaps_Services_ResponderAbstract {

  /**
   * Format Places Details API response and return as Location object
   *
   * @return App_GoogleMaps_Location
   */
  protected function ok() {
    //Get result from payload and grab address details array
    $result  = $this->payload->getResult();
    $details = $result['details'];

    $location = new App_GoogleMaps_Location();
    $location->setLabel($details['formatted_address'])
             ->setPlaceId($details['place_id'])
             ->setLatitude($details['geometry']['location']['lat'])
             ->setLongitude($details['geometry']['location']['lng']);

    //Convert response to match system field names
    foreach($details['address_components'] as $component) {
      foreach($component['types'] as $type) {
        switch($type) {
          case 'postal_code';
            $location->setPostalCode($component['long_name']);
            break;
          case 'administrative_area_level_1';
            $location->setTerritory($component['short_name']);
            break;
          case 'administrative_area_level_2';
            $location->setCounty($component['long_name']);
            break;
          case 'postal_town';
            $location->setTown($component['long_name']);
            break;
          case 'locality';
          case 'neighbourhood';
            $location->setAddressLineTwo($component['long_name']);
            break;
          case 'route';
            $addressLineOne = preg_replace('/(\s){2,}/', ' ', $location->getAddressLineOne() .' '. $component['long_name']);
            $location->setAddressLineOne($addressLineOne);
            break;
          case 'street_number';
            $addressLineOne = preg_replace('/(\s){2,}/', ' ', $component['long_name'] .' '. $location->getAddressLineOne());
            $location->setAddressLineOne($addressLineOne);
            break;
          case 'country';
          case 'political';
            $location->setCountry($component['short_name']);
            break;

        }// end switch
      }//end foreach
    }//end foreach

    /** @var App_GoogleMaps_Location */
    return $location;

  }//end ok()


  /**
   * Respond if no results are returned
   *
   * @return App_GoogleMaps_Location
   */
  protected function zeroresults() {
    return new App_GoogleMaps_Location();

  }//end zeroresults()


}//end class
