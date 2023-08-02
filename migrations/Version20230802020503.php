<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802020503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD company_id INT NOT NULL, ADD created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E979B1AD6 ON item (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E979B1AD6');
        $this->addSql('DROP INDEX IDX_1F1B251E979B1AD6 ON item');
        $this->addSql('ALTER TABLE item DROP company_id, DROP created');
    }
}
