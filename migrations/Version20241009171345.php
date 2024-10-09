<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241009171345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measurement_data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_location_id INTEGER NOT NULL, wind_speed NUMERIC(3, 0) NOT NULL, humidity NUMERIC(3, 0) NOT NULL, pressure NUMERIC(4, 0) NOT NULL, temperature NUMERIC(3, 0) NOT NULL, CONSTRAINT FK_DE6517321E5FEC79 FOREIGN KEY (id_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DE6517321E5FEC79 ON measurement_data (id_location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE measurement_data');
    }
}
