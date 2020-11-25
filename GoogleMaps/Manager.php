<?php
/**
 * Provides single access point to all Google Maps services & api calls
 *
 * @author Mike Wilson <mwilsoN@deliveryquotecompare.com>
 */

use App_GoogleMaps_Services_Payload                      AS Payload;
use App_GoogleMaps_Services_Places_Autocomplete          AS PlacesAutocompleteService;
use App_GoogleMaps_Services_Places_AutocompleteResponder AS PlacesAutocompleteResponder;
use App_GoogleMaps_Services_Places_Details               AS PlacesDetailService;
use App_GoogleMaps_Services_Places_DetailsResponder      AS PlacesDetailResponder;
use App_GoogleMaps_Services_Routes_Directions            AS RoutesDirectionService;
use App_GoogleMaps_Services_Routes_DirectionsResponder   AS RoutesDirectionResponder;
use App_GoogleMaps_Services_Maps_Static                  AS MapsStaticService;
use App_GoogleMaps_Services_Maps_StaticResponder         AS MapsStaticResponder;
use App_GoogleMaps_Services_Places_Geocoding             AS PlacesGeocodingService;
use App_GoogleMaps_Services_Places_GeocodingResponder    AS PlacesGeocodingResponder;

/**
 * Class App_GoogleMaps_Manager
 */
class App_GoogleMaps_Manager {

  const OUTPUT = 'json';

  const MAX_RETRIES = 5;

  /** @var App_GoogleMaps_Services_Places_Autocomplete $placesAutocompleteService */
  private $placesAutocompleteService;

  /** @var App_GoogleMaps_Services_Places_AutocompleteResponder $placesAutocompleteResponder */
  private $placesAutocompleteResponder;

  /** @var App_GoogleMaps_Services_Places_Details $placesDetailService */
  private $placesDetailService;

  /** @var App_GoogleMaps_Services_Places_DetailsResponder $placesDetailResponder */
  private $placesDetailResponder;

  /** @var App_GoogleMaps_Services_Routes_Directions $routesDirectionService */
  private $routesDirectionService;

  /** @var App_GoogleMaps_Services_Routes_DirectionsResponder $routesDirectionResponder */
  private $routesDirectionResponder;

  /** @var App_GoogleMaps_Services_Maps_Static $MapsStaticService */
  private $mapsStaticService;

  /** @var App_GoogleMaps_Services_Maps_StaticResponder $mapsStaticResponder */
  private $mapsStaticResponder;

  /** @var App_GoogleMaps_Services_Places_Geocoding $placesGeocodingService */
  private $placesGeocodingService;

  /** @var App_GoogleMaps_Services_Places_GeocodingResponder $placesGeocodingResponder */
  private $placesGeocodingResponder;

  /** @var string $apiKey */
  private $apiKey;


  /**
   * Get autocomplete suggestions for given inputs
   * 
   * @param string $input     The characters input by the user
   * @param array  $countries Countries to return suggestions for
   * @param string $sessionId (Optional) Session id for use with autocomplete sessions 
   *
   * @return App_GoogleMaps_Autocomplete
   */
  public function autocomplete(string $input, array $countries, string $sessionId=null) {
    $payload = $this->getPlacesAutocompleteService()
                    ->setInput($input)
                    ->setCountries($countries)
                    ->setSessionId($sessionId)
                    ->setApiKey($this->getApiKey())
                    ->getAutocomplete();

    return $this->getPlacesAutocompleteResponder()->__invoke($payload); 

  }//end autocomplete()


  /**
   * Get a location from a google placeId
   *
   * @param string $placeId   Google place id
   * @param string $sessionId (Optional) Session id for use with autocomplete sessions
   *
   * @return App_GoogleMaps_Location
   */
  public function getLocation(string $placeId, string $sessionId=null) : App_GoogleMaps_Location {
    $payload = $this->getPlacesDetailService()
                    ->setPlaceId($placeId)
                    ->setSessionId($sessionId)
                    ->setApiKey($this->getApiKey())
                    ->getDetails();

    return $this->getPlacesDetailResponder()->__invoke($payload);

  }//end getLocation();


  /**
   * Get a location from an address
   *
   * @param string $address
   *
   * @return App_GoogleMaps_Location
   */
  public function getLocationFromAddress(string $address) : App_GoogleMaps_Location {
    $payload = $this->getPlacesGeocodingService()
                    ->setAddress($address)
                    ->setApiKey($this->getApiKey())
                    ->getLocation();

    return $this->getPlacesGeocodingResponder()->__invoke($payload);

  }//end getLocationFromLatLong()


