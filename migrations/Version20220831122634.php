<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831122634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash ADD sender_id INT DEFAULT NULL, ADD recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trash ADD CONSTRAINT FK_528BB4DF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE trash ADD CONSTRAINT FK_528BB4DE92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_528BB4DF624B39D ON trash (sender_id)');
        $this->addSql('CREATE INDEX IDX_528BB4DE92F8F78 ON trash (recipient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash DROP FOREIGN KEY FK_528BB4DF624B39D');
        $this->addSql('ALTER TABLE trash DROP FOREIGN KEY FK_528BB4DE92F8F78');
        $this->addSql('DROP INDEX IDX_528BB4DF624B39D ON trash');
        $this->addSql('DROP INDEX IDX_528BB4DE92F8F78 ON trash');
        $this->addSql('ALTER TABLE trash DROP sender_id, DROP recipient_id');
    }
}
