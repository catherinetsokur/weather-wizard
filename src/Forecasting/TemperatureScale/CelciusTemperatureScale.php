<?php

namespace App\Forecasting\TemperatureScale;

class CelciusTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Celcius';
    }

    public function convertToCelcius(number $degrees): number
    {
        return $degrees;
    }

    public function convertFromCelcius(number $degrees): number
    {
        return $degrees;
    }
}
