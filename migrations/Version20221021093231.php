<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021093231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprises ADD emailrepresentant1 VARCHAR(255) DEFAULT NULL, ADD emailrepresentant2 VARCHAR(255) DEFAULT NULL, ADD numerotelephone1 VARCHAR(255) DEFAULT NULL, ADD numeortelephone2 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprises DROP emailrepresentant1, DROP emailrepresentant2, DROP numerotelephone1, DROP numeortelephone2');
    }
}
