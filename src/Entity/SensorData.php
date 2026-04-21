<?php

namespace App\Entity;

use App\Repository\SensorDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SensorDataRepository::class)]
class SensorData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $deviceName = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?float $humidity = null;

    #[ORM\Column(nullable: true)]
    private ?int $co2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $pirCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $light = null;

    #[ORM\Column(nullable: true)]
    private ?float $battery = null;

    #[ORM\Column(nullable: true)]
    private ?int $rssi = null;

    #[ORM\Column(nullable: true)]
    private ?float $snr = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $measuredAt = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    // =====================
    // GETTERS & SETTERS
    // =====================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceName(): ?string
    {
        return $this->deviceName;
    }

    public function setDeviceName(string $deviceName): self
    {
        $this->deviceName = $deviceName;
        return $this;
    }

    public function getDevEui(): ?string
    {
        return $this->devEui;
    }

    public function setDevEui(string $devEui): self
    {
        $this->devEui = $devEui;
        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(?float $humidity): self
    {
        $this->humidity = $humidity;
        return $this;
    }

    public function getCo2(): ?int
    {
        return $this->co2;
    }

    public function setCo2(?int $co2): self
    {
        $this->co2 = $co2;
        return $this;
    }

    public function getPirCount(): ?int
    {
        return $this->pirCount;
    }

    public function setPirCount(?int $pirCount): self
    {
        $this->pirCount = $pirCount;
        return $this;
    }

    public function getLight(): ?int
    {
        return $this->light;
    }

    public function setLight(?int $light): self
    {
        $this->light = $light;
        return $this;
    }

    public function getBattery(): ?float
    {
        return $this->battery;
    }

    public function setBattery(?float $battery): self
    {
        $this->battery = $battery;
        return $this;
    }

    public function getRssi(): ?int
    {
        return $this->rssi;
    }

    public function setRssi(?int $rssi): self
    {
        $this->rssi = $rssi;
        return $this;
    }

    public function getSnr(): ?float
    {
        return $this->snr;
    }

    public function setSnr(?float $snr): self
    {
        $this->snr = $snr;
        return $this;
    }

    public function getMeasuredAt(): ?\DateTimeInterface
    {
        return $this->measuredAt;
    }

    public function setMeasuredAt(\DateTimeInterface $measuredAt): self
    {
        $this->measuredAt = $measuredAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}