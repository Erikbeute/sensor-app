<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SensorDataRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SensorData;
use Symfony\Component\HttpFoundation\Request;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(SensorDataRepository $repo): Response
    {
        $data = $repo->findBy([], ['measuredAt' => 'DESC'], 50);

        return $this->render('dashboard/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/sensor-data/{id}', name: 'sensor_getbyid')]
    public function findSensorData(int $id, SensorDataRepository $repo): Response
    {
        $sensordata = $repo->find($id);
        
        if (!$sensordata) {
            throw $this->createNotFoundException('Sensor not found');
        }

        $allSensorData = $repo->findBy(
            ['deviceName' => $sensordata->getDeviceName()], 
            ['measuredAt' => 'DESC']
        ); 


        return $this->render('dashboard/sensor_detail.html.twig', ['sensordata' => $sensordata, 'allSensorData' => $allSensorData]);
    }

    #[Route('/api/sensor-data', name: 'sensor_save', methods: ['POST'])]
    public function saveData(Request $request, SensorDataRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $sensorData = new SensorData();
        $sensorData->setDevEui($data['devEui'] ?? null);
        $sensorData->setDeviceName($data['deviceName'] ?? null);
        $sensorData->setBattery($data['battery'] ?? null);
        $sensorData->setTemperature($data['temperature'] ?? null);
        $sensorData->setHumidity($data['humidity'] ?? null);
        $sensorData->setLight($data['light'] ?? null);
        $sensorData->setPirCount($data['pirCount'] ?? null);
        $sensorData->setSnr($data['snr'] ?? null);
        $sensorData->setCo2($data['co2'] ?? null);
        $sensorData->setRssi($data['rssi'] ?? null);
        $sensorData->setCreatedAt(new \DateTime());

        $sensorData->setMeasuredAt(
            isset($data['measuredAt'])
            ? new \DateTime($data['measuredAt'])
            : new \DateTime()
        );

        $repo->save($sensorData, true);

        return new JsonResponse(['status' => 'saved']);
    }
}
