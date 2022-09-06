<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906142345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absences_tableau_absences (absences_id INT NOT NULL, tableau_absences_id INT NOT NULL, INDEX IDX_FC0ACC7E9A5BDCB7 (absences_id), INDEX IDX_FC0ACC7E20E700A (tableau_absences_id), PRIMARY KEY(absences_id, tableau_absences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absences_tableau_absences ADD CONSTRAINT FK_FC0ACC7E9A5BDCB7 FOREIGN KEY (absences_id) REFERENCES absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE absences_tableau_absences ADD CONSTRAINT FK_FC0ACC7E20E700A FOREIGN KEY (tableau_absences_id) REFERENCES tableau_absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_absences_absences DROP FOREIGN KEY FK_266F22A720E700A');
        $this->addSql('ALTER TABLE tableau_absences_absences DROP FOREIGN KEY FK_266F22A79A5BDCB7');
        $this->addSql('ALTER TABLE tableau_notes_absences DROP FOREIGN KEY FK_41F3971B9A5BDCB7');
        $this->addSql('ALTER TABLE tableau_notes_absences DROP FOREIGN KEY FK_41F3971BE087BA45');
        $this->addSql('DROP TABLE tableau_absences_absences');
        $this->addSql('DROP TABLE tableau_notes_absences');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau_absences_absences (tableau_absences_id INT NOT NULL, absences_id INT NOT NULL, INDEX IDX_266F22A720E700A (tableau_absences_id), INDEX IDX_266F22A79A5BDCB7 (absences_id), PRIMARY KEY(tableau_absences_id, absences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tableau_notes_absences (tableau_notes_id INT NOT NULL, absences_id INT NOT NULL, INDEX IDX_41F3971BE087BA45 (tableau_notes_id), INDEX IDX_41F3971B9A5BDCB7 (absences_id), PRIMARY KEY(tableau_notes_id, absences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tableau_absences_absences ADD CONSTRAINT FK_266F22A720E700A FOREIGN KEY (tableau_absences_id) REFERENCES tableau_absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_absences_absences ADD CONSTRAINT FK_266F22A79A5BDCB7 FOREIGN KEY (absences_id) REFERENCES absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_notes_absences ADD CONSTRAINT FK_41F3971B9A5BDCB7 FOREIGN KEY (absences_id) REFERENCES absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_notes_absences ADD CONSTRAINT FK_41F3971BE087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE absences_tableau_absences DROP FOREIGN KEY FK_FC0ACC7E9A5BDCB7');
        $this->addSql('ALTER TABLE absences_tableau_absences DROP FOREIGN KEY FK_FC0ACC7E20E700A');
        $this->addSql('DROP TABLE absences_tableau_absences');
    }
}
