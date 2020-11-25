<?php

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DependencyInjectionServicesConfig implements ServiceProviderInterface
{
    /**
     * @param Container|App_Di_Container $c
     */
    public function register(Container $c)
    {
        /**
         * @param App_Di_Container $c
         * @return App_Config
         */
        $c['Config'] = function (App_Di_Container $c) {
            $appenv = getenv('APPLICATION_ISO')
              ? APPLICATION_ENVIRONMENT.'-'.getenv("APPLICATION_ISO")
              : APPLICATION_ENVIRONMENT;

            $config = new App_Config();
            $config->load(APPLICATION_DIR . '/config/config.ini');
            $config->loadOptional(APPLICATION_DIR . '/config/config.local.ini', $appenv);

            return $config;
        };

        /**
         * @param App_Di_Container
         *
         * @return GuzzleHttp\Client
         */
        $c['GoogleMaps.HttpClient'] = function(App_Di_Container $c) {
          $stack = $c['Guzzle.HandlerStack'];
          $stack->push(GuzzleHttp\Middleware::retry(App_GoogleMaps_Manager::httpRetryDecider()));

          $options = array_merge(
            $c['Guzzle.Defaults'],
            [
              'base_uri' => "https://maps.googleapis.com",
              'handler'  => $stack,
            ]
          );

          return new GuzzleHttp\Client($options);

        };//end GoogleMaps.HttpClient


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Services_Places_Autocomplete
         */
        $c['GoogleMaps.Places.Autocomplete.Service'] = function(App_Di_Container $c) {
          $cache  = $c->get('cache.db');
          $client = $c->get('GoogleMaps.HttpClient');

          return new App_GoogleMaps_Services_Places_Autocomplete($client, $cache);

        };//end GoogleMaps.Places.Autocomplete.Service


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Services_Places_Details
         */
        $c['GoogleMaps.Places.Details.Service'] = function(App_Di_Container $c) {
          $cache  = $c->get('cache.db');
          $client = $c->get('GoogleMaps.HttpClient');

          return new App_GoogleMaps_Services_Places_Details($client, $cache); 

        };//end GoogleMaps.Places.Details.Service


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Services_Places_Geocoding
         */
        $c['GoogleMaps.Places.Geocoding.Service'] = function(App_Di_Container $c) {
          $cache  = $c->get('cache.db');
          $client = $c->get('GoogleMaps.HttpClient');

          return new App_GoogleMaps_Services_Places_Geocoding($client, $cache); 

        };//end GoogleMaps.Places.Geocoding.Service


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Services_Routes_Directions
         */
        $c['GoogleMaps.Routes.Directions.Service'] = function(App_Di_Container $c) {
          $cache  = $c->get('cache.db');
          $client = $c->get('GoogleMaps.HttpClient');

          return new App_GoogleMaps_Services_Routes_Directions($client, $cache); 

        };//end GoogleMaps.Places.Details.Service


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Services_Maps_Static
         */
        $c['GoogleMaps.Maps.Static.Service'] = function(App_Di_Container $c) {
          $cache  = $c->get('cache.db');
          $client = $c->get('GoogleMaps.HttpClient');

          return new App_GoogleMaps_Services_Maps_Static($client, $cache);

        };//end GoogleMaps.Maps.Static.Service


        /**
         * @param App_Di_Container $c
         *
         * @return App_GoogleMaps_Manager
         */
        $c['GoogleMaps.Manager'] = function (App_Di_Container $c) {
          $manager = new App_GoogleMaps_Manager();
          $manager->setApiKey($c->get('Config')->get('googleapikey'))
                  ->setPlacesAutocompleteService($c->get('GoogleMaps.Places.Autocomplete.Service'))
                  ->setPlacesDetailService($c->get('GoogleMaps.Places.Details.Service'))
                  ->setRoutesDirectionService($c->get('GoogleMaps.Routes.Directions.Service'))
                  ->setMapsStaticService($c->get('GoogleMaps.Maps.Static.Service'))
                  ->setPlacesGeocodingService($c->get('GoogleMaps.Places.Geocoding.Service'))
                  ->setPlacesAutocompleteResponder(new App_GoogleMaps_Services_Places_AutocompleteResponder())
                  ->setPlacesDetailResponder(new App_GoogleMaps_Services_Places_DetailsResponder())
                  ->setRoutesDirectionResponder(new App_GoogleMaps_Services_Routes_DirectionsResponder())
                  ->setMapsStaticResponder(new App_GoogleMaps_Services_Maps_StaticResponder())
                  ->setPlacesGeocodingResponder(new App_GoogleMaps_Services_Places_GeocodingResponder());

          return $manager;

        };//end GoogleMaps.Manager

    }
}
