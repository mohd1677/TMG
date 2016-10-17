<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150919201753 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData DROP FOREIGN KEY FK_50ECE641549213EC');
        $this->addSql('DROP INDEX UNIQ_50ECE641549213EC ON TripStayWinData');
        $this->addSql('ALTER TABLE TripStayWinData DROP property_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE TripStayWinData ADD property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE TripStayWinData ADD CONSTRAINT FK_50ECE641549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50ECE641549213EC ON TripStayWinData (property_id)');
    }
}
