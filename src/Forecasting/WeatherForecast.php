<?php

namespace App\Forecasting;

use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherForecast
{
    /**
     * City, for which the forecast is made.
     * @var [type]
     */
    protected $city;

    /**
     * Day of the forecast (in timestamp).
     * @var int
     */
    protected $day;

    /**
     * Array of hourly weather predictions
     * each element is an array with at least 2 properties:
     * - hour int from 0 to 23
     * - degrees number (can be negative).
     * @var array
     */
    protected $hourlyPredictions;

    /**
     * Current forecast temperature scale.
     * @var AbstractTemperatureScale
     */
    protected $temperatureScale;

    /**
     * @param AbstractTemperatureScale $temperatureScale  Current forecast temperature scale
     * @param string                   $city              City for which the forecast is made
     * @param int                      $day               Day of the forecast (in timestamp)
     * @param array                    $hourlyPredictions Array of hourly weather predictions
     *                                                    each element is an array with at least 2 properties:
     *                                                    - hour int from 0 to 23
     *                                                    - degrees number (can be negative).
     */
    public function __construct(AbstractTemperatureScale $temperatureScale, string $city, int $day, array $hourlyPredictions)
    {
        $this->temperatureScale = $temperatureScale;
        $this->city = $city;
        $this->day = $day;
        $this->hourlyPredictions = $hourlyPredictions;
    }

    /**
     * Checks if the forecast temperature scale is the same as the supplied one.
     * @param  AbstractTemperatureScale $scale
     * @return bool
     */
    public function hasSameTemperatureScale(AbstractTemperatureScale $scale)
    {
        return \get_class($this->temperatureScale) === \get_class($scale);
    }

    /**
     * Converts the forecast into the given temperature scale.
     * @param  AbstractTemperatureScale $scale
     * @return void
     */
    public function convertToTemperatureScale(AbstractTemperatureScale $scale)
    {
        \array_walk($this->hourlyPredictions, function (&$hourlyPrediction) use ($scale) {
            $hourlyPrediction['degrees'] = $this->temperatureScale->convertToScale($hourlyPrediction['degrees'], $scale);
        });
        $this->temperatureScale = $scale;
    }

    /**
     * Returns hourly predictions.
     * @return array
     */
    public function getHourlyPredictions()
    {
        return $this->hourlyPredictions;
    }

    /**
     * Sets hourly predictions.
     * @param array $hourlyPredictions See construct
     */
    public function setHourlyPredictions(array $hourlyPredictions)
    {
        $this->hourlyPredictions = $hourlyPredictions;
    }

    /**
     * Returns forecast information in a form of JSON string.
     * @return string JSON
     */
    public function getAsJson()
    {
        return \json_encode([
            'city' => $this->city,
            'day' => \date('Ymd', $this->day),
            'scale' => $this->temperatureScale->getName(),
            'hourlyPredictions' => $this->hourlyPredictions,
        ]);
    }
}
