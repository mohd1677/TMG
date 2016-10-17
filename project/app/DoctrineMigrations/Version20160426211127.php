<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160426211127 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationReviews ADD response_reserved_by INT DEFAULT NULL, ADD response_reserved_at DATETIME DEFAULT NULL, ADD response_proposed_at DATETIME DEFAULT NULL, ADD tagged_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ReputationReviews ADD CONSTRAINT FK_ABE503495113C02D FOREIGN KEY (response_reserved_by) REFERENCES Users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABE503495113C02D ON ReputationReviews (response_reserved_by)');
        $this->addSql('CREATE INDEX response_reserved_at ON ReputationReviews (response_reserved_at)');
        $this->addSql('CREATE INDEX response_proposed_at ON ReputationReviews (response_proposed_at)');
        $this->addSql('CREATE INDEX tagged_at ON ReputationReviews (tagged_at)');

        /**
         * this cannot be undone
         * i am seeding the tagged_at date for reviews already resolved
         * the system will set it going forward - bp 2016-04-27
         */
        $this->addSql('UPDATE ReputationReviews 
          SET tagged_at = DATE_ADD(responded_at, INTERVAL -1 HOUR) 
          WHERE responded_at IS NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationReviews DROP FOREIGN KEY FK_ABE503495113C02D');
        $this->addSql('DROP INDEX UNIQ_ABE503495113C02D ON ReputationReviews');
        $this->addSql('DROP INDEX response_reserved_at ON ReputationReviews');
        $this->addSql('DROP INDEX response_proposed_at ON ReputationReviews');
        $this->addSql('DROP INDEX tagged_at ON ReputationReviews');
        $this->addSql('ALTER TABLE ReputationReviews DROP response_reserved_by, DROP response_reserved_at, DROP response_proposed_at, DROP tagged_at');
    }
}
