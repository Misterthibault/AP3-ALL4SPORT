<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921072349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) DEFAULT NULL, ADD date_naissance DATE NOT NULL');
        $this->addSql('ALTER TABLE enfant ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA219EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_34B70CA219EB6921 ON enfant (client_id)');
        $this->addSql('ALTER TABLE historique_commande ADD commande_tel_client VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP nom, DROP prenom, DROP adresse, DROP telephone, DROP date_naissance');
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA219EB6921');
        $this->addSql('DROP INDEX IDX_34B70CA219EB6921 ON enfant');
        $this->addSql('ALTER TABLE enfant DROP client_id');
        $this->addSql('ALTER TABLE historique_commande DROP commande_tel_client');
    }
}
