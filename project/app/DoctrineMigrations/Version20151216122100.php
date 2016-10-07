<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151216122100 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ResolveResponse DROP FOREIGN KEY FK_B108D73CA76ED395');
        $this->addSql('DROP INDEX IDX_B108D73CA76ED395 ON ResolveResponse');
        $this->addSql('ALTER TABLE ResolveResponse CHANGE user_id analystUser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ResolveResponse ADD CONSTRAINT FK_B108D73CC3921EB1 FOREIGN KEY (analystUser_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_B108D73CC3921EB1 ON ResolveResponse (analystUser_id)');
        $this->addSql('ALTER TABLE ResolveSetting DROP FOREIGN KEY FK_89368044A76ED395');
        $this->addSql('DROP INDEX IDX_89368044A76ED395 ON ResolveSetting');
        $this->addSql('ALTER TABLE ResolveSetting ADD hotelierUser_id INT DEFAULT NULL, CHANGE user_id analystUser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ResolveSetting ADD CONSTRAINT FK_89368044C3921EB1 FOREIGN KEY (analystUser_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE ResolveSetting ADD CONSTRAINT FK_893680443093F062 FOREIGN KEY (hotelierUser_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_89368044C3921EB1 ON ResolveSetting (analystUser_id)');
        $this->addSql('CREATE INDEX IDX_893680443093F062 ON ResolveSetting (hotelierUser_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ResolveResponse DROP FOREIGN KEY FK_B108D73CC3921EB1');
        $this->addSql('DROP INDEX IDX_B108D73CC3921EB1 ON ResolveResponse');
        $this->addSql('ALTER TABLE ResolveResponse CHANGE analystuser_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ResolveResponse ADD CONSTRAINT FK_B108D73CA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_B108D73CA76ED395 ON ResolveResponse (user_id)');
        $this->addSql('ALTER TABLE ResolveSetting DROP FOREIGN KEY FK_89368044C3921EB1');
        $this->addSql('ALTER TABLE ResolveSetting DROP FOREIGN KEY FK_893680443093F062');
        $this->addSql('DROP INDEX IDX_89368044C3921EB1 ON ResolveSetting');
        $this->addSql('DROP INDEX IDX_893680443093F062 ON ResolveSetting');
        $this->addSql('ALTER TABLE ResolveSetting ADD user_id INT DEFAULT NULL, DROP analystUser_id, DROP hotelierUser_id');
        $this->addSql('ALTER TABLE ResolveSetting ADD CONSTRAINT FK_89368044A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_89368044A76ED395 ON ResolveSetting (user_id)');
    }
}
