<?php

namespace App\Forecasting;

use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherWizard
{
    /**
     * @var AbstractTemperatureScale
     */
    protected $temperatureScale;

    /**
     * List of the weather providers to get forecast from.
     * @var AbstractWeatherProvider[]
     */
    protected $weatherProviders;

    /**
     * @param AbstractTemperatureScale $temperatureScale
     * @param AbstractWeatherProvider[] $weatherProviders
     */
    public function __construct(AbstractTemperatureScale $temperatureScale, array $weatherProviders)
    {
        $this->temperatureScale = $temperatureScale;
        $this->weatherProviders = $weatherProviders;
    }

    /**
     * Makes forecast more correct applying "magic" and secret self-learning algorythms
     * (or maybe just average for now).
     * @param  WeatherForecast[]  $forecasts List of forecasts obtained from all available weather providers
     * @return WeatherForecast              One perfect forecast
     */
    protected function boostForecast(array $forecasts): WeatherForecast
    {
        if (! $forecasts) {
            throw new ForecastNotFoundException('There are no available forecasts for performing magic');
        }

        // Applying magic algorythms here
        $hourlyPredictionsCollection = [];
        // First get all hourly forecasts and aggregate them by hour
        foreach ($forecasts as $forecast) {     // @var $forecast WeatherForecast
            $hourlyPredictions = $forecast->getHourlyPredictions();

            foreach ($hourlyPredictions as $prediction) {
                $predictionHour = $prediction['hour'];
                if (! isset($hourlyPredictionsCollection[$predictionHour])) {
                    $hourlyPredictionsCollection[$predictionHour] = [];
                }
                $hourlyPredictionsCollection[$predictionHour][] = $prediction['degrees'];
            }
        }

        // Average our aggregated data
        $boostedPredictions = [];
        foreach ($hourlyPredictionsCollection as $hour => $degreesList) {
            if (! empty($degreesList)) {
                $boostedPredictions[] = [
                    'hour' => $hour,
                    'degrees' => \array_sum($degreesList) / \count($degreesList),
                ];
            }
        }

        $finalForecast = clone $forecasts[0];
        $finalForecast->setHourlyPredictions($boostedPredictions);

        return $finalForecast;
    }

    /**
     * Makes a weather prediction for given city and day.
     * @param  string $city
     * @param  int    $day
     * @return WeatherForecast Weather prediction in the unified format
     */
    public function predictForCityAndDay(string $city, int $day): WeatherForecast
    {
        // Try to get forecast from cache
        // (later)

        // If forecast is expired - request fresh forecast from weather providers
        if (! $this->weatherProviders) {
            throw new ForecastNotFoundException('There are no available weather providers');
        }

        $forecasts = [];
        foreach ($this->weatherProviders as $provider) {    // @var $provider AbstractWeatherProvider
            $forecasts[] = $provider->getForecast($city, $day, $this->temperatureScale);
        }

        // Improve the forecast
        $forecast = $this->boostForecast($forecasts);

        // Save into cache with the defined TTL
        // (later)

        return $forecast;
    }
}