  /**
   * Get a location from the Latitude and Longitude
   *
   * @param float $latitude
   * @param float $longitude
   *
   * @return App_GoogleMaps_Location
   */
  public function getLocationFromLatLong(float $latitude, float $longitude) : App_GoogleMaps_Location {
    $payload = $this->getPlacesGeocodingService()
                    ->setLatitude($latitude)
                    ->setLongitude($longitude)
                    ->setApiKey($this->getApiKey())
                    ->getLocation();

    return $this->getPlacesGeocodingResponder()->__invoke($payload);

  }//end getLocationFromLatLong()


  /**
   * Get route information from two locations
   *
   * @param App_GoogleMaps_Location $origin
   * @param App_GoogleMaps_Location $destination
   *
   * @return App_GoogleMaps_Route
   */
  public function getRoute(App_GoogleMaps_Location $origin, App_GoogleMaps_Location $destination) : App_GoogleMaps_Route {
    $payload = $this->getRoutesDirectionService()
                    ->setOriginLocation($origin)
                    ->setDestinationLocation($destination)
                    ->setApiKey($this->getApiKey())
                    ->getDirections();

    return $this->getRoutesDirectionResponder()->__invoke($payload);

  }//end getRoute()


  /**
   * Get static map
   *
   * @param App_GoogleMaps_Location $origin
   * @param App_GoogleMaps_Location $destination
   * @param App_GoogleMaps_Route    $route       (Optional)
   *
   * @return App_GoogleMaps_Map
   */
  public function getStaticMap(App_GoogleMaps_Location $origin, App_GoogleMaps_Location $destination, App_GoogleMaps_Route $route=null) : App_GoogleMaps_Map {
    //Get the route if it is not passed as a param
    $route = $route ?? $this->getRoute($origin, $destination);

    $payload = $this->getMapsStaticService()
                    ->setOrigin($origin)
                    ->setDestination($destination)
                    ->setRoute($route)
                    ->setApiKey($this->getApiKey())
                    ->getMap();

    return $this->getMapsStaticResponder()->__invoke($payload);

  }//end getStaticMap()


  /**
   * Returns PlacesDetailService object
   *
   * @return PlacesDetailService
   */
  public function getPlacesDetailService() : PlacesDetailService {
    return $this->placesDetailService;

  }//end getPlacesDetails()


  /**
   * Set PlacesDetailService object
   *
   * @param PlacesDetailService $placesDetailService
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesDetailService(PlacesDetailService $placesDetailService) : App_GoogleMaps_Manager {
    $this->placesDetailService = $placesDetailService;

    return $this;

  }//end setPlacesDetails()


  /**
   * Get the Google Api Key
   *
   * @return string
   */
  public function getApiKey() : string {
    return $this->apiKey;

  }//end getApiKey()


  /**
   * Set the Google Api Key
   *
   * @param string $apiKey
   *
   * @return App_GoogleMaps_Manager
   */
  public function setApiKey(string $apiKey) : App_GoogleMaps_Manager {
    $this->apiKey = $apiKey;

    return $this;

  }//end setApiKey()


  /**
   * Get Place Detyails Responder
   *
   * @return App_GoogleMaps_Services_Places_DetailsResponder
   */
  public function getPlacesDetailResponder() : PlacesDetailResponder {
    return $this->placesDetailResponder;

  }//end getPlacesDetailResponder()


  /**
   * Set google places details responder
   *
   * @param PlacesDetailResponder $responder
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesDetailResponder(PlacesDetailResponder $responder) : App_GoogleMaps_Manager {
    $this->placesDetailResponder = $responder;

    return $this;

  }//end setPlacesDetailResponder()


  /**
   * Returns PlacesAutocompleteService object
   *
   * @return PlacesDetailService
   */
  public function getPlacesAutocompleteService() : PlacesAutocompleteService {
    return $this->placesAutocompleteService;

  }//end getPlacesAutocompleteService()


  /**
   * Set PlacesAutocompleteService object
   *
   * @param PlacesAutocompleteService $placesAutocompleteService
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesAutocompleteService(PlacesAutocompleteService $placesAutocompleteService) : App_GoogleMaps_Manager {
    $this->placesAutocompleteService = $placesAutocompleteService;

    return $this;

  }//end setPlacesAutocompleteService()


  /**
   * Return PlacesAutocompleteResponder object
   *
   * @return PlacesAutocompleteResponder
   */
  public function getPlacesAutocompleteResponder() {
    return $this->placesAutocompleteResponder;

  }//end getPlacesAutocompleteResponder()


