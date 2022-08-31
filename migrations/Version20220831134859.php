<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831134859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD remove_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9650FECFF4 FOREIGN KEY (remove_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E9650FECFF4 ON messages (remove_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9650FECFF4');
        $this->addSql('DROP INDEX IDX_DB021E9650FECFF4 ON messages');
        $this->addSql('ALTER TABLE messages DROP remove_id');
    }
}
