<?php

namespace App\Service;

use App\Entity\SensorData;

class ChartDataService
{
    /**
     * Prepare chart data from sensor data collection
     * 
     * @param SensorData[] $sensorDataCollection
     * @return array
     */
    public function prepareChartData(array $sensorDataCollection): array
    {
        return [
            'labels' => array_map(
                fn($row) => $row->getMeasuredAt()->format('H:i'),
                array_reverse($sensorDataCollection)
            ),
            'temperature' => array_map(
                fn($row) => $row->getTemperature(),
                array_reverse($sensorDataCollection)
            ),
            'humidity' => array_map(
                fn($row) => $row->getHumidity(),
                array_reverse($sensorDataCollection)
            ),
            'co2' => array_map(
                fn($row) => $row->getCo2(),
                array_reverse($sensorDataCollection)
            ),
            'light' => array_map(
                fn($row) => $row->getLight(),
                array_reverse($sensorDataCollection)
            ),
            'battery' => array_map(
                fn($row) => $row->getBattery(),
                array_reverse($sensorDataCollection)
            ),
            'rssi' => array_map(
                fn($row) => $row->getRssi(),
                array_reverse($sensorDataCollection)
            ),
        ];
    }
}
