<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160802205808 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ResolveSettingSites (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, resolveSetting_id INT DEFAULT NULL, reputationSite_id INT DEFAULT NULL, INDEX IDX_F74C77F5CB48DF8D (resolveSetting_id), INDEX IDX_F74C77F51A8BCCFA (reputationSite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ResolveSettingSites ADD CONSTRAINT FK_F74C77F5CB48DF8D FOREIGN KEY (resolveSetting_id) REFERENCES ResolveSetting (id)');
        $this->addSql('ALTER TABLE ResolveSettingSites ADD CONSTRAINT FK_F74C77F51A8BCCFA FOREIGN KEY (reputationSite_id) REFERENCES ReputationSites (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ResolveSettingSites');
    }
}
