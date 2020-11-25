<?php

class App_GoogleMaps_Services_Places_AutocompleteResponder
  extends App_GoogleMaps_Services_ResponderAbstract {

  protected function ok() {
    $result = $this->payload->getResult();

    $autocomplete = new App_GoogleMaps_Autocomplete();
    $autocomplete->setInput($result['input']);

    foreach ($result['predictions'] as $prediction) {
      $autocomplete->addPrediction([
        'place_id' => $prediction['place_id'],
        'description' => $prediction['description'],
      ]);
    }

    return $autocomplete;

  }//end ok()


  protected function zeroresults() {
    $result = $this->payload->getResult();

    $autocomplete = new App_GoogleMaps_Autocomplete();
    return $autocomplete->setInput($result['input']);

  }//end zeroresults()


}//end class
