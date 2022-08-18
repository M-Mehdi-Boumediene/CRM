<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619111938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE codepostal ADD villes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE codepostal ADD CONSTRAINT FK_D57F0145286C17BC FOREIGN KEY (villes_id) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_D57F0145286C17BC ON codepostal (villes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE codepostal DROP FOREIGN KEY FK_D57F0145286C17BC');
        $this->addSql('DROP INDEX IDX_D57F0145286C17BC ON codepostal');
        $this->addSql('ALTER TABLE codepostal DROP villes_id');
    }
}
