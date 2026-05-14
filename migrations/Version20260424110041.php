<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424110041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Placeholder - schema already created';
    }

    public function up(Schema $schema): void
    {
        // Already handled by Version20260421135437
    }

    public function down(Schema $schema): void
    {
        // Already handled by Version20260421135437
    }
}