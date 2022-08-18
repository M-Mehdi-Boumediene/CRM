<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619115821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenants ADD codepostale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervenants ADD CONSTRAINT FK_79A002C047AA4555 FOREIGN KEY (codepostale_id) REFERENCES codepostal (id)');
        $this->addSql('CREATE INDEX IDX_79A002C047AA4555 ON intervenants (codepostale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenants DROP FOREIGN KEY FK_79A002C047AA4555');
        $this->addSql('DROP INDEX IDX_79A002C047AA4555 ON intervenants');
        $this->addSql('ALTER TABLE intervenants DROP codepostale_id');
    }
}
