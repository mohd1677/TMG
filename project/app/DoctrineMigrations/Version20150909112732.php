<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150909112732 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RateOurStaySubdomain (id INT AUTO_INCREMENT NOT NULL, subdomain_id INT DEFAULT NULL, subdomain VARCHAR(255) NOT NULL, createdDate DATETIME NOT NULL, INDEX IDX_DAA980288530A5DC (subdomain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RateOurStaySubdomain ADD CONSTRAINT FK_DAA980288530A5DC FOREIGN KEY (subdomain_id) REFERENCES RateOurStayData (id)');
        $this->addSql('ALTER TABLE Properties ADD rateOurStayData_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E3217DE50FD83 FOREIGN KEY (rateOurStayData_id) REFERENCES RateOurStayData (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C89E3217DE50FD83 ON Properties (rateOurStayData_id)');
        $this->addSql('ALTER TABLE RateOurStayData DROP subdomain');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE RateOurStaySubdomain');
        $this->addSql('ALTER TABLE Properties DROP FOREIGN KEY FK_C89E3217DE50FD83');
        $this->addSql('DROP INDEX UNIQ_C89E3217DE50FD83 ON Properties');
        $this->addSql('ALTER TABLE Properties DROP rateOurStayData_id');
        $this->addSql('ALTER TABLE RateOurStayData ADD subdomain VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
