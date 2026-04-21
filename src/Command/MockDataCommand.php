<?php

namespace App\Command;

use App\Entity\SensorData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:mock-data')]
class MockDataCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $devices = [
            ["name" => "LivingRoom", "devEui" => "a81758fffe054167"],
            ["name" => "Bedroom", "devEui" => "b81758fffe054168"],
        ];

        foreach ($devices as $device) {
            $data = new SensorData();

            $data->setDeviceName($device["name"]);
            $data->setDevEui($device["devEui"]);

            $data->setTemperature(rand(180, 250) / 10);
            $data->setHumidity(rand(30, 70));
            $data->setCo2(rand(400, 1500));
            $data->setPirCount(rand(0, 10));
            $data->setLight(rand(0, 100));
            $data->setBattery(rand(300, 370) / 100);
            $data->setRssi(rand(-90, -30));
            $data->setSnr(rand(0, 100) / 10);

            $data->setMeasuredAt(new \DateTime());
            $data->setCreatedAt(new \DateTime());

            $this->em->persist($data);
        }

        $this->em->flush();

        $output->writeln("🔥 Mock data toegevoegd!");

        return Command::SUCCESS;
    }
}