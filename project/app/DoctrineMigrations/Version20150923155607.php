<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923155607 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData ADD twitterName VARCHAR(255) DEFAULT NULL, ADD facebookPageLink LONGTEXT DEFAULT NULL, ADD twitterPageLink LONGTEXT DEFAULT NULL, ADD googlePageLink LONGTEXT DEFAULT NULL, DROP googleMapsIframe, DROP twitterWidget, CHANGE logoPath logoPath VARCHAR(255) DEFAULT NULL, CHANGE sweepstakesDesktop sweepstakesDesktop LONGTEXT DEFAULT NULL, CHANGE sweepstakesMobile sweepstakesMobile LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData ADD googleMapsIframe LONGTEXT NOT NULL COLLATE utf8_unicode_ci, ADD twitterWidget LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP twitterName, DROP facebookPageLink, DROP twitterPageLink, DROP googlePageLink, CHANGE logoPath logoPath VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE sweepstakesDesktop sweepstakesDesktop LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE sweepstakesMobile sweepstakesMobile LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
