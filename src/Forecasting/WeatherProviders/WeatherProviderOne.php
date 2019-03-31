<?php

namespace App\Forecasting\WeatherProviders;

use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\TemperatureScaleFactory;
use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherProviderOne extends AbstractWeatherProvider
{
    protected function fetchExternalForecast(
        string $city,
        int $day,
        AbstractTemperatureScale $preferredTemperatureScale
    ) : WeatherForecast {
        // Assuming we have made a request and got the following from the third party
        $response = '{
              "predictions": {
                "-scale": "Celcius",
                "city": "Amsterdam",
                "date": "20180112",
                "prediction": [
                  {
                    "time": "00:00",
                    "value": "31"
                  },
                  {
                    "time": "01:00",
                    "value": "32"
                  },
                  {
                    "time": "02:00",
                    "value": "25"
                  },
                  {
                    "time": "03:00",
                    "value": "26"
                  },
                  {
                    "time": "04:00",
                    "value": "20"
                  },
                  {
                    "time": "05:00",
                    "value": "22"
                  },
                  {
                    "time": "06:00",
                    "value": "23"
                  },
                  {
                    "time": "07:00",
                    "value": "22"
                  },
                  {
                    "time": "08:00",
                    "value": "25"
                  },
                  {
                    "time": "09:00",
                    "value": "24"
                  },
                  {
                    "time": "10:00",
                    "value": "24"
                  }
                ]
              }
            }';

        // @TODO: Here would be good to have a validation of the response
        // - if it's a valid json
        // - if it contains all required fields
        // - are all fields in expected format
        // Due to the lack of time I'm assuming that everything is going to be as expected,
        // which almost never happens in real life.

        $responseDecoded = \json_decode($response, true);
        $obtainedScale = TemperatureScaleFactory::getByName($responseDecoded['predictions']['-scale']);
        $hourlyPredictions = [];
        foreach ($responseDecoded['predictions']['prediction'] as $value) {
            $hourlyPredictions[] = [
                'hour' => \intval(\substr($value['time'], 0, 2)),
                'degrees' => \floatval($value['value']),
            ];
        }

        return new WeatherForecast($obtainedScale, $city, $day, $hourlyPredictions);
    }
}
