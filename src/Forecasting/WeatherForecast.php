<?php
namespace App\Forecasting;

class WeatherForecast
{
    protected $city;
    protected $day;
    protected $predictions;

    function __construct(string $city, int $day, array $predictions)
    {
        $this->city = $city;
        $this->day = $day;
        $this->predictions = $predictions;
    }

    public function getAsJson()
    {
        return \json_encode([
            'city' => $this->city,
            'day'  => \date('Ymd', $this->day),
            'predictions' => $this->predictions
        ]);
    }
}