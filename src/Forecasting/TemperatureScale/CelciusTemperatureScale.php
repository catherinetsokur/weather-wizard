<?php

namespace App\Forecasting\TemperatureScale;

class CelciusTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Celcius';
    }

    public function convertToCelcius(int $degrees): int
    {
        return $degrees;
    }

    public function convertFromCelcius(int $degrees): int
    {
        return $degrees;
    }
}
