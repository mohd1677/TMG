<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160126114635 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE HotelRevenueCalculations ADD ota_fees DOUBLE PRECISION NOT NULL, ADD rev_par_increase DOUBLE PRECISION NOT NULL, ADD divert_existing_flow_from_ota DOUBLE PRECISION NOT NULL, ADD hotel_finder_rooms_per_night INT NOT NULL, ADD organic_search_rooms_per_night INT NOT NULL, ADD hotel_coupons_rooms_per_night INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE HotelRevenueCalculations DROP ota_fees, DROP rev_par_increase, DROP divert_existing_flow_from_ota, DROP hotel_finder_rooms_per_night, DROP organic_search_rooms_per_night, DROP hotel_coupons_rooms_per_night');
    }
}
