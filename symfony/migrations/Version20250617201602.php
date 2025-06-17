<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617201602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Defi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, cle VARCHAR(255) NOT NULL, points_recompense INT NOT NULL, categorie VARCHAR(255) NOT NULL, difficulte INT DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_7CE70C6041401D17 (cle), INDEX IDX_7CE70C60A76ED395 (user_id), UNIQUE INDEX id (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Defi_Tag (defi_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C1FBEC273F00F27 (defi_id), INDEX IDX_C1FBEC2BAD26311 (tag_id), PRIMARY KEY(defi_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Defi_Fichier (defi_id INT NOT NULL, fichier_id INT NOT NULL, INDEX IDX_AB61C573F00F27 (defi_id), INDEX IDX_AB61C5F915CFE (fichier_id), PRIMARY KEY(defi_id, fichier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Defi_Indice (ordre INT NOT NULL, defi_id INT NOT NULL, indice_id INT NOT NULL, INDEX IDX_81876195C8C0B132 (indice_id), UNIQUE INDEX un_defi_indice_ordre (defi_id, ordre), PRIMARY KEY(defi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Defi_Utilisateur_Recents (date_acces DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, user_id INT NOT NULL, defi_id INT NOT NULL, INDEX IDX_D57F0360A76ED395 (user_id), INDEX IDX_D57F036073F00F27 (defi_id), INDEX idx_date_acces (date_acces), PRIMARY KEY(user_id, defi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Fichier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_54CB6C836C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Indice (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE indice_defi (indice_id INT NOT NULL, defi_id INT NOT NULL, INDEX IDX_E4AEAA63C8C0B132 (indice_id), INDEX IDX_E4AEAA6373F00F27 (defi_id), PRIMARY KEY(indice_id, defi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3BC4F1636C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE Utilisateur (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, score_total INT DEFAULT 0 NOT NULL, creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, last_co DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, is_verified TINYINT(1) NOT NULL, roles JSON NOT NULL, last_try_date DATETIME DEFAULT NULL, username VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, token_expiration_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9B80EC645126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE defi_valid_utilisateur (id INT AUTO_INCREMENT NOT NULL, date_valid DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Defi ADD CONSTRAINT FK_7CE70C60A76ED395 FOREIGN KEY (user_id) REFERENCES Utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Tag ADD CONSTRAINT FK_C1FBEC273F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Tag ADD CONSTRAINT FK_C1FBEC2BAD26311 FOREIGN KEY (tag_id) REFERENCES Tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Fichier ADD CONSTRAINT FK_AB61C573F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Fichier ADD CONSTRAINT FK_AB61C5F915CFE FOREIGN KEY (fichier_id) REFERENCES Fichier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Indice ADD CONSTRAINT FK_8187619573F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Indice ADD CONSTRAINT FK_81876195C8C0B132 FOREIGN KEY (indice_id) REFERENCES Indice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents ADD CONSTRAINT FK_D57F0360A76ED395 FOREIGN KEY (user_id) REFERENCES Utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents ADD CONSTRAINT FK_D57F036073F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE indice_defi ADD CONSTRAINT FK_E4AEAA63C8C0B132 FOREIGN KEY (indice_id) REFERENCES Indice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE indice_defi ADD CONSTRAINT FK_E4AEAA6373F00F27 FOREIGN KEY (defi_id) REFERENCES Defi (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi DROP FOREIGN KEY FK_7CE70C60A76ED395');
        $this->addSql('ALTER TABLE Defi_Tag DROP FOREIGN KEY FK_C1FBEC273F00F27');
        $this->addSql('ALTER TABLE Defi_Tag DROP FOREIGN KEY FK_C1FBEC2BAD26311');
        $this->addSql('ALTER TABLE Defi_Fichier DROP FOREIGN KEY FK_AB61C573F00F27');
        $this->addSql('ALTER TABLE Defi_Fichier DROP FOREIGN KEY FK_AB61C5F915CFE');
        $this->addSql('ALTER TABLE Defi_Indice DROP FOREIGN KEY FK_8187619573F00F27');
        $this->addSql('ALTER TABLE Defi_Indice DROP FOREIGN KEY FK_81876195C8C0B132');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents DROP FOREIGN KEY FK_D57F0360A76ED395');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents DROP FOREIGN KEY FK_D57F036073F00F27');
        $this->addSql('ALTER TABLE indice_defi DROP FOREIGN KEY FK_E4AEAA63C8C0B132');
        $this->addSql('ALTER TABLE indice_defi DROP FOREIGN KEY FK_E4AEAA6373F00F27');
        $this->addSql('DROP TABLE Defi');
        $this->addSql('DROP TABLE Defi_Tag');
        $this->addSql('DROP TABLE Defi_Fichier');
        $this->addSql('DROP TABLE Defi_Indice');
        $this->addSql('DROP TABLE Defi_Utilisateur_Recents');
        $this->addSql('DROP TABLE Fichier');
        $this->addSql('DROP TABLE Indice');
        $this->addSql('DROP TABLE indice_defi');
        $this->addSql('DROP TABLE Tag');
        $this->addSql('DROP TABLE Utilisateur');
        $this->addSql('DROP TABLE defi_valid_utilisateur');
    }
}
