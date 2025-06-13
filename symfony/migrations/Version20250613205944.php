<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613205944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX id ON Defi (id)');
        $this->addSql('ALTER TABLE Defi RENAME INDEX un_defi_cle TO UNIQ_7CE70C6041401D17');
        $this->addSql('ALTER TABLE Defi RENAME INDEX fk_defi_utilisateur TO IDX_7CE70C60A76ED395');
        $this->addSql('ALTER TABLE Defi_Tag RENAME INDEX fk_defi_tag_tag TO IDX_C1FBEC2BAD26311');
        $this->addSql('ALTER TABLE Defi_Fichier RENAME INDEX fk_defi_fichier_fichier TO IDX_AB61C5F915CFE');
        $this->addSql('ALTER TABLE Defi_Indice RENAME INDEX fk_defi_indice_indice TO IDX_81876195C8C0B132');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents RENAME INDEX fk_recents_defi TO IDX_D57F036073F00F27');
        $this->addSql('ALTER TABLE Fichier RENAME INDEX un_fichier_nom TO UNIQ_54CB6C836C6E55B5');
        $this->addSql('ALTER TABLE Tag RENAME INDEX un_tag_nom TO UNIQ_3BC4F1636C6E55B5');
        $this->addSql('ALTER TABLE Utilisateur ADD last_try_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Utilisateur RENAME INDEX un_utilisateur_mail TO UNIQ_9B80EC645126AC48');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Tag RENAME INDEX uniq_3bc4f1636c6e55b5 TO un_tag_nom');
        $this->addSql('DROP INDEX id ON Defi');
        $this->addSql('ALTER TABLE Defi RENAME INDEX uniq_7ce70c6041401d17 TO un_defi_cle');
        $this->addSql('ALTER TABLE Defi RENAME INDEX idx_7ce70c60a76ed395 TO fk_defi_utilisateur');
        $this->addSql('ALTER TABLE Fichier RENAME INDEX uniq_54cb6c836c6e55b5 TO un_fichier_nom');
        $this->addSql('ALTER TABLE Utilisateur DROP last_try_date');
        $this->addSql('ALTER TABLE Utilisateur RENAME INDEX uniq_9b80ec645126ac48 TO un_utilisateur_mail');
        $this->addSql('ALTER TABLE Defi_Fichier RENAME INDEX idx_ab61c5f915cfe TO fk_defi_fichier_fichier');
        $this->addSql('ALTER TABLE Defi_Indice RENAME INDEX idx_81876195c8c0b132 TO fk_defi_indice_indice');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents RENAME INDEX idx_d57f036073f00f27 TO fk_recents_defi');
        $this->addSql('ALTER TABLE Defi_Tag RENAME INDEX idx_c1fbec2bad26311 TO fk_defi_tag_tag');
    }
}
