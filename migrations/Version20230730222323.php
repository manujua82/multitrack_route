<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230730222323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier ADD company_id INT NOT NULL, ADD created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE carrier ADD CONSTRAINT FK_4739F11C979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
        $this->addSql('CREATE INDEX IDX_4739F11C979B1AD6 ON carrier (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier DROP FOREIGN KEY FK_4739F11C979B1AD6');
        $this->addSql('DROP INDEX IDX_4739F11C979B1AD6 ON carrier');
        $this->addSql('ALTER TABLE carrier DROP company_id, DROP created');
    }
}
