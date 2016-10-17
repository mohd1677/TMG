<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160709171218 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ResolveContractorInvoice (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, hash VARCHAR(8) NOT NULL, total_payment_value NUMERIC(7, 2) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4D1702668D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ResolveContractorInvoice ADD CONSTRAINT FK_4D1702668D93D649 FOREIGN KEY (user) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE ResolveResponseRating ADD resolveContractorInvoice INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ResolveResponseRating ADD CONSTRAINT FK_9660FB43B3C1FC1C FOREIGN KEY (resolveContractorInvoice) REFERENCES ResolveContractorInvoice (id)');
        $this->addSql('CREATE INDEX IDX_9660FB43B3C1FC1C ON ResolveResponseRating (resolveContractorInvoice)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ResolveResponseRating DROP FOREIGN KEY FK_9660FB43B3C1FC1C');
        $this->addSql('DROP TABLE ResolveContractorInvoice');
        $this->addSql('DROP INDEX IDX_9660FB43B3C1FC1C ON ResolveResponseRating');
        $this->addSql('ALTER TABLE ResolveResponseRating DROP resolveContractorInvoice');
    }
}