  /**
   * Set PlacesAutocompleteResponder
   *
   * @param PlacesAutocompleteResponder $responder
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesAutocompleteResponder(PlacesAutocompleteResponder $responder) : App_GoogleMaps_Manager {
    $this->placesAutocompleteResponder = $responder;

    return $this;

  }//end setPlacesAutocompleteResponder()


  /**
   * Returns RoutesDirections object
   *
   * @return RoutesDirectionService
   */ 
  public function getRoutesDirectionService() : RoutesDirectionService {
    return $this->routesDirectionService;

  }//end getRoutesDirectionService()


  /**
   * Set RoutesDirections object
   *
   * @param RoutesDirectionService $routesDirectionService
   *
   * @return App_GoogleMaps_Manager
   */
  public function setRoutesDirectionService(RoutesDirectionService $routesDirectionService) : App_GoogleMaps_Manager {
    $this->routesDirectionService = $routesDirectionService;

    return $this;

  }//end setRoutesDirectionService()


  /**
   * Returns RoutesDirections responder
   *
   * @return RoutesDirectionResponder
   */ 
  public function getRoutesDirectionResponder() : RoutesDirectionResponder {
    return $this->routesDirectionResponder;

  }//end getRoutesDirectionResponder()


  /**
   * Set RoutesDirections responder object
   *
   * @param RoutesDirectionResponder $routesDirectionResponder
   *
   * @return App_GoogleMaps_Manager
   */
  public function setRoutesDirectionResponder(RoutesDirectionResponder $routesDirectionResponder) : App_GoogleMaps_Manager {
    $this->routesDirectionResponder = $routesDirectionResponder;

    return $this;

  }//end setRoutesDirectionResponder()


  /**
   * Returns the Static Maps service object
   *
   * @return MapsStaticService
   */
  public function getMapsStaticService() {
    return $this->mapsStaticService;

  }//end getMapsStaticService()


  /**
   * Set Maps Static Service object
   *
   * @param MapsStaticService
   *
   * @return App_GoogleMaps_Manager
   */
  public function setMapsStaticService(App_GoogleMaps_Services_Maps_Static $mapsStaticService) : App_GoogleMaps_Manager {
    $this->mapsStaticService = $mapsStaticService;

    return $this;

  }//end setMapsStaticService()


  /**
   * Returns MapStatic Responder object
   *
   * @return MapsStaticResponder
   */
  public function getMapsStaticResponder() {
    return $this->mapsStaticResponder;

  }//end getMapsStaticResponder()


  /**
   * Set Maps Static Responder
   *
   * @param MapsStaticResponder
   *
   * @return App_GoogleMaps_Manager
   */
  public function setMapsStaticResponder(App_GoogleMaps_Services_Maps_StaticResponder $mapsStaticResponder) : App_GoogleMaps_Manager {
    $this->mapsStaticResponder = $mapsStaticResponder;

    return $this;

  }//end setMapsStaticResponder()


  /**
   * Returns Places Geocoding Service
   *
   * @return PlacesGeocodingService
   */
  public function getPlacesGeocodingService() {
    return $this->placesGeocodingService;

  }//end getPlacesGeocodingService()


  /**
   * Set Places Geocoding Service
   *
   * @param PlacesGeocodingService $placesGeocodingService
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesGeocodingService(PlacesGeocodingService $placesGeocodingService) : App_GoogleMaps_Manager {
    $this->placesGeocodingService = $placesGeocodingService;

    return $this;
  
  }//end setPlacesGeocodingService()

  
  /**
   * Returns Places Geocoding Responder
   *
   * @return PlacesGeocodingResponder
   */
  public function getPlacesGeocodingResponder() {
    return $this->placesGeocodingResponder;

  }//end getPlacesGeocodingResponder()


  /**
   * Set Places Geocoding Responder
   *
   * @param PlacesGeocodingResponder $placesGeocodingResponder
   *
   * @return App_GoogleMaps_Manager
   */
  public function setPlacesGeocodingResponder(PlacesGeocodingResponder $placesGeocodingResponder) : App_GoogleMaps_Manager {
    $this->placesGeocodingResponder = $placesGeocodingResponder;

    return $this;
  
  }//end setPlacesGeocodingResponder()


  /**
   * Returns retry decider function for GuzzleHttp calls.
   *
   * @return function
   */
  public static function httpRetryDecider() {
    return function($retries, $request, $response = null, $exception = null) {
      //Limit retries
      if ($retries > self::MAX_RETRIES) {
        return false;
      }

      //Retry connection exceptions
      if ($exception InstanceOf \GuzzleHttp\Exception\ConnectException) {
        return true;
      }

      if ($response) {
        //Retry on server errors
        if ($response->getStatusCode() >= 500) {
          return true;
        }

        //Retry on unknown error from API
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['status'] == Payload::STATUS_UNKNOWN_ERROR) {
          return true;
        }

      }

      // Don't retry as a default
      return false;

    };

  }//end httpRetryDecider()


}//end class
