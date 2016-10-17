<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150406151207 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Confirmations (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, confirmed_by INT DEFAULT NULL, confirmed_issue INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8757C56B2576E0FD (contract_id), UNIQUE INDEX UNIQ_8757C56BFB3F81CB (confirmed_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Confirmations ADD CONSTRAINT FK_8757C56B2576E0FD FOREIGN KEY (contract_id) REFERENCES Contracts (id)');
        $this->addSql('ALTER TABLE Confirmations ADD CONSTRAINT FK_8757C56BFB3F81CB FOREIGN KEY (confirmed_by) REFERENCES Users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Confirmations');
    }
}
