<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150414172416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Rates (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, type INT DEFAULT NULL, advertisement_type INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, restrictions VARCHAR(255) DEFAULT NULL, rate_pretty VARCHAR(255) NOT NULL, rate_value NUMERIC(12, 2) DEFAULT NULL, approved TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, show_limit INT DEFAULT NULL, prioritize TINYINT(1) DEFAULT NULL, INDEX IDX_85158438549213EC (property_id), INDEX IDX_851584388CDE5729 (type), INDEX IDX_851584386F5C4C61 (advertisement_type), UNIQUE INDEX UNIQ_85158438DE12AB56 (created_by), UNIQUE INDEX UNIQ_8515843816FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RateTypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Rates ADD CONSTRAINT FK_85158438549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Rates ADD CONSTRAINT FK_851584388CDE5729 FOREIGN KEY (type) REFERENCES RateTypes (id)');
        $this->addSql('ALTER TABLE Rates ADD CONSTRAINT FK_851584386F5C4C61 FOREIGN KEY (advertisement_type) REFERENCES ProductTypes (id)');
        $this->addSql('ALTER TABLE Rates ADD CONSTRAINT FK_85158438DE12AB56 FOREIGN KEY (created_by) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Rates ADD CONSTRAINT FK_8515843816FE72E1 FOREIGN KEY (updated_by) REFERENCES Users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Rates DROP FOREIGN KEY FK_851584388CDE5729');
        $this->addSql('DROP TABLE Rates');
        $this->addSql('DROP TABLE RateTypes');
    }
}
