<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413144702 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CallLogs ADD postal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE CallLogs ADD CONSTRAINT FK_BDFCBE251EF0EA69 FOREIGN KEY (postal_id) REFERENCES PostalCodes (id)');
        $this->addSql('CREATE INDEX IDX_BDFCBE251EF0EA69 ON CallLogs (postal_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CallLogs DROP FOREIGN KEY FK_BDFCBE251EF0EA69');
        $this->addSql('DROP INDEX IDX_BDFCBE251EF0EA69 ON CallLogs');
        $this->addSql('ALTER TABLE CallLogs DROP postal_id');
    }
}
