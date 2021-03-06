<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716135423 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationQuestion DROP FOREIGN KEY FK_298A3803AD5F9BFC');
        $this->addSql('CREATE TABLE ReputationQuestions (id INT AUTO_INCREMENT NOT NULL, survey INT DEFAULT NULL, category INT DEFAULT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_822E1489AD5F9BFC (survey), INDEX IDX_822E148964C19C1 (category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReputationSurveys (id INT AUTO_INCREMENT NOT NULL, reputation_id INT DEFAULT NULL, customer INT DEFAULT NULL, source INT DEFAULT NULL, email_type VARCHAR(255) NOT NULL, open INT NOT NULL, yes INT NOT NULL, no INT NOT NULL, response_date DATETIME NOT NULL, yrmo INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6D3DC6E154266CA2 (reputation_id), INDEX IDX_6D3DC6E181398E09 (customer), INDEX IDX_6D3DC6E15F8A7F73 (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationQuestions ADD CONSTRAINT FK_822E1489AD5F9BFC FOREIGN KEY (survey) REFERENCES ReputationSurveys (id)');
        $this->addSql('ALTER TABLE ReputationQuestions ADD CONSTRAINT FK_822E148964C19C1 FOREIGN KEY (category) REFERENCES ReputationCategories (id)');
        $this->addSql('ALTER TABLE ReputationSurveys ADD CONSTRAINT FK_6D3DC6E154266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE ReputationSurveys ADD CONSTRAINT FK_6D3DC6E181398E09 FOREIGN KEY (customer) REFERENCES ReputationCustomers (id)');
        $this->addSql('ALTER TABLE ReputationSurveys ADD CONSTRAINT FK_6D3DC6E15F8A7F73 FOREIGN KEY (source) REFERENCES ReputationSources (id)');
        $this->addSql('DROP TABLE ReputationQuestion');
        $this->addSql('DROP TABLE ReputationSurvey');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationQuestions DROP FOREIGN KEY FK_822E1489AD5F9BFC');
        $this->addSql('CREATE TABLE ReputationQuestion (id INT AUTO_INCREMENT NOT NULL, category INT DEFAULT NULL, survey INT DEFAULT NULL, question VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, answer VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_298A3803AD5F9BFC (survey), INDEX IDX_298A380364C19C1 (category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReputationSurvey (id INT AUTO_INCREMENT NOT NULL, source INT DEFAULT NULL, reputation_id INT DEFAULT NULL, customer INT DEFAULT NULL, email_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, open INT NOT NULL, yes INT NOT NULL, no INT NOT NULL, response_date DATETIME NOT NULL, yrmo INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EF487A4054266CA2 (reputation_id), INDEX IDX_EF487A4081398E09 (customer), INDEX IDX_EF487A405F8A7F73 (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReputationQuestion ADD CONSTRAINT FK_298A380364C19C1 FOREIGN KEY (category) REFERENCES ReputationCategories (id)');
        $this->addSql('ALTER TABLE ReputationQuestion ADD CONSTRAINT FK_298A3803AD5F9BFC FOREIGN KEY (survey) REFERENCES ReputationSurvey (id)');
        $this->addSql('ALTER TABLE ReputationSurvey ADD CONSTRAINT FK_EF487A405F8A7F73 FOREIGN KEY (source) REFERENCES ReputationSources (id)');
        $this->addSql('ALTER TABLE ReputationSurvey ADD CONSTRAINT FK_EF487A4054266CA2 FOREIGN KEY (reputation_id) REFERENCES Reputations (id)');
        $this->addSql('ALTER TABLE ReputationSurvey ADD CONSTRAINT FK_EF487A4081398E09 FOREIGN KEY (customer) REFERENCES ReputationCustomers (id)');
        $this->addSql('DROP TABLE ReputationQuestions');
        $this->addSql('DROP TABLE ReputationSurveys');
    }
}
