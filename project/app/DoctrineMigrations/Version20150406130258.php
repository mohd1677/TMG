<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150406130258 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Analytics (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, device INT DEFAULT NULL, report_date DATETIME NOT NULL, online_rate_clicks INT NOT NULL, coupon_views INT NOT NULL, featured_ad_clicks INT NOT NULL, detail_views INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6833642B549213EC (property_id), INDEX IDX_6833642B92FB68E (device), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Analytics ADD CONSTRAINT FK_6833642B549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Analytics ADD CONSTRAINT FK_6833642B92FB68E FOREIGN KEY (device) REFERENCES DeviceTypes (id)');
        $this->addSql('ALTER TABLE Activities DROP INDEX UNIQ_FAACAC35549213EC, ADD INDEX IDX_FAACAC35549213EC (property_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Analytics');
        $this->addSql('ALTER TABLE Activities DROP INDEX IDX_FAACAC35549213EC, ADD UNIQUE INDEX UNIQ_FAACAC35549213EC (property_id)');
    }
}
