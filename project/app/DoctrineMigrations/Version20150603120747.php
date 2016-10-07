<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150603120747 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReputationCustomers (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, checkout_date DATETIME DEFAULT NULL, sent_date DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, redirect VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6AA10E7D54266CA2 (reputation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationCustomers ADD CONSTRAINT FK_6AA10E7D54266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE Reputations ADD total_customers INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ReputationCustomers');
        $this->addSql('ALTER TABLE Reputations DROP total_customers');
    }
}
