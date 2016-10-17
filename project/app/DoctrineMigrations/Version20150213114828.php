<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150213114828 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Client DROP FOREIGN KEY FK_C0E80163A76ED395');
        $this->addSql('DROP INDEX IDX_C0E80163A76ED395 ON Client');
        $this->addSql('ALTER TABLE Client DROP user_id, DROP application_name, DROP homepage_url, DROP description, DROP createdAt, DROP updatedAt');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Client ADD user_id INT DEFAULT NULL, ADD application_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD homepage_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Client ADD CONSTRAINT FK_C0E80163A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_C0E80163A76ED395 ON Client (user_id)');
    }
}
