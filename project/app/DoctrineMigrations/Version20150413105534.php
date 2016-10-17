<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413105534 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE SpecialTypes (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Products ADD special INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Products ADD CONSTRAINT FK_4ACC380C4C6B3FE3 FOREIGN KEY (special) REFERENCES SpecialTypes (id)');
        $this->addSql('CREATE INDEX IDX_4ACC380C4C6B3FE3 ON Products (special)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Products DROP FOREIGN KEY FK_4ACC380C4C6B3FE3');
        $this->addSql('DROP TABLE SpecialTypes');
        $this->addSql('DROP INDEX IDX_4ACC380C4C6B3FE3 ON Products');
        $this->addSql('ALTER TABLE Products DROP special');
    }
}
