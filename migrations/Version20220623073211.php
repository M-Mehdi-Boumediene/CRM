<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623073211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenants ADD ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenants ADD CONSTRAINT FK_79A002C0A73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_79A002C0A73F0036 ON intervenants (ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenants DROP FOREIGN KEY FK_79A002C0A73F0036');
        $this->addSql('DROP INDEX IDX_79A002C0A73F0036 ON intervenants');
        $this->addSql('ALTER TABLE intervenants DROP ville_id');
    }
}
