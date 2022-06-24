<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624081446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE message_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE message_response (id INT NOT NULL, author_id INT NOT NULL, original_message_id INT NOT NULL, content TEXT NOT NULL, response_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_375DCFEAF675F31B ON message_response (author_id)');
        $this->addSql('CREATE INDEX IDX_375DCFEA3ECD64BD ON message_response (original_message_id)');
        $this->addSql('COMMENT ON COLUMN message_response.response_created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE message_response ADD CONSTRAINT FK_375DCFEAF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message_response ADD CONSTRAINT FK_375DCFEA3ECD64BD FOREIGN KEY (original_message_id) REFERENCES private_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE message_response_id_seq CASCADE');
        $this->addSql('DROP TABLE message_response');
    }
}
