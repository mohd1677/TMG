<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160627192705 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ResolveResponseRating (id INT AUTO_INCREMENT NOT NULL, resolve_response INT DEFAULT NULL, rating INT NOT NULL, payment_value NUMERIC(5, 2) NOT NULL, feedback LONGTEXT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, ratedBy INT DEFAULT NULL, proposedBy INT DEFAULT NULL, UNIQUE INDEX UNIQ_9660FB4380FA938A (resolve_response), INDEX IDX_9660FB4381D1045E (ratedBy), INDEX IDX_9660FB43C155C3C3 (proposedBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ResolveResponseRating ADD CONSTRAINT FK_9660FB4380FA938A FOREIGN KEY (resolve_response) REFERENCES ResolveResponse (id)');
        $this->addSql('ALTER TABLE ResolveResponseRating ADD CONSTRAINT FK_9660FB4381D1045E FOREIGN KEY (ratedBy) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE ResolveResponseRating ADD CONSTRAINT FK_9660FB43C155C3C3 FOREIGN KEY (proposedBy) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE ResolveResponse DROP rating, DROP feedback');
        $this->addSql('ALTER TABLE Users ADD contractor_pay_scale INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ResolveResponseRating');
        $this->addSql('ALTER TABLE ResolveResponse ADD rating INT DEFAULT NULL, ADD feedback LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Users DROP contractor_pay_scale');
    }
}
