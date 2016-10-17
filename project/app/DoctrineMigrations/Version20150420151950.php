<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420151950 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Users ADD address_id INT DEFAULT NULL, ADD state INT DEFAULT NULL, ADD country_id INT DEFAULT NULL, ADD postal_id INT DEFAULT NULL, ADD old_pass VARCHAR(255) DEFAULT NULL, ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD full_name VARCHAR(255) DEFAULT NULL, ADD tutorial TINYINT(1) DEFAULT NULL, ADD subscribed TINYINT(1) DEFAULT NULL, ADD birth_date DATETIME DEFAULT NULL, ADD gender VARCHAR(1) DEFAULT NULL, ADD household_members INT DEFAULT NULL, ADD household_children INT DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDF5B7AF75 FOREIGN KEY (address_id) REFERENCES Addresses (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDA393D2FB FOREIGN KEY (state) REFERENCES States (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDF92F3E70 FOREIGN KEY (country_id) REFERENCES Countries (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AED1EF0EA69 FOREIGN KEY (postal_id) REFERENCES PostalCodes (id)');
        $this->addSql('CREATE INDEX IDX_D5428AEDF5B7AF75 ON Users (address_id)');
        $this->addSql('CREATE INDEX IDX_D5428AEDA393D2FB ON Users (state)');
        $this->addSql('CREATE INDEX IDX_D5428AEDF92F3E70 ON Users (country_id)');
        $this->addSql('CREATE INDEX IDX_D5428AED1EF0EA69 ON Users (postal_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDF5B7AF75');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDA393D2FB');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDF92F3E70');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AED1EF0EA69');
        $this->addSql('DROP INDEX IDX_D5428AEDF5B7AF75 ON Users');
        $this->addSql('DROP INDEX IDX_D5428AEDA393D2FB ON Users');
        $this->addSql('DROP INDEX IDX_D5428AEDF92F3E70 ON Users');
        $this->addSql('DROP INDEX IDX_D5428AED1EF0EA69 ON Users');
        $this->addSql('ALTER TABLE Users DROP address_id, DROP state, DROP country_id, DROP postal_id, DROP old_pass, DROP first_name, DROP last_name, DROP full_name, DROP tutorial, DROP subscribed, DROP birth_date, DROP gender, DROP household_members, DROP household_children, DROP phone, DROP city, DROP created_at, DROP updated_at');
    }
}
