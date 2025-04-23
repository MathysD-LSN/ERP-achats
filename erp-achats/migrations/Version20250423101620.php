<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250423101620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables ERP et des triggers d’alerte';
    }

    public function up(Schema $schema): void
    {
        // Tables
        $this->addSql('CREATE TABLE Client (
            Id_Client SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            phone VARCHAR(20) NOT NULL
        )');

        $this->addSql('CREATE TABLE Produit (
            Id_Produit SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            quantite_stock INT NOT NULL,
            prix_unitaire DECIMAL(10,2) NOT NULL
        )');

        $this->addSql('CREATE TABLE Stock (
            id_stock SERIAL PRIMARY KEY,
            id_produit INT NOT NULL,
            quantite INT NOT NULL,
            FOREIGN KEY (id_produit) REFERENCES Produit(Id_Produit)
        )');

        $this->addSql('CREATE TABLE Achat (
            id_achat SERIAL PRIMARY KEY,
            date_achat DATE NOT NULL,
            montant DECIMAL(10,2) NOT NULL,
            fournisseur VARCHAR(100) NOT NULL,
            id_stock INT NOT NULL REFERENCES Stock(id_stock)
        )');

        $this->addSql('CREATE TABLE Bon_livraison (
            Id_bon SERIAL PRIMARY KEY,
            date_livraison DATE NOT NULL,
            montant_total DECIMAL(10,2),
            Id_Client INT NOT NULL REFERENCES Client(Id_Client)
        )');

        $this->addSql('CREATE TABLE Ligne_bon (
            Id_Ligne SERIAL PRIMARY KEY,
            Id_bon INT NOT NULL REFERENCES Bon_livraison(Id_bon) ON DELETE CASCADE,
            Id_Produit INT REFERENCES Produit(Id_Produit),
            quantité INT NOT NULL,
            prix_unitaire DECIMAL(10,2) NOT NULL
        )');

        $this->addSql('CREATE TABLE Alerte (
            Id_Alerte SERIAL PRIMARY KEY,
            id_achat INT REFERENCES Achat(id_achat),
            Id_bon INT REFERENCES Bon_livraison(Id_bon),
            message TEXT NOT NULL,
            date_alerte TIMESTAMP DEFAULT NOW()
        )');

        // Trigger Achat > 10000€
        $this->addSql("CREATE OR REPLACE FUNCTION achat_alerte_trigger_fn()
        RETURNS TRIGGER AS $$
        BEGIN
            IF NEW.montant > 10000 THEN
                INSERT INTO Alerte(id_achat, message)
                VALUES (NEW.id_achat, 'Achat supérieur à 10 000 €');
            END IF;
            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql");

        $this->addSql("CREATE TRIGGER achat_alerte_trigger
        AFTER INSERT ON Achat
        FOR EACH ROW
        EXECUTE FUNCTION achat_alerte_trigger_fn()");

        // Trigger Achat trop ancien
        $this->addSql("CREATE OR REPLACE FUNCTION ancien_achat_alerte_trigger_fn()
        RETURNS TRIGGER AS $$
        DECLARE
            derniere_date DATE;
        BEGIN
            SELECT MAX(date_achat) INTO derniere_date
            FROM Achat
            WHERE id_stock = NEW.id_stock;

            IF derniere_date IS NOT NULL AND derniere_date < NOW() - INTERVAL '6 months' THEN
                INSERT INTO Alerte(id_achat, message)
                VALUES (NEW.id_achat, 'Achat trop ancien : plus de 6 mois');
            END IF;
            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql");

        $this->addSql("CREATE TRIGGER ancien_achat_alerte_trigger
        AFTER INSERT ON Achat
        FOR EACH ROW
        EXECUTE FUNCTION ancien_achat_alerte_trigger_fn()");

        // Trigger Vente > 3000€
        $this->addSql("CREATE OR REPLACE FUNCTION vente_alerte_fn()
        RETURNS TRIGGER AS $$
        BEGIN
            IF NEW.montant_total > 3000 THEN
                INSERT INTO Alerte(Id_bon, message)
                VALUES (NEW.Id_bon, 'Vente dépassant 3000 €');
            END IF;
            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql");

        $this->addSql("CREATE TRIGGER vente_alerte_trigger
        AFTER INSERT ON Bon_livraison
        FOR EACH ROW
        EXECUTE FUNCTION vente_alerte_fn()");
    }

    public function down(Schema $schema): void
    {
        // Tu peux ajouter des DROP TABLE ici si besoin
        $this->addSql('DROP TABLE IF EXISTS Ligne_bon');
        $this->addSql('DROP TABLE IF EXISTS Bon_livraison');
        $this->addSql('DROP TABLE IF EXISTS Achat');
        $this->addSql('DROP TABLE IF EXISTS Stock');
        $this->addSql('DROP TABLE IF EXISTS Produit');
        $this->addSql('DROP TABLE IF EXISTS Client');
        $this->addSql('DROP TABLE IF EXISTS Alerte');
        $this->addSql('DROP FUNCTION IF EXISTS achat_alerte_trigger_fn');
        $this->addSql('DROP FUNCTION IF EXISTS ancien_achat_alerte_trigger_fn');
        $this->addSql('DROP FUNCTION IF EXISTS vente_alerte_fn');
    }
}
