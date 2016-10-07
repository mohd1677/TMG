<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420131715 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE IHGProperties ADD brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE IHGProperties ADD CONSTRAINT FK_D7644AC244F5D008 FOREIGN KEY (brand_id) REFERENCES Brands (id)');
        $this->addSql('CREATE INDEX IDX_D7644AC244F5D008 ON IHGProperties (brand_id)');
        $this->addSql('ALTER TABLE Properties ADD brand_id INT DEFAULT NULL, ADD brand_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Properties ADD CONSTRAINT FK_C89E321744F5D008 FOREIGN KEY (brand_id) REFERENCES Brands (id)');
        $this->addSql('CREATE INDEX IDX_C89E321744F5D008 ON Properties (brand_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE IHGProperties DROP FOREIGN KEY FK_D7644AC244F5D008');
        $this->addSql('DROP INDEX IDX_D7644AC244F5D008 ON IHGProperties');
        $this->addSql('ALTER TABLE IHGProperties DROP brand_id');
        $this->addSql('ALTER TABLE Properties DROP FOREIGN KEY FK_C89E321744F5D008');
        $this->addSql('DROP INDEX IDX_C89E321744F5D008 ON Properties');
        $this->addSql('ALTER TABLE Properties DROP brand_id, DROP brand_url');
    }
}
