<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504143033 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReputationSiteData (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, site INT DEFAULT NULL, yrmo INT DEFAULT NULL, lifetime TINYINT(1) DEFAULT NULL, review_count INT DEFAULT NULL, average_rating NUMERIC(12, 2) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2B4A05CA54266CA2 (reputation_id), INDEX IDX_2B4A05CA694309E4 (site), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationSiteData ADD CONSTRAINT FK_2B4A05CA54266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE ReputationSiteData ADD CONSTRAINT FK_2B4A05CA694309E4 FOREIGN KEY (site) REFERENCES ReputationSites (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ReputationSiteData');
    }
}
