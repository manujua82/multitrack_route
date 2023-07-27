<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726222320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier DROP FOREIGN KEY FK_4739F11C545317D1');
        $this->addSql('DROP INDEX IDX_4739F11C545317D1 ON carrier');
        $this->addSql('ALTER TABLE carrier DROP vehicle_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier ADD vehicle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carrier ADD CONSTRAINT FK_4739F11C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_4739F11C545317D1 ON carrier (vehicle_id)');
    }
}
