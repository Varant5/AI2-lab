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
    name: 'weather:location',
    description: 'Fetch weather forecast for a specific location by ID',
)]
class WeatherLocationCommand extends Command
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
            ->addArgument('id', InputArgument::REQUIRED, 'ID of the location to fetch the weather for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $locationId = $input->getArgument('id');

        $location = $this->locationRepository->find($locationId);

        if (!$location) {
            $io->error(sprintf('Location with ID %d not found', $locationId));
            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s', $location->getCity()));

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
