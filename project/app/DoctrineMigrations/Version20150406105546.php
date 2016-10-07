<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150406105546 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Videos (id INT AUTO_INCREMENT NOT NULL, status INT DEFAULT NULL, description_id INT DEFAULT NULL, property_id INT DEFAULT NULL, submitted_by INT DEFAULT NULL, published_by INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, summary LONGTEXT DEFAULT NULL, duration INT DEFAULT NULL, create_url VARCHAR(255) NOT NULL, player_id INT DEFAULT NULL, vidyard_id INT DEFAULT NULL, inline LONGTEXT DEFAULT NULL, iframe LONGTEXT DEFAULT NULL, light_box LONGTEXT DEFAULT NULL, submitted DATETIME DEFAULT NULL, published DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2E0661047B00651C (status), UNIQUE INDEX UNIQ_2E066104D9F966B (description_id), UNIQUE INDEX UNIQ_2E066104549213EC (property_id), UNIQUE INDEX UNIQ_2E066104641EE842 (submitted_by), UNIQUE INDEX UNIQ_2E066104B548D29F (published_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E0661047B00651C FOREIGN KEY (status) REFERENCES VideoStatuses (id)');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E066104D9F966B FOREIGN KEY (description_id) REFERENCES Descriptions (id)');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E066104549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E066104641EE842 FOREIGN KEY (submitted_by) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E066104B548D29F FOREIGN KEY (published_by) REFERENCES Users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Videos');
    }
}
