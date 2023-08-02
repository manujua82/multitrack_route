<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802030443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4E6F8177153098 ON address (code)');
        $this->addSql('ALTER TABLE carrier CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4739F11C77153098 ON carrier (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0977153098 ON customer (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E77153098 ON item (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E48696901F54 ON vehicle (number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_4739F11C77153098 ON carrier');
        $this->addSql('ALTER TABLE carrier CHANGE code code VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_D4E6F8177153098 ON address');
        $this->addSql('ALTER TABLE address CHANGE code code VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_81398E0977153098 ON customer');
        $this->addSql('DROP INDEX UNIQ_1B80E48696901F54 ON vehicle');
        $this->addSql('DROP INDEX UNIQ_1F1B251E77153098 ON item');
    }
}
