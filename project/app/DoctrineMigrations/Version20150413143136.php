<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413143136 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Addresses ADD postal_id INT DEFAULT NULL, ADD country_id INT DEFAULT NULL, DROP postal_code, DROP country');
        $this->addSql('ALTER TABLE Addresses ADD CONSTRAINT FK_ED3BF7B51EF0EA69 FOREIGN KEY (postal_id) REFERENCES PostalCodes (id)');
        $this->addSql('ALTER TABLE Addresses ADD CONSTRAINT FK_ED3BF7B5F92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
        $this->addSql('CREATE INDEX IDX_ED3BF7B51EF0EA69 ON Addresses (postal_id)');
        $this->addSql('CREATE INDEX IDX_ED3BF7B5F92F3E70 ON Addresses (country_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Addresses DROP FOREIGN KEY FK_ED3BF7B51EF0EA69');
        $this->addSql('ALTER TABLE Addresses DROP FOREIGN KEY FK_ED3BF7B5F92F3E70');
        $this->addSql('DROP INDEX IDX_ED3BF7B51EF0EA69 ON Addresses');
        $this->addSql('DROP INDEX IDX_ED3BF7B5F92F3E70 ON Addresses');
        $this->addSql('ALTER TABLE Addresses ADD postal_code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD country VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP postal_id, DROP country_id');
    }
}
