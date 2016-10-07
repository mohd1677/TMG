<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151222190114 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ResolveReviewTag (id INT AUTO_INCREMENT NOT NULL, value SMALLINT NOT NULL, reputationReview_id INT DEFAULT NULL, resolveTag_id INT DEFAULT NULL, INDEX IDX_662486071058A0D3 (reputationReview_id), INDEX IDX_66248607275BB13B (resolveTag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ResolveReviewTag ADD CONSTRAINT FK_662486071058A0D3 FOREIGN KEY (reputationReview_id) REFERENCES ReputationReviews (id)');
        $this->addSql('ALTER TABLE ResolveReviewTag ADD CONSTRAINT FK_66248607275BB13B FOREIGN KEY (resolveTag_id) REFERENCES ResolveTag (id)');
        $this->addSql('DROP TABLE resolve_response_resolve_tag');
        $this->addSql('ALTER TABLE ResolveTag ADD hash VARCHAR(8) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D667A599D1B862B8 ON ResolveTag (hash)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resolve_response_resolve_tag (resolveResponse_id INT NOT NULL, resolveTag_id INT NOT NULL, INDEX IDX_607827CDD6D4BAAF (resolveResponse_id), INDEX IDX_607827CD275BB13B (resolveTag_id), PRIMARY KEY(resolveResponse_id, resolveTag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resolve_response_resolve_tag ADD CONSTRAINT FK_607827CDD6D4BAAF FOREIGN KEY (resolveResponse_id) REFERENCES ResolveResponse (id)');
        $this->addSql('ALTER TABLE resolve_response_resolve_tag ADD CONSTRAINT FK_607827CD275BB13B FOREIGN KEY (resolveTag_id) REFERENCES ResolveTag (id)');
        $this->addSql('DROP TABLE ResolveReviewTag');
        $this->addSql('DROP INDEX UNIQ_D667A599D1B862B8 ON ResolveTag');
        $this->addSql('ALTER TABLE ResolveTag DROP hash');
    }
}
