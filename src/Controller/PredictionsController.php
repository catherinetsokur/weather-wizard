<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Forecasting\WeatherForecast;
use App\Forecasting\TemperatureScale\TemperatureScaleFactory;

class PredictionsController
{
    /**
     * @Route("/predictions/{city}/today/{temperatureScaleName}",
     *      name="predictions_today",
     *      requirements={"city"="\w+","temperatureScaleName"="celcius|fahrenheit"})
     */
    public function getByCityToday($city, $temperatureScaleName)
    {
        $forecast = new WeatherForecast(TemperatureScaleFactory::getByName($temperatureScaleName), $city, \time(), []);

        return JsonResponse::fromJsonString($forecast->getAsJson());
    }

    /**
     * @Route("/predictions/{city}/{day}/{temperatureScaleName}",
     *      name="predictions_day",
     *      requirements={"city"="\w+","day"="\d{8}", "temperatureScaleName"="celcius|fahrenheit"})
     */
    public function getByCityAndDay($city, $day, $temperatureScaleName)
    {
        $requestedDay = \strtotime($day);
        $startOfTheDay = \strtotime(\date('Ymd 00:00:00'));

        // Only provide predictions within 10 days interval from today
        if ($requestedDay < $startOfTheDay || $requestedDay >= \strtotime('+10 days', $startOfTheDay))
        {
            return new Response('Requested day is out of range', 404);
        }

        $forecast = new WeatherForecast(TemperatureScaleFactory::getByName($temperatureScaleName), $city, $requestedDay, []);

        return JsonResponse::fromJsonString($forecast->getAsJson());
    }
}
