<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906135328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau_absences (id INT AUTO_INCREMENT NOT NULL, dateabsence DATETIME DEFAULT NULL, retard DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau_absences_etudiants (tableau_absences_id INT NOT NULL, etudiants_id INT NOT NULL, INDEX IDX_471374E020E700A (tableau_absences_id), INDEX IDX_471374E0A873A5C6 (etudiants_id), PRIMARY KEY(tableau_absences_id, etudiants_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tableau_absences_etudiants ADD CONSTRAINT FK_471374E020E700A FOREIGN KEY (tableau_absences_id) REFERENCES tableau_absences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tableau_absences_etudiants ADD CONSTRAINT FK_471374E0A873A5C6 FOREIGN KEY (etudiants_id) REFERENCES etudiants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE files ADD tableau_absences_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_635405920E700A FOREIGN KEY (tableau_absences_id) REFERENCES tableau_absences (id)');
        $this->addSql('CREATE INDEX IDX_635405920E700A ON files (tableau_absences_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_635405920E700A');
        $this->addSql('ALTER TABLE tableau_absences_etudiants DROP FOREIGN KEY FK_471374E020E700A');
        $this->addSql('ALTER TABLE tableau_absences_etudiants DROP FOREIGN KEY FK_471374E0A873A5C6');
        $this->addSql('DROP TABLE tableau_absences');
        $this->addSql('DROP TABLE tableau_absences_etudiants');
        $this->addSql('DROP INDEX IDX_635405920E700A ON files');
        $this->addSql('ALTER TABLE files DROP tableau_absences_id');
    }
}
