<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906092247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes_classes DROP FOREIGN KEY FK_28AE26A173F98F88');
        $this->addSql('ALTER TABLE classes_classes DROP FOREIGN KEY FK_28AE26A16A1CDF07');
        $this->addSql('DROP TABLE classes_classes');
        $this->addSql('ALTER TABLE etudiants ADD tableau_notes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiants ADD CONSTRAINT FK_227C02EBE087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id)');
        $this->addSql('CREATE INDEX IDX_227C02EBE087BA45 ON etudiants (tableau_notes_id)');
        $this->addSql('ALTER TABLE tableau_notes DROP FOREIGN KEY FK_8BA9CB01DDEAB1A3');
        $this->addSql('DROP INDEX UNIQ_8BA9CB01DDEAB1A3 ON tableau_notes');
        $this->addSql('ALTER TABLE tableau_notes DROP etudiant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classes_classes (classes_source INT NOT NULL, classes_target INT NOT NULL, INDEX IDX_28AE26A16A1CDF07 (classes_target), INDEX IDX_28AE26A173F98F88 (classes_source), PRIMARY KEY(classes_source, classes_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE classes_classes ADD CONSTRAINT FK_28AE26A173F98F88 FOREIGN KEY (classes_source) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classes_classes ADD CONSTRAINT FK_28AE26A16A1CDF07 FOREIGN KEY (classes_target) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiants DROP FOREIGN KEY FK_227C02EBE087BA45');
        $this->addSql('DROP INDEX IDX_227C02EBE087BA45 ON etudiants');
        $this->addSql('ALTER TABLE etudiants DROP tableau_notes_id');
        $this->addSql('ALTER TABLE tableau_notes ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tableau_notes ADD CONSTRAINT FK_8BA9CB01DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BA9CB01DDEAB1A3 ON tableau_notes (etudiant_id)');
    }
}
