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
     * Converts degrees given in the current scale into Celsius.
     * @param  float    $degrees  Degrees in the current scale
     * @return float              Degrees in Celsius
     */
    abstract public function convertToCelsius(float $degrees): float;

    /**
     * Converts degrees given in Celsius into the current scale.
     * @param  float    $degrees  Degrees in Celsius
     * @return float              Degrees in the current scale
     */
    abstract public function convertFromCelsius(float $degrees): float;

    /**
     * Converts degrees given in the current scale into degrees in the given scale.
     * @param  float                          $degrees Degrees in the current scale
     * @param  AbstractTemperatureScale     $scale   The scale to convert to
     * @return float          Degrees in the given scale
     */
    public function convertToScale(float $degrees, self $scale): float
    {
        // Converting from current scale to given via Celsius
        return $scale->convertFromCelsius($this->convertToCelsius($degrees));
    }
}
