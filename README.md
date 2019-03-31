# weather-wizard
Weather forecasting service.
The service gets weather forecasts from certain weather providers, and creates a more reliable forecast based on the obtained data.

## Diagrams
### Sequence
![Weather Wizard Sequence Diagram](https://github.com/catherinetsokur/weather-wizard/blob/master/docs/WeatherWizard-Sequence.png "Weather Wizard Sequence Diagram")

### Class
![Weather Wizard Class Diagram](https://github.com/catherinetsokur/weather-wizard/blob/master/docs/WeatherWizard-Class.png "Weather Wizard Class Diagram")

## Notes
This project has a room for improvements
* Validation and errors handling are very light and need more work, especially in points of "receiving" data from the third parties
* Cache is implemented via Symfony's simple FilesystemCache. I'd personally chose for Redis in the other circumstances
* There are no unit tests or other tests for this project. Points for attention: conversion from one tempperature scale to another and "magic" averaging algorythm.
* Manual testing also did not cover some corner cases.

## Installation
Before you start, make sure that you have [Symfony set up](https://symfony.com/doc/current/setup.html).
To install the service and run it locally you will need to execute following commands.
```
git clone git@github.com:catherinetsokur/weather-wizard.git
cd weather-wizard
composer install
php bin/console server:run
```

## Usage
At the moment, service does not have the UI and only provides 2 API endpoints:
* `/predictions/<city>/today/<temperature_scale>` returns the weather forecast for the requested **city** **today** in the requested **temperature scale**
* `/predictions/<city>/<day>/<temperature_scale>` returns the weather forecast for the requested **city** on the **given day** in the requested **temperature scale**

### Parameters
| Parameter | Type | Example | Notes |
| --------- | ---- | ------- | ----- |
| **city**  | string | *Amsterdam* | Case insensitive|
| **day**  | string, YYYYmmdd | 20190401 | The day of the forecast should be within the interval from today to 10 days forward |
| **temperature_scale**  | string | *celsius* | Case sensitive, possible values: *celsius*, *fahrenheit*|

### Examples
Example of requests, you can make from your console:
```
curl GET http://127.0.0.1:8000/predictions/amsterdam/today/celsius
curl GET http://127.0.0.1:8000/predictions/amsterdam/20190405/fahrenheit
```

Example response:
```
{
	"city": "amsterdam",
	"day": "20190405",
	"scale": "Fahrenheit",
	"hourlyPredictions": [{
		"hour": 0,
		"degrees": 36
	}, {
		"hour": 1,
		"degrees": 38
	}, {
		"hour": 2,
		"degrees": 36.86666666666667
	}, {
		"hour": 3,
		"degrees": 36
	}, {
		"hour": 4,
		"degrees": 37.6
	}, {
		"hour": 5,
		"degrees": 34.666666666666664
	}, {
		"hour": 6,
		"degrees": 47
	}, {
		"hour": 7,
		"degrees": 28.666666666666668
	}, {
		"hour": 8,
		"degrees": 30.866666666666664
	}, {
		"hour": 9,
		"degrees": 31.733333333333334
	}, {
		"hour": 10,
		"degrees": 32.93333333333333
	}]
}
```
