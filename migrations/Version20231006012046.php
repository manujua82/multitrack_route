<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231006012046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route_address (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, main_order_id INT DEFAULT NULL, position INT NOT NULL, full_address VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, millage DOUBLE PRECISION DEFAULT NULL, eta TIME DEFAULT NULL, INDEX IDX_F51D321934ECB4E6 (route_id), INDEX IDX_F51D321954BD7C4D (main_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE route_address ADD CONSTRAINT FK_F51D321934ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE route_address ADD CONSTRAINT FK_F51D321954BD7C4D FOREIGN KEY (main_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route_address DROP FOREIGN KEY FK_F51D321934ECB4E6');
        $this->addSql('ALTER TABLE route_address DROP FOREIGN KEY FK_F51D321954BD7C4D');
        $this->addSql('DROP TABLE route_address');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
