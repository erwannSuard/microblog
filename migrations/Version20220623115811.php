<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623115811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE private_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE private_message (id INT NOT NULL, author_id INT NOT NULL, receiver_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4744FC9BF675F31B ON private_message (author_id)');
        $this->addSql('CREATE INDEX IDX_4744FC9BCD53EDB6 ON private_message (receiver_id)');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE private_message_id_seq CASCADE');
        $this->addSql('DROP TABLE private_message');
    }
}
