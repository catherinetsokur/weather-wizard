<?php

namespace App\Forecasting;

use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherForecast
{
    protected $city;

    protected $day;

    protected $hourlyPredictions;

    /**
     * @var AbstractTemperatureScale
     */
    protected $temperatureScale;

    public function __construct(AbstractTemperatureScale $temperatureScale, string $city, int $day, array $hourlyPredictions)
    {
        $this->temperatureScale = $temperatureScale;
        $this->city = $city;
        $this->day = $day;
        $this->hourlyPredictions = $hourlyPredictions;
    }

    public function hasSameTemperatureScale(AbstractTemperatureScale $scale)
    {
        return \get_class($this->temperatureScale) === \get_class($scale);
    }

    public function convertToTemperatureScale(AbstractTemperatureScale $scale)
    {
        \array_walk($this->hourlyPredictions, function (&$hourlyPrediction) use ($scale) {
            $hourlyPrediction['degrees'] = $this->temperatureScale->convertToScale($hourlyPrediction['degrees'], $scale);
        });
        $this->temperatureScale = $scale;
    }

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
