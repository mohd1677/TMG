<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150917171255 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE TripStayWinData (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, googleMapsIframe LONGTEXT NOT NULL, twitterWidget LONGTEXT NOT NULL, logoPath VARCHAR(255) NOT NULL, sweepstakesDesktop LONGTEXT NOT NULL, sweepstakesMobile LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_50ECE641549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE TripStayWinData ADD CONSTRAINT FK_50ECE641549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE Properties ADD tripStayWinData_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E3217EBC7609E FOREIGN KEY (tripStayWinData_id) REFERENCES TripStayWinData (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C89E3217EBC7609E ON Properties (tripStayWinData_id)');
        $this->addSql('ALTER TABLE RateOurStaySubdomain ADD tripStayWinData_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RateOurStaySubdomain ADD CONSTRAINT FK_DAA98028EBC7609E FOREIGN KEY (tripStayWinData_id) REFERENCES TripStayWinData (id)');
        $this->addSql('CREATE INDEX IDX_DAA98028EBC7609E ON RateOurStaySubdomain (tripStayWinData_id)');
        $this->addSql('ALTER TABLE ReputationCustomers ADD opened INT NOT NULL, ADD yes INT NOT NULL, ADD no INT NOT NULL, ADD upload_date DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Properties DROP FOREIGN KEY FK_C89E3217EBC7609E');
        $this->addSql('ALTER TABLE RateOurStaySubdomain DROP FOREIGN KEY FK_DAA98028EBC7609E');
        $this->addSql('DROP TABLE TripStayWinData');
        $this->addSql('DROP INDEX UNIQ_C89E3217EBC7609E ON Properties');
        $this->addSql('ALTER TABLE Properties DROP tripStayWinData_id');
        $this->addSql('DROP INDEX IDX_DAA98028EBC7609E ON RateOurStaySubdomain');
        $this->addSql('ALTER TABLE RateOurStaySubdomain DROP tripStayWinData_id');
        $this->addSql('ALTER TABLE ReputationCustomers DROP opened, DROP yes, DROP no, DROP upload_date');
    }
}
