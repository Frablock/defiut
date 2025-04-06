<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406165047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Defi_Fichier RENAME INDEX idx_ab61c573f00f27 TO IDX_D72783A173F00F27');
        $this->addSql('ALTER TABLE Defi_Fichier RENAME INDEX fk_defi_fichier_fichier TO IDX_D72783A1F915CFE');
        $this->addSql('ALTER TABLE Defi_Indice RENAME INDEX fk_defi_indice_indice TO IDX_81876195C8C0B132');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents CHANGE date_acces date_acces DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents RENAME INDEX fk_recents_defi TO IDX_D57F036073F00F27');
        $this->addSql('ALTER TABLE Fichier RENAME INDEX un_fichier_nom TO UNIQ_54CB6C836C6E55B5');
        $this->addSql('ALTER TABLE Indice CHANGE contenu contenu LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE Tag RENAME INDEX un_tag_nom TO UNIQ_3BC4F1636C6E55B5');
        $this->addSql('ALTER TABLE Utilisateur ADD is_verified TINYINT(1) NOT NULL, ADD roles JSON NOT NULL, CHANGE score_total score_total INT DEFAULT 0 NOT NULL, CHANGE creation_date creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE last_co last_co DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur RENAME INDEX un_utilisateur_mail TO UNIQ_9B80EC645126AC48');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Indice CHANGE contenu contenu TEXT NOT NULL');
        $this->addSql('ALTER TABLE Fichier RENAME INDEX uniq_54cb6c836c6e55b5 TO un_fichier_nom');
        $this->addSql('ALTER TABLE Utilisateur DROP is_verified, DROP roles, CHANGE score_total score_total INT DEFAULT 0, CHANGE creation_date creation_date DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE last_co last_co DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE Utilisateur RENAME INDEX uniq_9b80ec645126ac48 TO un_utilisateur_mail');
        $this->addSql('ALTER TABLE defi_fichier RENAME INDEX idx_d72783a1f915cfe TO fk_defi_fichier_fichier');
        $this->addSql('ALTER TABLE defi_fichier RENAME INDEX idx_d72783a173f00f27 TO IDX_AB61C573F00F27');
        $this->addSql('ALTER TABLE Tag RENAME INDEX uniq_3bc4f1636c6e55b5 TO un_tag_nom');
        $this->addSql('ALTER TABLE Defi_Indice RENAME INDEX idx_81876195c8c0b132 TO fk_defi_indice_indice');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents CHANGE date_acces date_acces DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE Defi_Utilisateur_Recents RENAME INDEX idx_d57f036073f00f27 TO fk_recents_defi');
    }
}
