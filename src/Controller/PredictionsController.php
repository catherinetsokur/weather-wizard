<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PredictionsController
{
    /**
     * @Route("/predictions/{city}/today/{temperatureScale}",
     *      name="predictions_today",
     *      requirements={"city"="\w+","temperatureScale"="c|f"})
     */
    public function getByCityToday($city, $temperatureScale)
    {
        return new Response('Forecast was requested for ' . $city . ' for today in ' . $temperatureScale);
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

        return new Response('Forecast was requested for ' . $city . ' for ' . $day . ' in ' . $temperatureScale);
    }
}