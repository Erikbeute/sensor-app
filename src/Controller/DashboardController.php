<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SensorDataRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SensorData;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ChartDataService;
use App\Service\SensorViewService;
use Doctrine\ORM\EntityManagerInterface; 

final class DashboardController extends AbstractController
{
    public function __construct(
        private ChartDataService $chartDataService,
        private SensorViewService $sensorViewService
    ) {
    }

    #[Route('/', name: 'dashboard')]
    public function index(SensorDataRepository $repo): Response
    {
        $data = $repo->findBy([], ['measuredAt' => 'DESC'], 50);

        return $this->render('dashboard/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/dashboard/fragment', name: 'dashboard_fragment')]
    public function dashboardFragment(SensorDataRepository $repo): Response
    {
        $data = $repo->findBy([], ['measuredAt' => 'DESC'], 50);

        return $this->render('dashboard/index_fragment.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/sensor-data/{id}', name: 'sensor_getbyid')]
    public function findSensorData(int $id, SensorDataRepository $repo, Request $request): Response
    {
        $sensordata = $repo->find($id);

        if (!$sensordata) {
            throw $this->createNotFoundException('Sensor not found');
        }

        $allSensorData = $repo->findBy(
            ['devEui' => $sensordata->getDevEui()],
            ['measuredAt' => 'DESC']
        );

        $chartData = $this->chartDataService->prepareChartData($allSensorData);
        $template = $this->sensorViewService->getTemplate($request);
        $templateData = $this->sensorViewService->getTemplateData($sensordata, $allSensorData, $chartData, $template);

        return $this->render($template, $templateData);
    }

    #[Route('/api/sensor-data/{id}', name: 'sensor_update', methods: ['PUT'])]
    public function updateDeviceName( int $id, Request $request, SensorDataRepository $repo, EntityManagerInterface $em ): JsonResponse 
    {
        $sensorToUpdate = $repo->find($id);

        if (!$sensorToUpdate) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $sensorToUpdate->setDeviceName($data['deviceName']);
        $em->flush(); 

        return new JsonResponse(['status' => 'updated']);
    }

    #[Route('/api/sensor-data', name: 'sensor_save', methods: ['POST'])]
    public function saveData(Request $request, SensorDataRepository $repo, EntityManagerInterface $em ): JsonResponse {
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

        $em->persist($sensorData);
        $em->flush();

        return new JsonResponse(['status' => 'saved']);
    }
}