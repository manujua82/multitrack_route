<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025230936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_setup (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, document_type VARCHAR(255) NOT NULL, document_status VARCHAR(255) NOT NULL, email_subject VARCHAR(255) DEFAULT NULL, email_body LONGTEXT DEFAULT NULL, INDEX IDX_E3A9B3C9979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_setup ADD CONSTRAINT FK_E3A9B3C9979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_setup DROP FOREIGN KEY FK_E3A9B3C9979B1AD6');
        $this->addSql('DROP TABLE notification_setup');
    }
}
