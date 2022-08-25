<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825083914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notes_tableau_notes (notes_id INT NOT NULL, tableau_notes_id INT NOT NULL, INDEX IDX_828AD2EDFC56F556 (notes_id), INDEX IDX_828AD2EDE087BA45 (tableau_notes_id), PRIMARY KEY(notes_id, tableau_notes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE table_notes (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau_notes (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, note1 VARCHAR(255) DEFAULT NULL, note2 VARCHAR(255) DEFAULT NULL, note3 VARCHAR(255) DEFAULT NULL, observation1 VARCHAR(255) DEFAULT NULL, observation2 VARCHAR(255) DEFAULT NULL, observation3 VARCHAR(255) DEFAULT NULL, piecepath VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8BA9CB01DDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes_tableau_notes ADD CONSTRAINT FK_828AD2EDFC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_tableau_notes ADD CONSTRAINT FK_828AD2EDE087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_notes ADD CONSTRAINT FK_8BA9CB01DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes_tableau_notes DROP FOREIGN KEY FK_828AD2EDFC56F556');
        $this->addSql('ALTER TABLE notes_tableau_notes DROP FOREIGN KEY FK_828AD2EDE087BA45');
        $this->addSql('ALTER TABLE tableau_notes DROP FOREIGN KEY FK_8BA9CB01DDEAB1A3');
        $this->addSql('DROP TABLE notes_tableau_notes');
        $this->addSql('DROP TABLE table_notes');
        $this->addSql('DROP TABLE tableau_notes');
    }
}
