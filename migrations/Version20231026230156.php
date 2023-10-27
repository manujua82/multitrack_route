<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026230156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_company ADD route_date_format VARCHAR(30) DEFAULT NULL, ADD route_time_format VARCHAR(20) DEFAULT NULL, ADD route_unit_distance VARCHAR(30) DEFAULT NULL, ADD route_unit_weight VARCHAR(10) DEFAULT NULL, ADD route_unit_volume VARCHAR(10) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_company DROP route_date_format, DROP route_time_format, DROP route_unit_distance, DROP route_unit_weight, DROP route_unit_volume, DROP photo');
    }
}
