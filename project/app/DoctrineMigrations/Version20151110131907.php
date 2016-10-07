<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151110131907 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE LocalEvent DROP FOREIGN KEY FK_D4083CBB8BF21CDE');
        $this->addSql('DROP INDEX IDX_D4083CBB8BF21CDE ON LocalEvent');
        $this->addSql('ALTER TABLE LocalEvent CHANGE property property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE LocalEvent ADD CONSTRAINT FK_D4083CBB549213EC FOREIGN KEY (property_id) REFERENCES Properties (id)');
        $this->addSql('CREATE INDEX IDX_D4083CBB549213EC ON LocalEvent (property_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE LocalEvent DROP FOREIGN KEY FK_D4083CBB549213EC');
        $this->addSql('DROP INDEX IDX_D4083CBB549213EC ON LocalEvent');
        $this->addSql('ALTER TABLE LocalEvent CHANGE property_id property INT DEFAULT NULL');
        $this->addSql('ALTER TABLE LocalEvent ADD CONSTRAINT FK_D4083CBB8BF21CDE FOREIGN KEY (property) REFERENCES Properties (id)');
        $this->addSql('CREATE INDEX IDX_D4083CBB8BF21CDE ON LocalEvent (property)');
    }
}
