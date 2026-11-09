<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261109104600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add product quantity to the command-product association.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('commande_produit') && !$schema->getTable('commande_produit')->hasColumn('quantite')) {
            $this->addSql('ALTER TABLE commande_produit ADD quantite INT NOT NULL DEFAULT 1');
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('commande_produit') && $schema->getTable('commande_produit')->hasColumn('quantite')) {
            $this->addSql('ALTER TABLE commande_produit DROP quantite');
        }
    }
}
