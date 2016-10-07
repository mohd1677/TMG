<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150330142033 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Addresses DROP FOREIGN KEY FK_ED3BF7B55D83CC1');
        $this->addSql('DROP INDEX IDX_ED3BF7B55D83CC1 ON Addresses');
        $this->addSql('ALTER TABLE Addresses CHANGE interstate_number interstate_number VARCHAR(255) DEFAULT NULL, CHANGE interstate_exit interstate_exit VARCHAR(255) DEFAULT NULL, CHANGE display_interstate_exit display_interstate_exit VARCHAR(255) DEFAULT NULL, CHANGE state_id state INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Addresses ADD CONSTRAINT FK_ED3BF7B5A393D2FB FOREIGN KEY (state) REFERENCES States (id)');
        $this->addSql('CREATE INDEX IDX_ED3BF7B5A393D2FB ON Addresses (state)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Addresses DROP FOREIGN KEY FK_ED3BF7B5A393D2FB');
        $this->addSql('DROP INDEX IDX_ED3BF7B5A393D2FB ON Addresses');
        $this->addSql('ALTER TABLE Addresses CHANGE interstate_number interstate_number VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE interstate_exit interstate_exit VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE display_interstate_exit display_interstate_exit VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE state state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Addresses ADD CONSTRAINT FK_ED3BF7B55D83CC1 FOREIGN KEY (state_id) REFERENCES States (id)');
        $this->addSql('CREATE INDEX IDX_ED3BF7B55D83CC1 ON Addresses (state_id)');
    }
}
