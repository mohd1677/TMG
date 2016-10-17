<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413132008 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE CityCenters (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, country_id INT DEFAULT NULL, city VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, hero_image VARCHAR(255) DEFAULT NULL, place TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6213C4095D83CC1 (state_id), INDEX IDX_6213C409F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postal_code_city_centers (postal_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_A650F8221EF0EA69 (postal_id), INDEX IDX_A650F8228BAC62AF (city_id), PRIMARY KEY(postal_id, city_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CityCenters ADD CONSTRAINT FK_6213C4095D83CC1 FOREIGN KEY (state_id) REFERENCES States (id)');
        $this->addSql('ALTER TABLE CityCenters ADD CONSTRAINT FK_6213C409F92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
        $this->addSql('ALTER TABLE postal_code_city_centers ADD CONSTRAINT FK_A650F8221EF0EA69 FOREIGN KEY (postal_id) REFERENCES PostalCodes (id)');
        $this->addSql('ALTER TABLE postal_code_city_centers ADD CONSTRAINT FK_A650F8228BAC62AF FOREIGN KEY (city_id) REFERENCES CityCenters (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE postal_code_city_centers DROP FOREIGN KEY FK_A650F8228BAC62AF');
        $this->addSql('DROP TABLE CityCenters');
        $this->addSql('DROP TABLE postal_code_city_centers');
    }
}
