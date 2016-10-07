<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160405191707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX tone ON ReputationReviews (tone)');
        $this->addSql('CREATE INDEX critical ON ReputationReviews (critical)');
        $this->addSql('CREATE INDEX post_date ON ReputationReviews (post_date)');
        $this->addSql('CREATE INDEX created_at ON ReputationReviews (created_at)');
        $this->addSql('CREATE INDEX responded_at ON ReputationReviews (responded_at)');
        $this->addSql('CREATE INDEX approved_at ON ReputationReviews (approved_at)');
        $this->addSql('CREATE INDEX resolved_at ON ReputationReviews (resolved_at)');

        //this cannot be undone
        //Patrick requests to set the response cycle to 24 hours for all back data entered into the resolve system - bp 2016-04-05 09:04:00
        $this->addSql("UPDATE ReputationReviews SET responded_at = DATE_ADD(created_at, INTERVAL 24 HOUR) WHERE responded_at IS NOT NULL AND responded_at <= '2016-04-05 09:04:00'") ;

        //this cannot be undone
        //Patrick requests to set the approval cycle to 24 hours for all back data entered into the resolve system - bp 2016-04-05 09:04:00
        $this->addSql("UPDATE ReputationReviews SET approved_at = DATE_ADD(responded_at, INTERVAL 24 HOUR) WHERE approved_at IS NOT NULL AND responded_at IS NOT NULL AND responded_at <= '2016-04-05 09:04:00'") ;

        //this cannot be undone
        //Patrick requests to set the resolve cycle to 12 hours for all back data entered into the resolve system - bp 2016-04-05 09:04:00
        $this->addSql("UPDATE ReputationReviews SET resolved_at = DATE_ADD(approved_at, INTERVAL 12 HOUR) WHERE resolved_at IS NOT NULL AND approved_at IS NOT NULL AND responded_at <= '2016-04-05 09:04:00'") ;
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX tone ON ReputationReviews');
        $this->addSql('DROP INDEX critical ON ReputationReviews');
        $this->addSql('DROP INDEX post_date ON ReputationReviews');
        $this->addSql('DROP INDEX created_at ON ReputationReviews');
        $this->addSql('DROP INDEX responded_at ON ReputationReviews');
        $this->addSql('DROP INDEX approved_at ON ReputationReviews');
        $this->addSql('DROP INDEX resolved_at ON ReputationReviews');
    }
}
