<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504123442 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Reputations ADD external_average_rating NUMERIC(12, 2) DEFAULT NULL, ADD external_total INT DEFAULT NULL, ADD external_stars LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD external_positive INT DEFAULT NULL, ADD trip_advisor_rating NUMERIC(12, 2) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Reputations DROP external_average_rating, DROP external_total, DROP external_stars, DROP external_positive, DROP trip_advisor_rating');
    }
}
