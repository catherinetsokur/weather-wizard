<?php

namespace App\Forecasting\TemperatureScale;

class TemperatureScaleFactory
{
    /**
     * Creates appropriate temperature scale object by name.
     * @param  string $temperatureScaleName Temperature scale name, case insensitive
     * @return AbstractTemperatureScale
     */
    public static function getByName(string $temperatureScaleName): AbstractTemperatureScale
    {
        $temperatureScaleClassName = __NAMESPACE__.'\\'.
            \ucfirst(\strtolower($temperatureScaleName)).'TemperatureScale';

        return new $temperatureScaleClassName();
    }
}
