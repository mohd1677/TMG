<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150415104627 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Photos (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, original VARCHAR(255) NOT NULL, extra_large VARCHAR(255) NOT NULL, large VARCHAR(255) NOT NULL, medium VARCHAR(255) NOT NULL, small VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, ice_id VARCHAR(255) DEFAULT NULL, ice_updated DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FDAE5EF549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Photos ADD CONSTRAINT FK_FDAE5EF549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Photos');
    }
}
