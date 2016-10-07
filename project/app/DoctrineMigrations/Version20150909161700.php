<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150909161700 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RateOurStaySubdomain DROP FOREIGN KEY FK_DAA980288530A5DC');
        $this->addSql('DROP INDEX IDX_DAA980288530A5DC ON RateOurStaySubdomain');
        $this->addSql('ALTER TABLE RateOurStaySubdomain CHANGE subdomain_id rateOurStayData_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RateOurStaySubdomain ADD CONSTRAINT FK_DAA98028DE50FD83 FOREIGN KEY (rateOurStayData_id) REFERENCES RateOurStayData (id)');
        $this->addSql('CREATE INDEX IDX_DAA98028DE50FD83 ON RateOurStaySubdomain (rateOurStayData_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RateOurStaySubdomain DROP FOREIGN KEY FK_DAA98028DE50FD83');
        $this->addSql('DROP INDEX IDX_DAA98028DE50FD83 ON RateOurStaySubdomain');
        $this->addSql('ALTER TABLE RateOurStaySubdomain CHANGE rateourstaydata_id subdomain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE RateOurStaySubdomain ADD CONSTRAINT FK_DAA980288530A5DC FOREIGN KEY (subdomain_id) REFERENCES RateOurStayData (id)');
        $this->addSql('CREATE INDEX IDX_DAA980288530A5DC ON RateOurStaySubdomain (subdomain_id)');
    }
}
