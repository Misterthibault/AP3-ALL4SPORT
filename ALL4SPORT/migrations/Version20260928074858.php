<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260928074858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entrepot (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepot_produit (entrepot_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_D23AE53F72831E97 (entrepot_id), INDEX IDX_D23AE53FF347EFB (produit_id), PRIMARY KEY (entrepot_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE magasin_produit (magasin_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_5E1A357B20096AE3 (magasin_id), INDEX IDX_5E1A357BF347EFB (produit_id), PRIMARY KEY (magasin_id, produit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE entrepot_produit ADD CONSTRAINT FK_D23AE53F72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entrepot_produit ADD CONSTRAINT FK_D23AE53FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magasin_produit ADD CONSTRAINT FK_5E1A357B20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magasin_produit ADD CONSTRAINT FK_5E1A357BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entrepot_produit DROP FOREIGN KEY FK_D23AE53F72831E97');
        $this->addSql('ALTER TABLE entrepot_produit DROP FOREIGN KEY FK_D23AE53FF347EFB');
        $this->addSql('ALTER TABLE magasin_produit DROP FOREIGN KEY FK_5E1A357B20096AE3');
        $this->addSql('ALTER TABLE magasin_produit DROP FOREIGN KEY FK_5E1A357BF347EFB');
        $this->addSql('DROP TABLE entrepot');
        $this->addSql('DROP TABLE entrepot_produit');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE magasin_produit');
    }
}
