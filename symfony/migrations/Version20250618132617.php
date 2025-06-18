<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618132617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi CHANGE defi_valid_utilisateur_id defi_valid_utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Utilisateur CHANGE defi_valid_utilisateur_id defi_valid_utilisateur_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Utilisateur CHANGE defi_valid_utilisateur_id defi_valid_utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE Defi CHANGE defi_valid_utilisateur_id defi_valid_utilisateur_id INT NOT NULL');
    }
}
