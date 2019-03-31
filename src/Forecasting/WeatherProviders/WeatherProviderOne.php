<?php

namespace App\Forecasting\WeatherProviders;

use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherProviderOne extends AbstractWeatherProvider
{
    protected function fetchExternalForecast(
        string $city,
        int $day,
        AbstractTemperatureScale $preferredTemperatureScale
    ) : WeatherForecast {
        return new WeatherForecast($preferredTemperatureScale, $city, $day, []);
    }
}
