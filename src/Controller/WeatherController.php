<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementDataRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather')]
    public function city(string $city, Location $location, LocationRepository $locationRepository, MeasurementDataRepository $repository): Response 
    {
        $location = $locationRepository->findByCity($city);

        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

}
