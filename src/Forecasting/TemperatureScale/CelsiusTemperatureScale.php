<?php

namespace App\Forecasting\TemperatureScale;

class CelsiusTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Celsius';
    }

    public function convertToCelsius(number $degrees): number
    {
        return $degrees;
    }

    public function convertFromCelsius(number $degrees): number
    {
        return $degrees;
    }
}
