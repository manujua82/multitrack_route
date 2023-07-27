<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726222022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, depot_id INT NOT NULL, number VARCHAR(255) NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, volume DOUBLE PRECISION DEFAULT NULL, plt DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_1B80E486C3423909 (driver_id), INDEX IDX_1B80E4868510D4DE (depot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4868510D4DE FOREIGN KEY (depot_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE carrier ADD vehicle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carrier ADD CONSTRAINT FK_4739F11C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_4739F11C545317D1 ON carrier (vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier DROP FOREIGN KEY FK_4739F11C545317D1');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C3423909');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4868510D4DE');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP INDEX IDX_4739F11C545317D1 ON carrier');
        $this->addSql('ALTER TABLE carrier DROP vehicle_id');
    }
}
