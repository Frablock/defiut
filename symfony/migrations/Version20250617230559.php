<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617230559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON Defi_Indice');
        $this->addSql('CREATE INDEX IDX_8187619573F00F27 ON Defi_Indice (defi_id)');
        $this->addSql('ALTER TABLE Defi_Indice ADD PRIMARY KEY (defi_id, indice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8187619573F00F27 ON Defi_Indice');
        $this->addSql('DROP INDEX `PRIMARY` ON Defi_Indice');
        $this->addSql('ALTER TABLE Defi_Indice ADD PRIMARY KEY (defi_id)');
    }
}
