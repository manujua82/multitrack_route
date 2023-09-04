<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818193900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, ship_from_id INT DEFAULT NULL, shipper_id INT DEFAULT NULL, customer_id_id INT DEFAULT NULL, address_id_id INT DEFAULT NULL, company_id INT NOT NULL, number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date DATETIME NOT NULL, barcode VARCHAR(255) DEFAULT NULL, customer_name VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, customer_email VARCHAR(255) DEFAULT NULL, customer_phone VARCHAR(255) DEFAULT NULL, address_zone VARCHAR(255) NOT NULL, time_from TIME DEFAULT NULL, time_until TIME NOT NULL, note VARCHAR(255) DEFAULT NULL, service_time BIGINT DEFAULT NULL, cod NUMERIC(10, 2) DEFAULT NULL, priority VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, weight NUMERIC(20, 5) DEFAULT NULL, volume NUMERIC(20, 5) DEFAULT NULL, pkg NUMERIC(20, 5) DEFAULT NULL, INDEX IDX_F529939876E3E966 (ship_from_id), INDEX IDX_F529939838459F23 (shipper_id), INDEX IDX_F5299398B171EB6C (customer_id_id), INDEX IDX_F529939848E1E977 (address_id_id), INDEX IDX_F5299398979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, main_order_id INT NOT NULL, item_id INT NOT NULL, unit_measure VARCHAR(255) DEFAULT NULL, qty INT NOT NULL, price NUMERIC(20, 5) NOT NULL, total_amount NUMERIC(20, 5) NOT NULL, INDEX IDX_52EA1F0954BD7C4D (main_order_id), INDEX IDX_52EA1F09126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939876E3E966 FOREIGN KEY (ship_from_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939838459F23 FOREIGN KEY (shipper_id) REFERENCES shipper (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939848E1E977 FOREIGN KEY (address_id_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398979B1AD6 FOREIGN KEY (company_id) REFERENCES main_company (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0954BD7C4D FOREIGN KEY (main_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939876E3E966');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939838459F23');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B171EB6C');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939848E1E977');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398979B1AD6');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0954BD7C4D');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09126F525E');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
    }
}
