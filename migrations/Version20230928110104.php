<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928110104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD pickup_customer_id_id INT DEFAULT NULL, ADD pickup_address_id_id INT DEFAULT NULL, ADD pickup_customer_name VARCHAR(255) DEFAULT NULL, ADD pickup_contact_name VARCHAR(255) DEFAULT NULL, ADD pickup_customer_email VARCHAR(255) DEFAULT NULL, ADD pickup_customer_phone VARCHAR(255) DEFAULT NULL, ADD pickup_address_zone VARCHAR(255) DEFAULT NULL, ADD pickup_time_from DATETIME DEFAULT NULL, ADD pickup_time_until DATETIME DEFAULT NULL, ADD pickup_note VARCHAR(255) DEFAULT NULL, ADD pickup_service_time BIGINT DEFAULT NULL, ADD pickup_cod NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398991021EE FOREIGN KEY (pickup_customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993982B93F54F FOREIGN KEY (pickup_address_id_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_F5299398991021EE ON `order` (pickup_customer_id_id)');
        $this->addSql('CREATE INDEX IDX_F52993982B93F54F ON `order` (pickup_address_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398991021EE');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993982B93F54F');
        $this->addSql('DROP INDEX IDX_F5299398991021EE ON `order`');
        $this->addSql('DROP INDEX IDX_F52993982B93F54F ON `order`');
        $this->addSql('ALTER TABLE `order` DROP pickup_customer_id_id, DROP pickup_address_id_id, DROP pickup_customer_name, DROP pickup_contact_name, DROP pickup_customer_email, DROP pickup_customer_phone, DROP pickup_address_zone, DROP pickup_time_from, DROP pickup_time_until, DROP pickup_note, DROP pickup_service_time, DROP pickup_cod');
    }
}
