<?php

class App_GoogleMaps_Autocomplete {

  /** @var array $predictions */
  private $predictions;

  /** @var string $input */
  private $input;


  /**
   * Get the predictions array
   *
   * @return array
   */
  public function getPredictions() {
    return $this->predictions;

  }//end getPredictions()


  /**
   * Set the predictions array
   *
   * @param array $predictions
   *
   * @return App_GoogleMaps_Autocomplete
   */
  public function setPredictions(array $predictions) : App_GoogleMaps_Autocomplete {
    $this->predictions = $predictions;

    return $this;

  }//end setPredictions()


  /**
   * Append a prediction to the prediciton array
   *
   * @param array $prediction
   *
   * @return App_GoogleMaps_Autocomplete
   */
  public function addPrediction(array $prediction) {
    $this->predictions[] = $prediction;

    return $this;

  }//end addPrediction()


  /**
   * Get the input string
   *
   * @return string
   */
  public function getInput() {
    return $this->input;

  }//end getString()


  /**
   * Set the input string
   *
   * @param string $input
   *
   * @return App_GoogleMaps_Autocomplete
   */
  public function setInput(string $input) : App_GoogleMaps_Autocomplete {
    $this->input = $input;

    return $this;

  }//end setInput()


  /**
   * Return object variables in an array
   *
   * @return array
   */
  public function toArray() : array {
    return [
      'input'       => $this->getInput(),
      'predictions' => $this->getPredictions() ?? [],
    ];

  }//end toArray()


}//end class
