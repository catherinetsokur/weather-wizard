<?php

namespace App\Forecasting;

use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherWizard
{
    /**
     * @var AbstractTemperatureScale
     */
    protected $temperatureScale;

    public function __construct(AbstractTemperatureScale $temperatureScale)
    {
        $this->temperatureScale = $temperatureScale;
    }

    /**
     * Makes a weather prediction for given city and day.
     * @param  string $city
     * @param  int    $day
     * @return WeatherForecast Weather prediction in the unified format
     */
    public function predictForCityAndDay(string $city, int $day): WeatherForecast
    {
        return new WeatherForecast($this->temperatureScale, $city, $day, []);
    }
}
