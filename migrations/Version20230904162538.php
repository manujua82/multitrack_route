<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904162538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE correlatives (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, document_type VARCHAR(255) NOT NULL, prefix VARCHAR(255) NOT NULL, length SMALLINT NOT NULL, last_used INT NOT NULL, created DATETIME NOT NULL, INDEX IDX_17EFA52E979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE correlatives ADD CONSTRAINT FK_17EFA52E979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE correlatives DROP FOREIGN KEY FK_17EFA52E979B1AD6');
        $this->addSql('DROP TABLE correlatives');
    }
}
