<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150919194545 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData CHANGE googleMapsIframe googleMapsIframe LONGTEXT DEFAULT NULL, CHANGE twitterWidget twitterWidget LONGTEXT DEFAULT NULL, CHANGE logoPath logoPath VARCHAR(255) DEFAULT NULL, CHANGE sweepstakesDesktop sweepstakesDesktop LONGTEXT DEFAULT NULL, CHANGE sweepstakesMobile sweepstakesMobile LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData CHANGE googleMapsIframe googleMapsIframe LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE twitterWidget twitterWidget LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE logoPath logoPath VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE sweepstakesDesktop sweepstakesDesktop LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE sweepstakesMobile sweepstakesMobile LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
