<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240514193339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create employee adn working_time tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE employee (uuid BINARY(16) NOT NULL, firstname VARCHAR(32) NOT NULL, lastname VARCHAR(32) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE working_time (uuid BINARY(16) NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL, start_day DATE NOT NULL, id BINARY(16) DEFAULT NULL, INDEX IDX_31EE2ABFBF396750 (id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE working_time ADD CONSTRAINT FK_31EE2ABFBF396750 FOREIGN KEY (id) REFERENCES employee (uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE working_time DROP FOREIGN KEY FK_31EE2ABFBF396750');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE working_time');
    }
}
