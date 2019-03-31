<?php

namespace App\Forecasting\TemperatureScale;

class FahrenheitTemperatureScale extends AbstractTemperatureScale
{
    public function getName(): string
    {
        return 'Fahrenheit';
    }

    public function convertToCelcius(int $degrees): int
    {
        return ($degrees - 32) * 5 / 9;
    }

    public function convertFromCelcius(int $degrees): int
    {
        return $degrees * 9 / 5 + 32;
    }
}
