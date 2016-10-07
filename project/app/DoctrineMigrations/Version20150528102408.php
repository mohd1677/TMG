<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150528102408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReputationCompetitors (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, address_id INT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, lifetime_rating NUMERIC(12, 2) DEFAULT NULL, lifetime_reviews INT DEFAULT NULL, city_rank INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4D3F0BFC54266CA2 (reputation_id), INDEX IDX_4D3F0BFCF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReputationCompetitorData (id INT AUTO_INCREMENT NOT NULL, competitor_id INT DEFAULT NULL, site INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, rss_id INT DEFAULT NULL, yrmo INT NOT NULL, rating NUMERIC(12, 2) DEFAULT NULL, reviews INT DEFAULT NULL, city_rank INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_43022B2E78A5D405 (competitor_id), INDEX IDX_43022B2E694309E4 (site), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationCompetitors ADD CONSTRAINT FK_4D3F0BFC54266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE ReputationCompetitors ADD CONSTRAINT FK_4D3F0BFCF5B7AF75 FOREIGN KEY (address_id) REFERENCES Addresses (id)');
        $this->addSql('ALTER TABLE ReputationCompetitorData ADD CONSTRAINT FK_43022B2E78A5D405 FOREIGN KEY (competitor_id) REFERENCES ReputationCompetitors (id)');
        $this->addSql('ALTER TABLE ReputationCompetitorData ADD CONSTRAINT FK_43022B2E694309E4 FOREIGN KEY (site) REFERENCES ReputationSites (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationCompetitorData DROP FOREIGN KEY FK_43022B2E78A5D405');
        $this->addSql('DROP TABLE ReputationCompetitors');
        $this->addSql('DROP TABLE ReputationCompetitorData');
    }
}
