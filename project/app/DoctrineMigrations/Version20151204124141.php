<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151204124141 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ResolveResponse (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hash VARCHAR(8) NOT NULL, response LONGTEXT NOT NULL, comment LONGTEXT NOT NULL, approved TINYINT(1) DEFAULT NULL, scheduled_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, reputationReview_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_B108D73CD1B862B8 (hash), INDEX IDX_B108D73C1058A0D3 (reputationReview_id), INDEX IDX_B108D73CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resolve_response_resolve_tag (resolveResponse_id INT NOT NULL, resolveTag_id INT NOT NULL, INDEX IDX_607827CDD6D4BAAF (resolveResponse_id), INDEX IDX_607827CD275BB13B (resolveTag_id), PRIMARY KEY(resolveResponse_id, resolveTag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ResolveSetting (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, user_id INT DEFAULT NULL, sla_normal INT NOT NULL, sla_critical INT NOT NULL, pre_approved INT DEFAULT NULL, pre_approved_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_89368044549213EC (property_id), INDEX IDX_89368044A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ResolveTag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ResolveResponse ADD CONSTRAINT FK_B108D73C1058A0D3 FOREIGN KEY (reputationReview_id) REFERENCES ReputationReviews (id)');
        $this->addSql('ALTER TABLE ResolveResponse ADD CONSTRAINT FK_B108D73CA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE resolve_response_resolve_tag ADD CONSTRAINT FK_607827CDD6D4BAAF FOREIGN KEY (resolveResponse_id) REFERENCES ResolveResponse (id)');
        $this->addSql('ALTER TABLE resolve_response_resolve_tag ADD CONSTRAINT FK_607827CD275BB13B FOREIGN KEY (resolveTag_id) REFERENCES ResolveTag (id)');
        $this->addSql('ALTER TABLE ResolveSetting ADD CONSTRAINT FK_89368044549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('ALTER TABLE ResolveSetting ADD CONSTRAINT FK_89368044A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE Properties ADD resolveSetting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E3217CB48DF8D FOREIGN KEY (resolveSetting_id) REFERENCES ResolveSetting (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C89E3217CB48DF8D ON Properties (resolveSetting_id)');
        $this->addSql('ALTER TABLE ReputationReviews ADD critical TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resolve_response_resolve_tag DROP FOREIGN KEY FK_607827CDD6D4BAAF');
        $this->addSql('ALTER TABLE Properties DROP FOREIGN KEY FK_C89E3217CB48DF8D');
        $this->addSql('ALTER TABLE resolve_response_resolve_tag DROP FOREIGN KEY FK_607827CD275BB13B');
        $this->addSql('DROP TABLE ResolveResponse');
        $this->addSql('DROP TABLE resolve_response_resolve_tag');
        $this->addSql('DROP TABLE ResolveSetting');
        $this->addSql('DROP TABLE ResolveTag');
        $this->addSql('DROP INDEX UNIQ_C89E3217CB48DF8D ON Properties');
        $this->addSql('ALTER TABLE Properties DROP resolveSetting_id');
        $this->addSql('ALTER TABLE ReputationReviews DROP critical');
    }
}
