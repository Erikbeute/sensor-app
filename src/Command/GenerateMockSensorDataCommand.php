<?php

namespace App\Command;

use App\Entity\SensorData;
use App\Repository\SensorDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-mock-data',
    description: 'Generates mock sensor data per 15 minutes for at least 2 hours per sensor',
)]
class GenerateMockSensorDataCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Generating mock sensor data...');

        $sensors = [
            ['name' => 'LivingRoom', 'devEui' => '70B3D57ED0062A5C'],
            ['name' => 'Bedroom', 'devEui' => '70B3D57ED0062A5D'],
        ];

        // Generate data for 2 hours (120 minutes) with 15-minute intervals
        $intervals = 8; // 120 / 15 = 8 intervals
        $now = new \DateTime();

        foreach ($sensors as $sensor) {
            for ($i = 0; $i < $intervals; $i++) {
                $measuredAt = clone $now;
                $measuredAt->modify('-' . ($intervals - $i - 1) * 15 . ' minutes');

                $sensorData = new SensorData();
                $sensorData->setDeviceName($sensor['name']);
                $sensorData->setDevEui($sensor['devEui']);
                $sensorData->setTemperature(rand(15, 28) + rand(0, 99) / 100);
                $sensorData->setHumidity(rand(30, 80) + rand(0, 99) / 100);
                $sensorData->setCo2(rand(400, 1500));
                $sensorData->setPirCount(rand(0, 20));
                $sensorData->setLight(rand(0, 500));
                $sensorData->setBattery(3.0 + rand(0, 50) / 100);
                $sensorData->setRssi(rand(-95, -40));
                $sensorData->setSnr(rand(0, 15) + rand(0, 99) / 100);
                $sensorData->setMeasuredAt($measuredAt);
                $sensorData->setCreatedAt(new \DateTime());

                $this->em->persist($sensorData);
            }
        }

        $this->em->flush();

        $output->writeln('<info>Mock data generated successfully!</info>');
        $output->writeln('Created 16 records (8 per sensor, 15-minute intervals, 2 hours span)');

        return Command::SUCCESS;
    }
}
