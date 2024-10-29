<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use App\Entity\MeasurementData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] ?string $city,
        #[MapQueryParameter] ?string $country,
        #[MapQueryParameter] ?string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        if (!$city || !$country) {
            return $this->json(['error' => 'City and country are required parameters.'], 400);
        }

        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            $csvHeader = "city,country,date,celsius,windSpeed,humidity,pressure\n";
            $csvData = array_map(fn(MeasurementData $m) => sprintf(
                "%s,%s,%s,%s,%s,%s,%s",
                $city,
                $country,
                $m->getDate()->format('Y-m-d'),
                $m->getTemperature(),
                $m->getFahrenheit(),
                $m->getWindSpeed(),
                $m->getHumidity(),
                $m->getPressure()
            ), $measurements);
            $csvOutput = $csvHeader . implode("\n", $csvData);

            return new Response($csvOutput, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="weather.csv"',
            ]);
        }

        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        $formattedMeasurements = array_map(fn(MeasurementData $m) => [
            'date' => $m->getDate()->format('Y-m-d'),
            'temperature' => $m->getTemperature(),
            'fahrenheit' => $m->getFahrenheit(),
            'windSpeed' => $m->getWindSpeed(),
            'humidity' => $m->getHumidity(),
            'pressure' => $m->getPressure(),
        ], $measurements);

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => $formattedMeasurements,
        ]);
    }
}
