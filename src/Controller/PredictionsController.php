<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Forecasting\WeatherForecast;

class PredictionsController
{
    /**
     * @Route("/predictions/{city}/today/{temperatureScale}",
     *      name="predictions_today",
     *      requirements={"city"="\w+","temperatureScale"="c|f"})
     */
    public function getByCityToday($city, $temperatureScale)
    {
        $forecast = new WeatherForecast($city, \time(), []);
        return JsonResponse::fromJsonString($forecast->getAsJson());
    }

    /**
     * @Route("/predictions/{city}/{day}/{temperatureScale}",
     *      name="predictions_day",
     *      requirements={"city"="\w+","day"="\d{8}", "temperatureScale"="c|f"})
     */
    public function getByCityAndDay($city, $day, $temperatureScale)
    {
        $requestedDay  = \strtotime($day);
        $startOfTheDay = \strtotime(\date('Ymd 00:00:00'));

        // Only provide predictions within 10 days interval from today
        if ($requestedDay < $startOfTheDay ||  $requestedDay >= \strtotime('+10 days', $startOfTheDay))
        {
            return new Response('Requested day is out of range', 404);
        }

        $forecast = new WeatherForecast($city, $requestedDay, []);
        return JsonResponse::fromJsonString($forecast->getAsJson());
    }
}