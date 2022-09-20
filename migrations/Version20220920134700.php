<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920134700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents ADD messages_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A5905F5A FOREIGN KEY (messages_id) REFERENCES messages (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288A5905F5A ON documents (messages_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288A5905F5A');
        $this->addSql('DROP INDEX IDX_A2B07288A5905F5A ON documents');
        $this->addSql('ALTER TABLE documents DROP messages_id');
    }
}
