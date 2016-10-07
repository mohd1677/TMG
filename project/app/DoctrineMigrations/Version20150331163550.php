<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150331163550 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ProductTypes (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Products CHANGE type type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Products ADD CONSTRAINT FK_4ACC380C8CDE5729 FOREIGN KEY (type) REFERENCES ProductTypes (id)');
        $this->addSql('CREATE INDEX IDX_4ACC380C8CDE5729 ON Products (type)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Products DROP FOREIGN KEY FK_4ACC380C8CDE5729');
        $this->addSql('DROP TABLE ProductTypes');
        $this->addSql('DROP INDEX IDX_4ACC380C8CDE5729 ON Products');
        $this->addSql('ALTER TABLE Products CHANGE type type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
