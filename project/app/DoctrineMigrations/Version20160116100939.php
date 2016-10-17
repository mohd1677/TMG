<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160116100939 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Properties DROP FOREIGN KEY FK_C89E3217CB48DF8D');
        $this->addSql('DROP INDEX UNIQ_C89E3217CB48DF8D ON Properties');
        $this->addSql('ALTER TABLE Properties DROP resolveSetting_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Properties ADD resolveSetting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E3217CB48DF8D FOREIGN KEY (resolveSetting_id) REFERENCES ResolveSetting (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C89E3217CB48DF8D ON Properties (resolveSetting_id)');
    }
}
