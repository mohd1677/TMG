<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150416124634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Socials (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, active TINYINT(1) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, clicks INT DEFAULT NULL, spent NUMERIC(12, 2) DEFAULT NULL, impressions INT DEFAULT NULL, reach INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A71E81F5549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SocialData (id INT AUTO_INCREMENT NOT NULL, social_id INT DEFAULT NULL, type INT DEFAULT NULL, yrmo INT NOT NULL, fans INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C1D670D4FFEB5B27 (social_id), INDEX IDX_C1D670D48CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Socials ADD CONSTRAINT FK_A71E81F5549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE SocialData ADD CONSTRAINT FK_C1D670D4FFEB5B27 FOREIGN KEY (social_id) REFERENCES Socials (id)');
        $this->addSql('ALTER TABLE SocialData ADD CONSTRAINT FK_C1D670D48CDE5729 FOREIGN KEY (type) REFERENCES SocialDataTypes (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SocialData DROP FOREIGN KEY FK_C1D670D4FFEB5B27');
        $this->addSql('DROP TABLE Socials');
        $this->addSql('DROP TABLE SocialData');
    }
}
