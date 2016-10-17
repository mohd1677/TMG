<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160501213014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationReviews DROP FOREIGN KEY FK_ABE503495113C02D');
        $this->addSql('DROP INDEX response_reserved_at ON ReputationReviews');
        $this->addSql('DROP INDEX response_proposed_at ON ReputationReviews');
        $this->addSql('DROP INDEX IDX_ABE503495113C02D ON ReputationReviews');
        $this->addSql('ALTER TABLE ReputationReviews ADD reserved_at DATETIME DEFAULT NULL, ADD proposed_at DATETIME DEFAULT NULL, DROP response_reserved_at, DROP response_proposed_at, CHANGE response_reserved_by reserved_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ReputationReviews ADD CONSTRAINT FK_ABE50349F1CADAA2 FOREIGN KEY (reserved_by) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_ABE50349F1CADAA2 ON ReputationReviews (reserved_by)');
        $this->addSql('CREATE INDEX reserved_at ON ReputationReviews (reserved_at)');
        $this->addSql('CREATE INDEX proposed_at ON ReputationReviews (proposed_at)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationReviews DROP FOREIGN KEY FK_ABE50349F1CADAA2');
        $this->addSql('DROP INDEX IDX_ABE50349F1CADAA2 ON ReputationReviews');
        $this->addSql('DROP INDEX reserved_at ON ReputationReviews');
        $this->addSql('DROP INDEX proposed_at ON ReputationReviews');
        $this->addSql('ALTER TABLE ReputationReviews ADD response_reserved_at DATETIME DEFAULT NULL, ADD response_proposed_at DATETIME DEFAULT NULL, DROP reserved_at, DROP proposed_at, CHANGE reserved_by response_reserved_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ReputationReviews ADD CONSTRAINT FK_ABE503495113C02D FOREIGN KEY (response_reserved_by) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX response_reserved_at ON ReputationReviews (response_reserved_at)');
        $this->addSql('CREATE INDEX response_proposed_at ON ReputationReviews (response_proposed_at)');
        $this->addSql('CREATE INDEX IDX_ABE503495113C02D ON ReputationReviews (response_reserved_by)');
    }
}
