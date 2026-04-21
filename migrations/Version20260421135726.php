<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260421135726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__sensor_data AS SELECT id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at FROM sensor_data');
        $this->addSql('DROP TABLE sensor_data');
        $this->addSql('CREATE TABLE sensor_data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, device_name VARCHAR(255) NOT NULL, temperature DOUBLE PRECISION DEFAULT NULL, humidity DOUBLE PRECISION DEFAULT NULL, co2 INTEGER DEFAULT NULL, pir_count INTEGER DEFAULT NULL, light INTEGER DEFAULT NULL, battery DOUBLE PRECISION DEFAULT NULL, rssi INTEGER DEFAULT NULL, snr DOUBLE PRECISION DEFAULT NULL, measured_at DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO sensor_data (id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at) SELECT id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at FROM __temp__sensor_data');
        $this->addSql('DROP TABLE __temp__sensor_data');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__sensor_data AS SELECT id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at FROM sensor_data');
        $this->addSql('DROP TABLE sensor_data');
        $this->addSql('CREATE TABLE sensor_data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, device_name VARCHAR(255) NOT NULL, temperature DOUBLE PRECISION DEFAULT NULL, humidity DOUBLE PRECISION DEFAULT NULL, co2 INTEGER DEFAULT NULL, pir_count INTEGER DEFAULT NULL, light INTEGER DEFAULT NULL, battery DOUBLE PRECISION DEFAULT NULL, rssi INTEGER DEFAULT NULL, snr DOUBLE PRECISION DEFAULT NULL, measured_at VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, dev_eui VARCHAR(32) NOT NULL)');
        $this->addSql('INSERT INTO sensor_data (id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at) SELECT id, device_name, temperature, humidity, co2, pir_count, light, battery, rssi, snr, measured_at, created_at FROM __temp__sensor_data');
        $this->addSql('DROP TABLE __temp__sensor_data');
    }
}
