<?php

namespace App\Forecasting\WeatherProviders;

use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

abstract class AbstractWeatherProvider
{
    /**
     * Makes (hypothetically speaking) a request to the external weather server to fetch the forecast.
     * @param  string                   $city
     * @param  int                      $day
     * @param  AbstractTemperatureScale $preferredTemperatureScale We can specify the desired
     *                                                             temperature scale but we cannot guarantee
     *                                                             that all external partners support
     *                                                             many scales or scale choice.
     * @return WeatherForecast                                     Weather forecast in a unified format
     */
    abstract protected function fetchExternalForecast(
        string $city,
        int $day,
        AbstractTemperatureScale $preferredTemperatureScale
    ) : WeatherForecast;

    /**
     * Gets the weather forecast supplied by this partner in the unified format.
     * @param  string                   $city
     * @param  int                      $day
     * @param  AbstractTemperatureScale $temperatureScale
     * @return WeatherForecast                              Weather forecast in a unified format
     */
    public function getForecast(string $city, int $day, AbstractTemperatureScale $temperatureScale): WeatherForecast
    {
        $forecast = $this->fetchExternalForecast($city, $day, $temperatureScale);

        // It can happen that temperature scale is not supported or we cannot specify our preferences
        // when requesting the weather forecast. In this case, we need to check the obtained
        // temperature scale and perform the conversion is needed.
        if (! $forecast->hasSameTemperatureScale($temperatureScale)) {
            $forecast->convertToTemperatureScale($temperatureScale);
        }

        return $forecast;
    }
}
