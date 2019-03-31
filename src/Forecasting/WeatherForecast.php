<?php

namespace App\Forecasting;

use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherForecast
{
    protected $city;

    protected $day;

    protected $predictions;

    /**
     * @var AbstractTemperatureScale
     */
    protected $temperatureScale;

    public function __construct(AbstractTemperatureScale $temperatureScale, string $city, int $day, array $predictions)
    {
        $this->temperatureScale = $temperatureScale;
        $this->city = $city;
        $this->day = $day;
        $this->predictions = $predictions;
    }

    public function isSameTemperatureScale(AbstractTemperatureScale $scale)
    {
        return \get_class($this->temperatureScale) === \get_class($scale);
    }

    public function convertToTemperatureScale(AbstractTemperatureScale $scale)
    {
        \array_walk($this->predictions, function (&$prediction) use ($scale) {
            $prediction['degrees'] = $this->temperatureScale->convertToScale($prediction['degrees'], $scale);
        });
        $this->temperatureScale = $scale;
    }

    public function getAsJson()
    {
        return \json_encode([
            'city' => $this->city,
            'day' => \date('Ymd', $this->day),
            'scale' => $this->temperatureScale->getName(),
            'predictions' => $this->predictions,
        ]);
    }
}
