<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831152058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash DROP FOREIGN KEY FK_528BB4DF624B39D');
        $this->addSql('ALTER TABLE trash DROP FOREIGN KEY FK_528BB4DE92F8F78');
        $this->addSql('DROP TABLE trash');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trash (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, objet VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATE NOT NULL, is_read TINYINT(1) NOT NULL, remove_msg TINYINT(1) NOT NULL, INDEX IDX_528BB4DF624B39D (sender_id), INDEX IDX_528BB4DE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE trash ADD CONSTRAINT FK_528BB4DF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE trash ADD CONSTRAINT FK_528BB4DE92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id)');
    }
}
