<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150212151147 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RoleRights (role_id INT NOT NULL, right_id INT NOT NULL, INDEX IDX_3475D356D60322AC (role_id), INDEX IDX_3475D35654976835 (right_id), PRIMARY KEY(role_id, right_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RoleRights ADD CONSTRAINT FK_3475D356D60322AC FOREIGN KEY (role_id) REFERENCES UserRoles (id)');
        $this->addSql('ALTER TABLE RoleRights ADD CONSTRAINT FK_3475D35654976835 FOREIGN KEY (right_id) REFERENCES UserRights (id)');
        $this->addSql('DROP TABLE role_right');
        $this->addSql('ALTER TABLE ApiDocMeta ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Client ADD user_id INT DEFAULT NULL, ADD application_name VARCHAR(255) NOT NULL, ADD homepage_url VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Client ADD CONSTRAINT FK_C0E80163A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_C0E80163A76ED395 ON Client (user_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role_right (role_id INT NOT NULL, right_id INT NOT NULL, INDEX IDX_43169D3BD60322AC (role_id), INDEX IDX_43169D3B54976835 (right_id), PRIMARY KEY(role_id, right_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_right ADD CONSTRAINT FK_43169D3B54976835 FOREIGN KEY (right_id) REFERENCES UserRights (id)');
        $this->addSql('ALTER TABLE role_right ADD CONSTRAINT FK_43169D3BD60322AC FOREIGN KEY (role_id) REFERENCES UserRoles (id)');
        $this->addSql('DROP TABLE RoleRights');
        $this->addSql('ALTER TABLE ApiDocMeta DROP updatedAt');
        $this->addSql('ALTER TABLE Client DROP FOREIGN KEY FK_C0E80163A76ED395');
        $this->addSql('DROP INDEX IDX_C0E80163A76ED395 ON Client');
        $this->addSql('ALTER TABLE Client DROP user_id, DROP application_name, DROP homepage_url, DROP description, DROP createdAt, DROP updatedAt');
    }
}
