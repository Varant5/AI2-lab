<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\MeasurementData;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class WeatherUtil
{
    private LocationRepository $locationRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(LocationRepository $locationRepository, EntityManagerInterface $entityManager)
    {
        $this->locationRepository = $locationRepository;
        $this->entityManager = $entityManager;
    }

    public function getWeatherForLocation(Location $location): array
    {
        $measurementRepository = $this->entityManager->getRepository(MeasurementData::class);

        return $measurementRepository->findBy(['location' => $location]);
    }

    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if (!$location) {
            throw new \Exception("Location not found for city: $city, country: $countryCode");
        }

        return $this->getWeatherForLocation($location);
    }
}
