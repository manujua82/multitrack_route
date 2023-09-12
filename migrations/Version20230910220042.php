<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910220042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route ADD ship_from_id INT NOT NULL');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207976E3E966 FOREIGN KEY (ship_from_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE INDEX IDX_2C4207976E3E966 ON route (ship_from_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207976E3E966');
        $this->addSql('DROP INDEX IDX_2C4207976E3E966 ON route');
        $this->addSql('ALTER TABLE route DROP ship_from_id');
    }
}
