<?php

namespace App\Forecasting\WeatherProviders;

use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\TemperatureScaleFactory;
use App\Forecasting\TemperatureScale\AbstractTemperatureScale;

class WeatherProviderTwo extends AbstractWeatherProvider
{
    protected function fetchExternalForecast(
        string $city,
        int $day,
        AbstractTemperatureScale $preferredTemperatureScale
    ) : WeatherForecast {
        // Assuming we have made a request and got the following from the third party
        $response = '<?xml version="1.0" encoding="utf-8" ?>
<predictions scale="Celsius">
    <city>Amsterdam</city>
    <date>20180112</date>
    <prediction>
        <time>00:00</time>
        <value>05</value>
    </prediction>
    <prediction>
        <time>01:00</time>
        <value>05</value>
    </prediction>
    <prediction>
        <time>02:00</time>
        <value>06</value>
    </prediction>
    <prediction>
        <time>03:00</time>
        <value>05</value>
    </prediction>
    <prediction>
        <time>04:00</time>
        <value>08</value>
    </prediction>
    <prediction>
        <time>05:00</time>
        <value>05</value>
    </prediction>
    <prediction>
        <time>06:00</time>
        <value>15</value>
    </prediction>
    <prediction>
        <time>07:00</time>
        <value>00</value>
    </prediction>
    <prediction>
        <time>08:00</time>
        <value>01</value>
    </prediction>
    <prediction>
        <time>09:00</time>
        <value>02</value>
    </prediction>
    <prediction>
        <time>10:00</time>
        <value>03</value>
    </prediction>
    <!-- more... -->
</predictions>';

        // @TODO: Here would be good to have a validation of the response
        // - if it's a valid xml
        // - if it contains all required fields
        // - are all fields in expected format
        // Due to the lack of time I'm assuming that everything is going to be as expected,
        // which almost never happens in real life.

        $predictions = new \SimpleXMLElement($response);
        $obtainedScale = TemperatureScaleFactory::getByName((string) $predictions['scale']);
        $hourlyPredictions = [];
        foreach ($predictions->prediction as $prediction) {
            $hourlyPredictions[] = [
                'hour' => \intval(\substr((string) $prediction->time, 0, 2)),
                'degrees' => \floatval((string) $prediction->value),
            ];
        }

        return new WeatherForecast($obtainedScale, $city, $day, $hourlyPredictions);
    }
}
