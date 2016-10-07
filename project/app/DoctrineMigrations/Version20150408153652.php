<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150408153652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE CallLogs (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, state_id INT DEFAULT NULL, call_id VARCHAR(20) NOT NULL, start_time DATETIME NOT NULL, duration INT NOT NULL, talk_time INT NOT NULL, call_num INT NOT NULL, tracking_num INT NOT NULL, endpoint_num INT DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, account INT NOT NULL, campaign VARCHAR(255) NOT NULL, recording_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BDFCBE25549213EC (property_id), INDEX IDX_BDFCBE255D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CallLogs ADD CONSTRAINT FK_BDFCBE25549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE CallLogs ADD CONSTRAINT FK_BDFCBE255D83CC1 FOREIGN KEY (state_id) REFERENCES States (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE CallLogs');
    }
}
