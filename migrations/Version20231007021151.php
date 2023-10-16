<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231007021151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route_address DROP FOREIGN KEY FK_F51D321954BD7C4D');
        $this->addSql('DROP INDEX IDX_F51D321954BD7C4D ON route_address');
        $this->addSql('ALTER TABLE route_address ADD order_ids LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', DROP main_order_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route_address ADD main_order_id INT DEFAULT NULL, DROP order_ids');
        $this->addSql('ALTER TABLE route_address ADD CONSTRAINT FK_F51D321954BD7C4D FOREIGN KEY (main_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_F51D321954BD7C4D ON route_address (main_order_id)');
    }
}
