<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220930134000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiants ADD entreprise_id INT DEFAULT NULL, ADD cursus VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiants ADD CONSTRAINT FK_227C02EBA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprises (id)');
        $this->addSql('CREATE INDEX IDX_227C02EBA4AEAFEA ON etudiants (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiants DROP FOREIGN KEY FK_227C02EBA4AEAFEA');
        $this->addSql('DROP INDEX IDX_227C02EBA4AEAFEA ON etudiants');
        $this->addSql('ALTER TABLE etudiants DROP entreprise_id, DROP cursus');
    }
}
