<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504130406 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReputationData (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, yrmo INT NOT NULL, external_average_rating NUMERIC(12, 2) DEFAULT NULL, external_total INT DEFAULT NULL, external_stars LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', external_positive INT DEFAULT NULL, trip_advisor_rating NUMERIC(12, 2) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DE96B14454266CA2 (reputation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationData ADD CONSTRAINT FK_DE96B14454266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ReputationData');
    }
}
