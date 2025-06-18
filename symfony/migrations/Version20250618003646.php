<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618003646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE defi_valid_utilisateur (id INT AUTO_INCREMENT NOT NULL, date_valid DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Defi ADD defi_valid_utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE Defi ADD CONSTRAINT FK_7CE70C60DFABEAE4 FOREIGN KEY (defi_valid_utilisateur_id) REFERENCES defi_valid_utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_7CE70C60DFABEAE4 ON Defi (defi_valid_utilisateur_id)');
        $this->addSql('ALTER TABLE Utilisateur ADD defi_valid_utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur ADD CONSTRAINT FK_9B80EC64DFABEAE4 FOREIGN KEY (defi_valid_utilisateur_id) REFERENCES defi_valid_utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_9B80EC64DFABEAE4 ON Utilisateur (defi_valid_utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE defi_valid_utilisateur');
        $this->addSql('ALTER TABLE Utilisateur DROP FOREIGN KEY FK_9B80EC64DFABEAE4');
        $this->addSql('DROP INDEX IDX_9B80EC64DFABEAE4 ON Utilisateur');
        $this->addSql('ALTER TABLE Utilisateur DROP defi_valid_utilisateur_id');
        $this->addSql('ALTER TABLE Defi DROP FOREIGN KEY FK_7CE70C60DFABEAE4');
        $this->addSql('DROP INDEX IDX_7CE70C60DFABEAE4 ON Defi');
        $this->addSql('ALTER TABLE Defi DROP defi_valid_utilisateur_id');
    }
}
