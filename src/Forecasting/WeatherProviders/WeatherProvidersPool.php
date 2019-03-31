<?php

namespace App\Forecasting\WeatherProviders;

use Symfony\Component\Finder\Finder;

class WeatherProvidersPool
{
    /**
     * Pool of the available weather providers.
     * @var AbstractWeatherProvider[]
     */
    protected static $weatherProviders = null;

    /**
     * Loads all available weather providers into the pool
     * (optimisation to not search them all each time we need them).
     * @return void
     */
    protected static function loadAll()
    {
        static::$weatherProviders = [];

        // All weather providers should be located in the current directory and have a name WeatherProvider<NAME>.php
        $finder = new Finder();
        $finder
            ->files()
            ->in(__DIR__)
            ->name('WeatherProvider*.php')
            ->notName('AbstractWeatherProvider.php');

        if (! $finder->hasResults()) {
            return;
        }

        foreach ($finder as $file) {
            $weatherProviderClassName = __NAMESPACE__.'\\'.substr($file->getFilename(), 0, -4);
            $provider = new $weatherProviderClassName();
            if (\is_a($provider, __NAMESPACE__.'\\AbstractWeatherProvider')) {
                static::$weatherProviders[] = $provider;
            }
        }
    }

    /**
     * Gets the list of all available weather providers.
     * @return AbstractWeatherProvider[] List of weather providers
     */
    public static function getAllAvailable()
    {
        if (static::$weatherProviders === null) {
            static::loadAll();
        }

        return static::$weatherProviders;
    }
}
