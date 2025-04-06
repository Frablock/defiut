<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406164740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents CHANGE date_acces date_acces DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE Indice CHANGE contenu contenu LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur ADD is_verified TINYINT(1) NOT NULL, ADD roles JSON NOT NULL, CHANGE score_total score_total INT DEFAULT 0 NOT NULL, CHANGE creation_date creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE last_co last_co DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi CHANGE description description TEXT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur DROP is_verified, DROP roles, CHANGE score_total score_total INT DEFAULT 0, CHANGE creation_date creation_date DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE last_co last_co DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE Indice CHANGE contenu contenu TEXT NOT NULL');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents CHANGE date_acces date_acces DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}
