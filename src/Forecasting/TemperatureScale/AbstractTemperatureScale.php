<?php

namespace App\Forecasting\TemperatureScale;

abstract class AbstractTemperatureScale
{
    /**
     * Returns the name of the temperature scale.
     * @return string
     */
    abstract public function getName();

    /**
     * Converts degrees given in the current scale into Celcius.
     * @param  number    $degrees  Degrees in the current scale
     * @return number              Degrees in Celcius
     */
    abstract public function convertToCelcius(number $degrees): number;

    /**
     * Converts degrees given in Celcius into the current scale.
     * @param  number    $degrees  Degrees in Celcius
     * @return number              Degrees in the current scale
     */
    abstract public function convertFromCelcius(number $degrees): number;

    /**
     * Converts degrees given in the current scale into degrees in the given scale.
     * @param  number                          $degrees Degrees in the current scale
     * @param  AbstractTemperatureScale     $scale   The scale to convert to
     * @return number          Degrees in the given scale
     */
    public function convertToScale(number $degrees, self $scale): number
    {
        // Converting from current scale to given via Celcius
        return $scale->convertFromCelcius($this->convertToCelcius($degrees));
    }
}
