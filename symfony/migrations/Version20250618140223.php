<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618140223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi DROP FOREIGN KEY FK_7CE70C60DFABEAE4');
        $this->addSql('DROP INDEX IDX_7CE70C60DFABEAE4 ON Defi');
        $this->addSql('ALTER TABLE Defi DROP defi_valid_utilisateur_id');
        $this->addSql('ALTER TABLE Utilisateur DROP FOREIGN KEY FK_9B80EC64DFABEAE4');
        $this->addSql('DROP INDEX IDX_9B80EC64DFABEAE4 ON Utilisateur');
        $this->addSql('ALTER TABLE Utilisateur DROP defi_valid_utilisateur_id');
        $this->addSql('ALTER TABLE defi_valid_utilisateur ADD user_id INT NOT NULL, ADD defi_id INT NOT NULL, CHANGE date_valid date_valid DATETIME NOT NULL');
        $this->addSql('ALTER TABLE defi_valid_utilisateur ADD CONSTRAINT FK_A33DC038A76ED395 FOREIGN KEY (user_id) REFERENCES Utilisateur (id)');
        $this->addSql('ALTER TABLE defi_valid_utilisateur ADD CONSTRAINT FK_A33DC03873F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id)');
        $this->addSql('CREATE INDEX IDX_A33DC038A76ED395 ON defi_valid_utilisateur (user_id)');
        $this->addSql('CREATE INDEX IDX_A33DC03873F00F27 ON defi_valid_utilisateur (defi_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE defi_valid_utilisateur DROP FOREIGN KEY FK_A33DC038A76ED395');
        $this->addSql('ALTER TABLE defi_valid_utilisateur DROP FOREIGN KEY FK_A33DC03873F00F27');
        $this->addSql('DROP INDEX IDX_A33DC038A76ED395 ON defi_valid_utilisateur');
        $this->addSql('DROP INDEX IDX_A33DC03873F00F27 ON defi_valid_utilisateur');
        $this->addSql('ALTER TABLE defi_valid_utilisateur DROP user_id, DROP defi_id, CHANGE date_valid date_valid DATE NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur ADD defi_valid_utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Utilisateur ADD CONSTRAINT FK_9B80EC64DFABEAE4 FOREIGN KEY (defi_valid_utilisateur_id) REFERENCES defi_valid_utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9B80EC64DFABEAE4 ON Utilisateur (defi_valid_utilisateur_id)');
        $this->addSql('ALTER TABLE Defi ADD defi_valid_utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Defi ADD CONSTRAINT FK_7CE70C60DFABEAE4 FOREIGN KEY (defi_valid_utilisateur_id) REFERENCES defi_valid_utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7CE70C60DFABEAE4 ON Defi (defi_valid_utilisateur_id)');
    }
}
