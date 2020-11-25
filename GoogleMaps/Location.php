<?php

class App_GoogleMaps_Location {

  /** @var string */
  private $addressLineOne;

  /** @var string */
  private $addressLineTwo;

  /** @var string */
  private $country;

  /** @var string */
  private $county;

  /** @var string */
  private $label;

  /** @var float */
  private $latitude;

  /** @var float */
  private $longitude;

  /** @var string */
  private $name;

  /** @var string */
  private $placeId;
  
  /** @var string */
  private $postalCode;

  /** @var string */
  private $territory;

  /** @var string */
  private $town;


  /**
   * Set first line of address
   *
   * @param string $addressLine
   *
   * @return App_GoogleMaps_Location
   */
  public function setAddressLineOne(string $addressLine) {
    $this->addressLineOne = $addressLine;

    return $this;

  }//end setAddressLineOne()


  /**
   * Get address line 1
   *
   * @return string
   */
  public function getAddressLineOne() {
    return $this->addressLineOne;

  }//end getAddressLineOne()


  /**
   * Set second line of address
   *
   * @param string $addressLine
   *
   * @return App_GoogleMaps_Location
   */
  public function setAddressLineTwo(string $addressLine) {
    $this->addressLineTwo = $addressLine;

    return $this;

  }//end setAddressLineTwo()


  /**
   * Get address line 2
   *
   * @return string
   */
  public function getAddressLineTwo() {
    if( $this->addressLineTwo == $this->town || null == $this->town ) {
      return null;

    }

    return $this->addressLineTwo;

  }//end getAddressLineOne()


  /**
   * Set Country ISO
   *
   * @param string $country Country ISO code ('GB', 'AU'...)
   *
   * @return App_GoogleMaps_Location
   */
  public function setCountry(string $country) {
    $this->country = $country;

    return $this;

  }//end setCountry()


  /**
   * Get country ISO code
   *
   * @return string
   */
  public function getCountry() {
    return $this->country;

  }//end getCountry()


  /**
   * Set County
   *
   * @param string $county 
   *
   * @return App_GoogleMaps_Location
   */
  public function setCounty(string $county) {
    $this->county = $county;

    return $this;

  }//end setCounty()


  /**
   * Get county ISO code
   *
   * @return string
   */
  public function getCounty() {
    if( $this->country == 'AU' ) {
      return $this->territory;

    }

    return $this->county;

  }//end getCountry()


  /**
   * Set town name
   *
   * @param string $town
   *
   * @return App_GoogleMaps_Location
   */
  public function setTown(string $town) {
    $this->town = $town;

    return $this;

  }//end setTown()


  /**
   * Get town name
   *
   * @return string
   */
  public function getTown() {
    if( null == $this->town && null !== $this->addressLineTwo ) {
      return $this->addressLineTwo;

    }

    return $this->town;

  }//end getTown()


  /**
   * @return float
   */
  public function getLongitude() {
    return $this->longitude;

  }//end getLongitude()


  /**
   * @param float $longitude
   * @return App_GoogleMaps_Location
   */
  public function setLongitude($longitude) {
      $this->longitude = $longitude;

      return $this;

  }//end setLongitude()


  /**
   * @return float
   */
  public function getLatitude() {
    return $this->latitude;

  }//end getLatitude()


  /**
   * @param float $latitude
   * @return App_GoogleMaps_Location
   */
  public function setLatitude($latitude) {
    $this->latitude = $latitude;

    return $this;

  }//end setLatitude()


  /**
   * @return string
   */
  public function getName() {
    return $this->name;

  }//end getName()


  /**
   * @param string $name
   *
   * @return App_GoogleMaps_Location
   */
  public function setName($name) {
    $this->name = $name;

    return $this;

  }//end setName()


  /**
   * Set post code
   *
   * @param string $postalCode
   *
   * @return App_GoogleMaps_Location
   */
  public function setPostalCode(string $postalCode) {
    $this->postalCode = $postalCode;

    return $this;

  }//end setPostalCode()


  /**
   * Get Postal code
   *
   * @return string
   */
  public function getPostalCode() {
    return $this->postalCode;

  }//end getPostalCode()


  /**
   * Set territory
   *
   * @param string $territory
   *
   * @return App_GoogleMaps_Location
   */
  public function setTerritory(string $territory) {
    $this->territory = $territory;

    return $this;

  }//end setTerritory()


  /**
   * Get Territory
   *
   * @return string
   */
  public function getTerritory() {
    return $this->territory;

  }//end getTerritory()


  /**
   * Set Label
   * 
   * @param string $label
   *
   * @return App_GoogleMaps_Location
   */
  public function setLabel(string $label) {
    $this->label = $label;

    return $this;

  }//end setLabel()


  /**
   * Get label
   *
   * @return string
   */
  public function getLabel() {
    return $this->label;

  }//end getLabel()


  /**
   * Set Place Id
   *
   * @param string $placeId
   *
   * @return App_GoogleMaps_Location
   */
  public function setPlaceId(string $placeId) {
    $this->placeId = $placeId;

    return $this;

  }//end setPlaceId()


  /**
   * Get PlaceId
   *
   * @return string
   */
  public function getPlaceId() {
    return $this->placeId;

  }//end getPlaceId()


  /**
   * Convert object to an array
   *
   * @return array
   */
  public function toArray() {
    return [
      'address' => [
        'address_line1' => $this->getAddressLineOne(),
        'address_line2' => $this->getAddressLineTwo(),
        'country'       => $this->getCountry(),
        'county'        => $this->getCounty(),
        'label'         => $this->getLabel(),
        'latitude'      => $this->getLatitude(),
        'longitude'     => $this->getLongitude(),
        'name'          => $this->getName(),
        'placeId'       => $this->getPlaceId(),
        'postcode'      => $this->getPostalCode(),
        'territory'     => $this->getTerritory(),
        'town'          => $this->getTown(),
      ]
    ];

    }//end toArray()


  }//end class()
