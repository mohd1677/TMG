<?php

namespace TMG\Api\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224093502 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReputationCustomers ADD follow_up_email_send_date DATETIME DEFAULT NULL, ADD follow_up_number_opened INT DEFAULT NULL, ADD follow_up_yes INT DEFAULT NULL, ADD follow_up_no INT DEFAULT NULL, ADD follow_up_redirect_url VARCHAR(255) DEFAULT NULL, ADD thank_you_email_send_date DATETIME DEFAULT NULL, ADD thank_you_number_opened INT DEFAULT NULL, ADD thank_you_click_tripadvisor INT DEFAULT NULL, ADD thank_you_click_googleplus INT DEFAULT NULL, ADD thank_you_click_survey INT DEFAULT NULL, ADD thank_you_redirect_url VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE ReputationCustomers DROP follow_up_email_send_date, DROP follow_up_number_opened, DROP follow_up_yes, DROP follow_up_no, DROP follow_up_redirect_url, DROP thank_you_email_send_date, DROP thank_you_number_opened, DROP thank_you_click_tripadvisor, DROP thank_you_click_googleplus, DROP thank_you_click_survey, DROP thank_you_redirect_url');
    }
}
