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
     * @param  int    $degrees  Degrees in the current scale
     * @return int              Degrees in Celcius
     */
    abstract public function convertToCelcius(int $degrees): int;

    /**
     * Converts degrees given in Celcius into the current scale.
     * @param  int    $degrees  Degrees in Celcius
     * @return int              Degrees in the current scale
     */
    abstract public function convertFromCelcius(int $degrees): int;

    /**
     * Converts degrees given in the current scale into degrees in the given scale.
     * @param  int                          $degrees Degrees in the current scale
     * @param  AbstractTemperatureScale     $scale   The scale to convert to
     * @return int          Degrees in the given scale
     */
    public function convertToScale(int $degrees, self $scale): int
    {
        // Converting from current scale to given via Celcius
        return $scale->convertFromCelcius($this->convertToCelcius($degrees));
    }
}
