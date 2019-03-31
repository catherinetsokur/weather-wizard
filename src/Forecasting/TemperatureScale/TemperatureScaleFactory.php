<?php

namespace App\Forecasting\TemperatureScale;

class TemperatureScaleFactory
{
    public static function getByName(string $temperatureScaleName): AbstractTemperatureScale
    {
        $temperatureScaleClassName = __NAMESPACE__.'\\'.
            \ucfirst(\strtolower($temperatureScaleName)).'TemperatureScale';

        return new $temperatureScaleClassName();
    }
}
