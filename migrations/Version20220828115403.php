<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828115403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files ADD tableau_notes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059E087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id)');
        $this->addSql('CREATE INDEX IDX_6354059E087BA45 ON files (tableau_notes_id)');
        $this->addSql('ALTER TABLE tableau_notes DROP piecepath');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059E087BA45');
        $this->addSql('DROP INDEX IDX_6354059E087BA45 ON files');
        $this->addSql('ALTER TABLE files DROP tableau_notes_id');
        $this->addSql('ALTER TABLE tableau_notes ADD piecepath VARCHAR(255) DEFAULT NULL');
    }
}
