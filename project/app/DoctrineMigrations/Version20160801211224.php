<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160801211224 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE resolveSetting_reputationSite');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resolveSetting_reputationSite (resolveSetting_id INT NOT NULL, reputationSite_id INT NOT NULL, INDEX IDX_44DDE4A0CB48DF8D (resolveSetting_id), INDEX IDX_44DDE4A01A8BCCFA (reputationSite_id), PRIMARY KEY(resolveSetting_id, reputationSite_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resolveSetting_reputationSite ADD CONSTRAINT FK_44DDE4A01A8BCCFA FOREIGN KEY (reputationSite_id) REFERENCES ReputationSites (id)');
        $this->addSql('ALTER TABLE resolveSetting_reputationSite ADD CONSTRAINT FK_44DDE4A0CB48DF8D FOREIGN KEY (resolveSetting_id) REFERENCES ResolveSetting (id)');
    }
}
