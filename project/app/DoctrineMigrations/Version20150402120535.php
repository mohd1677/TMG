<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150402120535 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Contracts (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, product_id INT DEFAULT NULL, property_id INT DEFAULT NULL, rep_id INT DEFAULT NULL, current_active TINYINT(1) NOT NULL, space_reserved VARCHAR(255) DEFAULT NULL, collection_message VARCHAR(255) DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, start_issue INT NOT NULL, end_issue INT NOT NULL, color VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, email_copy TINYINT(1) NOT NULL, fax_copy TINYINT(1) NOT NULL, feed_status VARCHAR(10) NOT NULL, order_number VARCHAR(255) NOT NULL, master_order_number VARCHAR(255) DEFAULT NULL, master_order_account VARCHAR(255) DEFAULT NULL, master_order_e1_account VARCHAR(255) DEFAULT NULL, feed_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8BA12BD016A2B381 (book_id), INDEX IDX_8BA12BD04584665A (product_id), INDEX IDX_8BA12BD0549213EC (property_id), INDEX IDX_8BA12BD054C549EA (rep_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Contracts ADD CONSTRAINT FK_8BA12BD016A2B381 FOREIGN KEY (book_id) REFERENCES Books (id)');
        $this->addSql('ALTER TABLE Contracts ADD CONSTRAINT FK_8BA12BD04584665A FOREIGN KEY (product_id) REFERENCES Products (id)');
        $this->addSql('ALTER TABLE Contracts ADD CONSTRAINT FK_8BA12BD0549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Contracts ADD CONSTRAINT FK_8BA12BD054C549EA FOREIGN KEY (rep_id) REFERENCES SalesReps (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Contracts');
    }
}
