<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240514172218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create employee table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE employee (uuid CHAR(36) NOT NULL, firstname VARCHAR(32) NOT NULL, lastname VARCHAR(32) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE employee');
    }
}
