<?php

namespace App\Command;

use App\Service\WeatherUtil;
use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Fetch weather forecast for a specific location by country code and city name',
)]
class WeatherCountryCityCommand extends Command
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        $this->weatherUtil = $weatherUtil;
        $this->locationRepository = $locationRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code of the location')
            ->addArgument('city', InputArgument::REQUIRED, 'City name of the location');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countryCode = $input->getArgument('countryCode');
        $city = $input->getArgument('city');

        $location = $this->locationRepository->findByCountryAndCity($countryCode, $city);

        if (!$location) {
            $io->error(sprintf('Location with country code "%s" and city "%s" not found', $countryCode, $city));
            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));

        foreach ($measurements as $measurement) {
            $io->writeln(sprintf("\t%s: %s°C, %s km/h, %s%%, %s hPa",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getTemperature(),
                $measurement->getWindSpeed(),
                $measurement->getHumidity(),
                $measurement->getPressure()
            ));
        }

        return Command::SUCCESS;
    }
}
