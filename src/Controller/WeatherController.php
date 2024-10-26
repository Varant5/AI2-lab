<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementDataRepository;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(string $city, Location $location, LocationRepository $locationRepository, WeatherUtil $util): Response 
    {
        $location = $locationRepository->findByCity($city);

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

}
