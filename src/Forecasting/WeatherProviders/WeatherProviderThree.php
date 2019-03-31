<?php

namespace App\Forecasting\WeatherProviders;

use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\TemperatureScaleFactory;
use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherProviderThree extends AbstractWeatherProvider
{
    protected function fetchExternalForecast(
        string $city,
        int $day,
        AbstractTemperatureScale $preferredTemperatureScale
    ) : WeatherForecast {
        // Assuming we have made a request and got the following from the third party
        $response = '"-scale","city","date","prediction__time","prediction__value"
"celsius","Amsterdam","20180112","00:00","05"
"","","","01:00","05"
"","","","02:00","06"
"","","","03:00","05"
"","","","04:00","08"
"","","","05:00","05"
"","","","06:00","15"
"","","","07:00","00"
"","","","08:00","01"
"","","","09:00","02"
"","","","10:00","03"';

        // @TODO: Here would be good to have a validation of the response
        // - if it's a valid csv
        // - if it contains all required fields
        // - are all fields in expected format
        // Due to the lack of time I'm assuming that everything is going to be as expected,
        // which almost never happens in real life.

        $parsedResponse = \array_map('str_getcsv', \explode("\n", $response));
        $obtainedScale = TemperatureScaleFactory::getByName($parsedResponse[1][0]);
        \array_splice($parsedResponse, 0, 2);

        $hourlyPredictions = [];
        foreach ($parsedResponse as $row) {
            $hourlyPredictions[] = [
                'hour' => \intval(\substr($row[3], 0, 2)),
                'degrees' => \floatval($row[4]),
            ];
        }

        return new WeatherForecast($obtainedScale, $city, $day, $hourlyPredictions);
    }
}
