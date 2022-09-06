<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906135512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau_notes_absences (tableau_notes_id INT NOT NULL, absences_id INT NOT NULL, INDEX IDX_41F3971BE087BA45 (tableau_notes_id), INDEX IDX_41F3971B9A5BDCB7 (absences_id), PRIMARY KEY(tableau_notes_id, absences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tableau_notes_absences ADD CONSTRAINT FK_41F3971BE087BA45 FOREIGN KEY (tableau_notes_id) REFERENCES tableau_notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_notes_absences ADD CONSTRAINT FK_41F3971B9A5BDCB7 FOREIGN KEY (absences_id) REFERENCES absences (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tableau_notes_absences DROP FOREIGN KEY FK_41F3971BE087BA45');
        $this->addSql('ALTER TABLE tableau_notes_absences DROP FOREIGN KEY FK_41F3971B9A5BDCB7');
        $this->addSql('DROP TABLE tableau_notes_absences');
    }
}
