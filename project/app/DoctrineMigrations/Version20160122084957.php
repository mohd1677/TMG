<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160122084957 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE HotelRevenueCalculations ADD user_id INT DEFAULT NULL, CHANGE hotel_city hotel_city VARCHAR(255) DEFAULT NULL, CHANGE hotel_state hotel_state VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE HotelRevenueCalculations ADD CONSTRAINT FK_D0933400A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_D0933400A76ED395 ON HotelRevenueCalculations (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE HotelRevenueCalculations DROP FOREIGN KEY FK_D0933400A76ED395');
        $this->addSql('DROP INDEX IDX_D0933400A76ED395 ON HotelRevenueCalculations');
        $this->addSql('ALTER TABLE HotelRevenueCalculations DROP user_id, CHANGE hotel_city hotel_city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE hotel_state hotel_state VARCHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
