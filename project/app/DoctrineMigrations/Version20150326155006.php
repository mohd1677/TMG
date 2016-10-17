<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150326155006 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Addresses (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, line_1 VARCHAR(255) NOT NULL, line_2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, hash VARCHAR(255) DEFAULT NULL, latitude NUMERIC(14, 10) DEFAULT NULL, longitude NUMERIC(14, 10) DEFAULT NULL, interstate_number VARCHAR(255) NOT NULL, interstate_exit VARCHAR(255) NOT NULL, display_interstate_exit VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_ED3BF7B5D1B862B8 (hash), INDEX IDX_ED3BF7B55D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Addresses ADD CONSTRAINT FK_ED3BF7B55D83CC1 FOREIGN KEY (state_id) REFERENCES States (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Addresses');
    }
}
