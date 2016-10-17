<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150513130148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReputationReviews (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, site INT DEFAULT NULL, engage_id VARCHAR(255) NOT NULL, yrmo INT NOT NULL, post_date DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL, content_short LONGTEXT DEFAULT NULL, content_url VARCHAR(255) DEFAULT NULL, tone NUMERIC(12, 2) NOT NULL, sentiment TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_ABE503496CF7C16 (engage_id), INDEX IDX_ABE5034954266CA2 (reputation_id), INDEX IDX_ABE50349694309E4 (site), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationReviews ADD CONSTRAINT FK_ABE5034954266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE ReputationReviews ADD CONSTRAINT FK_ABE50349694309E4 FOREIGN KEY (site) REFERENCES ReputationSites (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ReputationReviews');
    }
}
