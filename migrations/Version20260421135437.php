<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260421135437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sensor_data (id SERIAL PRIMARY KEY NOT NULL, device_name VARCHAR(255) NOT NULL, dev_eui VARCHAR(32) NOT NULL, temperature DOUBLE PRECISION DEFAULT NULL, humidity DOUBLE PRECISION DEFAULT NULL, co2 INTEGER DEFAULT NULL, pir_count INTEGER DEFAULT NULL, light INTEGER DEFAULT NULL, battery DOUBLE PRECISION DEFAULT NULL, rssi INTEGER DEFAULT NULL, snr DOUBLE PRECISION DEFAULT NULL, measured_at VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sensor_data');
    }
}
