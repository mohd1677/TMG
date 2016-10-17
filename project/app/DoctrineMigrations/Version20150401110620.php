<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150401110620 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_properties (user_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_20A4F72BA76ED395 (user_id), INDEX IDX_20A4F72B549213EC (property_id), PRIMARY KEY(user_id, property_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Properties (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, billing_address_id INT DEFAULT NULL, hash VARCHAR(8) NOT NULL, ax_number VARCHAR(40) NOT NULL, property_number VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, account_phone VARCHAR(255) DEFAULT NULL, send_fax TINYINT(1) DEFAULT \'0\' NOT NULL, send_email TINYINT(1) DEFAULT \'0\' NOT NULL, slug VARCHAR(255) NOT NULL, featured_amenities LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', rate_lock TINYINT(1) DEFAULT \'0\' NOT NULL, force_live TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C89E3217206B2CC5 (ax_number), INDEX IDX_C89E3217F5B7AF75 (address_id), INDEX IDX_C89E321779D0C0E4 (billing_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_amenities (property_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_9A9F56CA549213EC (property_id), INDEX IDX_9A9F56CA9F9F1305 (amenity_id), PRIMARY KEY(property_id, amenity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_properties ADD CONSTRAINT FK_20A4F72BA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE user_properties ADD CONSTRAINT FK_20A4F72B549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E3217F5B7AF75 FOREIGN KEY (address_id) REFERENCES Addresses (id)');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E321779D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES Addresses (id)');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CA549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CA9F9F1305 FOREIGN KEY (amenity_id) REFERENCES Amenities (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_properties DROP FOREIGN KEY FK_20A4F72B549213EC');
        $this->addSql('ALTER TABLE property_amenities DROP FOREIGN KEY FK_9A9F56CA549213EC');
        $this->addSql('DROP TABLE user_properties');
        $this->addSql('DROP TABLE Properties');
        $this->addSql('DROP TABLE property_amenities');
    }
}
