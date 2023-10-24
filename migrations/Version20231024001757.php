<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024001757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE routing_setup (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, start_time DATETIME NOT NULL, stop_duration INT NOT NULL, start_from_deport TINYINT(1) DEFAULT NULL, end_from_depot TINYINT(1) DEFAULT NULL, driver_home_location TINYINT(1) DEFAULT NULL, cost_per_distance NUMERIC(10, 2) DEFAULT NULL, cost_per_hour NUMERIC(10, 2) DEFAULT NULL, base_fare NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_9A9A4B75979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE routing_setup ADD CONSTRAINT FK_9A9A4B75979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
        $this->addSql('ALTER TABLE main_company DROP route_date_format, DROP route_time_format, DROP route_unit_distance, DROP route_unit_weight, DROP route_unit_volume, DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE routing_setup DROP FOREIGN KEY FK_9A9A4B75979B1AD6');
        $this->addSql('DROP TABLE routing_setup');
        $this->addSql('ALTER TABLE main_company ADD route_date_format VARCHAR(30) DEFAULT NULL, ADD route_time_format VARCHAR(20) DEFAULT NULL, ADD route_unit_distance VARCHAR(30) DEFAULT NULL, ADD route_unit_weight VARCHAR(10) DEFAULT NULL, ADD route_unit_volume VARCHAR(10) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL');
    }
}
