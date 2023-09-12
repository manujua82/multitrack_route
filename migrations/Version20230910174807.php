<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910174807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, vehicle_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, date DATETIME NOT NULL, time TIME NOT NULL, start_from_depot TINYINT(1) NOT NULL, end_at_depot TINYINT(1) NOT NULL, INDEX IDX_2C42079C3423909 (driver_id), INDEX IDX_2C42079545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE `order` ADD route_id INT DEFAULT NULL, ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939834ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('CREATE INDEX IDX_F529939834ECB4E6 ON `order` (route_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939834ECB4E6');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079C3423909');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079545317D1');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP INDEX IDX_F529939834ECB4E6 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP route_id, DROP status');
    }
}
