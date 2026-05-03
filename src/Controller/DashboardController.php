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

final class DashboardController extends AbstractController
{
    public function __construct(
        private ChartDataService $chartDataService
    ) {}

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
            ['deviceName' => $sensordata->getDeviceName()], 
            ['measuredAt' => 'DESC']
        );

        $chartData = $this->chartDataService->prepareChartData($allSensorData);
        $template = $this->getTemplate($request);
        $templateData = $this->getTemplateData($sensordata, $allSensorData, $chartData, $template);

        return $this->render($template, $templateData);
    }

    private function getTemplate(Request $request): string
    {
        return $request->headers->get('HX-Request')
            ? 'dashboard/sensor_data_fragment.html.twig'
            : 'dashboard/sensor_detail.html.twig';
    }

    private function getTemplateData(SensorData $sensordata, array $allSensorData, array $chartData, string $template): array
    {
        $baseData = [
            'allData' => $allSensorData,
            'chartData' => $chartData
        ];

        if ($template === 'dashboard/sensor_detail.html.twig') {
            $baseData['sensordata'] = $sensordata;
        }

        return $baseData;
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
