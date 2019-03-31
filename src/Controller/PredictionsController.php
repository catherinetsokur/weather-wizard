<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Forecasting\WeatherWizard;
use App\Forecasting\TemperatureScale\TemperatureScaleFactory;
use App\Forecasting\WeatherProviders\WeatherProvidersPool;
use App\Forecasting\ForecastNotFoundException;

class PredictionsController
{
    /**
     * @Route("/predictions/{city}/today/{temperatureScaleName}",
     *      name="predictions_today",
     *      requirements={"city"="\w+","temperatureScaleName"="celcius|fahrenheit"})
     */
    public function getByCityToday($city, $temperatureScaleName)
    {
        try {
            $wizard = new WeatherWizard(
                TemperatureScaleFactory::getByName($temperatureScaleName),
                WeatherProvidersPool::getAllAvailable()
            );
            $forecast = $wizard->predictForCityAndDay($city, \time());
        } catch (ForecastNotFoundException $e) {
            return new Response('Wizard cannot predict this forecast because: '.$e->getMessage(), 404);
        } catch (\Exception $e) {
            return new Response('Something mysterious has happened... '.$e->getMessage(), 500);
        }

        return JsonResponse::fromJsonString($forecast->getAsJson());
    }

    /**
     * @Route("/predictions/{city}/{day}/{temperatureScaleName}",
     *      name="predictions_day",
     *      requirements={"city"="\w+","day"="\d{8}", "temperatureScaleName"="celcius|fahrenheit"})
     */
    public function getByCityAndDay($city, $day, $temperatureScaleName)
    {
        try {
            $requestedDay = \strtotime($day);
            $startOfTheDay = \strtotime(\date('Ymd 00:00:00'));

            // Only provide predictions within 10 days interval from today
            if ($requestedDay < $startOfTheDay || $requestedDay >= \strtotime('+10 days', $startOfTheDay)) {
                throw new ForecastNotFoundException('Requested day is out of range');
            }

            $wizard = new WeatherWizard(
                TemperatureScaleFactory::getByName($temperatureScaleName),
                WeatherProvidersPool::getAllAvailable()
            );
            $forecast = $wizard->predictForCityAndDay($city, $requestedDay);
        } catch (ForecastNotFoundException $e) {
            return new Response('Wizard cannot predict this forecast because: '.$e->getMessage(), 404);
        } catch (\Exception $e) {
            return new Response('Something mysterious has happened... '.$e->getMessage(), 500);
        }

        return JsonResponse::fromJsonString($forecast->getAsJson());
    }
}
