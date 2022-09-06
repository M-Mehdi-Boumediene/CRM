<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906093208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau_notes_etudiants (tableau_notes_id INT NOT NULL, etudiants_id INT NOT NULL, INDEX IDX_85A317F2E087BA45 (tableau_notes_id), INDEX IDX_85A317F2A873A5C6 (etudiants_id), PRIMARY KEY(tableau_notes_id, etudiants_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tableau_notes_etudiants ADD CONSTRAINT FK_85A317F2E087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_notes_etudiants ADD CONSTRAINT FK_85A317F2A873A5C6 FOREIGN KEY (etudiants_id) REFERENCES etudiants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiants DROP FOREIGN KEY FK_227C02EBE087BA45');
        $this->addSql('DROP INDEX IDX_227C02EBE087BA45 ON etudiants');
        $this->addSql('ALTER TABLE etudiants DROP tableau_notes_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tableau_notes_etudiants DROP FOREIGN KEY FK_85A317F2E087BA45');
        $this->addSql('ALTER TABLE tableau_notes_etudiants DROP FOREIGN KEY FK_85A317F2A873A5C6');
        $this->addSql('DROP TABLE tableau_notes_etudiants');
        $this->addSql('ALTER TABLE etudiants ADD tableau_notes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiants ADD CONSTRAINT FK_227C02EBE087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id)');
        $this->addSql('CREATE INDEX IDX_227C02EBE087BA45 ON etudiants (tableau_notes_id)');
    }
}
