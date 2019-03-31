<?php

namespace App\Forecasting\TemperatureScale;

class FahrenheitTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Fahrenheit';
    }

    public function convertToCelsius(float $degrees): float
    {
        return ($degrees - 32) * 5 / 9;
    }

    public function convertFromCelsius(float $degrees): float
    {
        return $degrees * 9 / 5 + 32;
    }
}
