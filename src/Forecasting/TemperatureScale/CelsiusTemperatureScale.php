<?php

namespace App\Forecasting\TemperatureScale;

class CelsiusTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Celsius';
    }

    public function convertToCelsius(float $degrees): float
    {
        return $degrees;
    }

    public function convertFromCelsius(float $degrees): float
    {
        return $degrees;
    }
}
