<?php
/**
 * Astro widget script
 * Version 1.0.3 [2016-11-23]
 *
 * @author jpg
 * @link http://jpg.priv.no/scripts/astro/
 *
 * Licence: MIT
 * @link https://opensource.org/licenses/MIT
 *
 * Meteorologisk institutt
 * Data from api.met.no are licensed under CC BY 3.0.
 * @link http://creativecommons.org/licenses/by/3.0
 * @link http://api.met.no/license_data.html
 * @link http://api.met.no/weatherapi/sunrise/1.1/documentation
 *
 * Google Maps
 * @link https://developers.google.com/maps/documentation/geocoding/intro
 * @link https://developers.google.com/maps/documentation/geocoding/usage-limits
 * @link https://developers.google.com/maps/documentation/geocoding/get-api-key
 */

/**
 * Geocode lookup
 *
 * @param  array $parameters - geocode API parameters
 * @return array
 */
function astro_api_geocode($parameters)
{
    $debug = false;
    $api = 'http://maps.google.com/maps/api/geocode/xml?';
    foreach ($parameters as $key => $value)
        $api .= $key . '=' . $value . ';';
    $api = substr($api, 0, -1);
    if ($debug) echo $api . "<br>";
    $GeocodeResponse = simplexml_load_file($api);
    $geocode['lat'] = $GeocodeResponse->result->geometry->location->lat;
    $geocode['lon'] = $GeocodeResponse->result->geometry->location->lng;
    $geocode['name'] = $GeocodeResponse->result->address_component[0]->long_name;
    return $geocode;
}

/**
 * Sunrise lookup
 *
 * @param  array $parameters - sunrise API parameters
 * @return array
 */
function astro_api_sunrise($parameters)
{
    $debug = false;
    $api = 'http://api.met.no/weatherapi/sunrise/1.1/?';
    foreach ($parameters as $key => $value)
        $api .= $key . '=' . $value . ';';
    $api = substr($api, 0, -1);
    if ($debug) echo $api . "<br>";
    $astrodata = simplexml_load_file($api);
    $sunrise = array();
    foreach ($astrodata->time as $time) {
        $current = (string)$time['date'];
// Sunrise and sunset
        $sunrise[$current]['sun']['never_rise'] = ($time->location->sun['never_rise'] == 'true') ? true : false;
        $sunrise[$current]['sun']['rise'] = (isset($time->location->sun['rise']) ? date('H:i', strtotime($time->location->sun['rise'])) : null);
        $sunrise[$current]['sun']['never_set'] = ($time->location->sun['never_set'] == 'true') ? true : false;
        $sunrise[$current]['sun']['set'] = (isset($time->location->sun['set']) ? date('H:i', strtotime($time->location->sun['set'])) : null);
// Moonrise and moonset
        $sunrise[$current]['moon']['never_rise'] = ($time->location->moon['never_rise'] == 'true') ? true : false;
        $sunrise[$current]['moon']['rise'] = (isset($time->location->moon['rise']) ? date('H:i', strtotime($time->location->moon['rise'])) : null);
        $sunrise[$current]['moon']['never_set'] = ($time->location->moon['never_set'] == 'true') ? true : false;
        $sunrise[$current]['moon']['set'] = (isset($time->location->moon['set']) ? date('H:i', strtotime($time->location->moon['set'])) : null);
// Moon phase
        $sunrise[$current]['moon']['phase'] = $time->location->moon['phase'];
        switch ($sunrise[$current]['moon']['phase']) {
            case 'New moon':
                $sunrise[$current]['moon']['phase_no'] = 'nym&aring;ne';
                break;
            case 'Waxing crescent':
                $sunrise[$current]['moon']['phase_no'] = 'voksende nym&aring;ne';
                break;
            case 'First quarter':
                $sunrise[$current]['moon']['phase_no'] = '1/4 m&aring;ne';
                break;
            case 'Waxing gibbous':
                $sunrise[$current]['moon']['phase_no'] = 'voksende halvm&aring;ne';
                break;
            case 'Full moon':
                $sunrise[$current]['moon']['phase_no'] = 'fullm&aring;ne';
                break;
            case 'Waning gibbous':
                $sunrise[$current]['moon']['phase_no'] = 'avtagende fullm&aring;ne';
                break;
            case 'Third quarter':
                $sunrise[$current]['moon']['phase_no'] = '3/4 m&aring;ne';
                break;
            case 'Waning crescent':
                $sunrise[$current]['moon']['phase_no'] = 'avtagende halvm&aring;ne';
                break;
            default:
                $sunrise[$current]['moon']['phase_no'] = $sunrise[$current]['moon']['phase'];
        }
    }
    return $sunrise;
}
