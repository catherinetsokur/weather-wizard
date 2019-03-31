<?php

namespace App\Forecasting\TemperatureScale;

class FahrenheitTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Fahrenheit';
    }

    public function convertToCelsius(number $degrees): number
    {
        return ($degrees - 32) * 5 / 9;
    }

    public function convertFromCelsius(number $degrees): number
    {
        return $degrees * 9 / 5 + 32;
    }
}
