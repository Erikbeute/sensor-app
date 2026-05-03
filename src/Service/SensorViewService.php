<?php

namespace App\Service;

use App\Entity\SensorData;
use Symfony\Component\HttpFoundation\Request;

class SensorViewService
{
    /**
     * Determine which template to render based on request type
     */
    public function getTemplate(Request $request): string
    {
        return $request->headers->get('HX-Request')
            ? 'dashboard/sensor_data_fragment.html.twig'
            : 'dashboard/sensor_detail.html.twig';
    }

    /**
     * Build template data based on template type
     */
    public function getTemplateData(
        SensorData $sensordata,
        array $allSensorData,
        array $chartData,
        string $template
    ): array {
        $baseData = [
            'allData' => $allSensorData,
            'chartData' => $chartData
        ];

        if ($template === 'dashboard/sensor_detail.html.twig') {
            $baseData['sensordata'] = $sensordata;
        }

        return $baseData;
    }
}
