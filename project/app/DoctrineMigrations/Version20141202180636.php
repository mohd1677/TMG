<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141202180636 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE DocParams DROP FOREIGN KEY FK_EF4208AC34ECB4E6');
        $this->addSql('ALTER TABLE DocParams ADD CONSTRAINT FK_EF4208AC34ECB4E6 FOREIGN KEY (route_id) REFERENCES ApiDocMeta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE DocParams DROP FOREIGN KEY FK_EF4208AC34ECB4E6');
        $this->addSql('ALTER TABLE DocParams ADD CONSTRAINT FK_EF4208AC34ECB4E6 FOREIGN KEY (route_id) REFERENCES ApiDocMeta (id)');
    }
}
