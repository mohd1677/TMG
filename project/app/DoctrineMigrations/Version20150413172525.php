<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413172525 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE IHGProperties (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, address_id INT DEFAULT NULL, hotel_code VARCHAR(6) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, rate VARCHAR(10) NOT NULL, rate_type VARCHAR(255) NOT NULL, rate_pretty VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, expires DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D7644AC2549213EC (property_id), INDEX IDX_D7644AC2F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE IHGProperties ADD CONSTRAINT FK_D7644AC2549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE IHGProperties ADD CONSTRAINT FK_D7644AC2F5B7AF75 FOREIGN KEY (address_id) REFERENCES Addresses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IHGProperties');
    }
}
