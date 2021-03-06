<?php
/**
 * OpenWeatherMap-PHP-API — An php api to parse weather data from http://www.OpenWeatherMap.org .
 *
 * @license MIT
 *
 * Please see the LICENSE file distributed with this source code for further
 * information regarding copyright and licensing.
 *
 * Please visit the following links to read about the usage policies and the license of OpenWeatherMap before using this class.
 * @see http://www.OpenWeatherMap.org
 * @see http://www.OpenWeatherMap.org/about
 * @see http://www.OpenWeatherMap.org/copyright
 * @see http://openweathermap.org/appid
 */

namespace Cmfcmf\OpenWeatherMap;

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Util\City;

/**
 * Weather class returned by Cmfcmf\OpenWeatherMap->getWeather().
 *
 * @see Cmfcmf\OpenWeatherMap::getWeather() The function using it.
 */
class WeatherForecast implements \Iterator
{
    /**
     * A city object.
     *
     * @var Util\City
     */
    public $city;

    /**
     * The time of the last update of this weather data.
     *
     * @var \DateTime
     */
    public $lastUpdate;

    /**
     * An array of {@link WeatherForecast} objects.
     *
     * @var array
     *
     * @see WeatherForecast The WeatherForecast class.
     */
    private $forecasts;

    /**
     * @internal
     */
    private $position = 0;

    /**
     * Create a new Forecast object.
     *
     * @param        $xml
     * @param string $units
     *
     * @internal
     */
    public function __construct($xml, $units)
    {
        $this->city = new City(-1, $xml->location->name, $xml->location->location['longitude'], $xml->location->location['latitude'], $xml->location->country);
        $this->lastUpdate = new \DateTime($xml->meta->lastupdate);

        foreach ($xml->forecast->time as $time) {
            $forecast = new Forecast($time, $units);
            $forecast->city = $this->city;
            $this->forecasts[] = $forecast;
        }
    }

    /**
     * @internal
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @internal
     */
    public function current()
    {
        return $this->forecasts[$this->position];
    }

    /**
     * @internal
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @internal
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @internal
     */
    public function valid()
    {
        return isset($this->forecasts[$this->position]);
    }
}
