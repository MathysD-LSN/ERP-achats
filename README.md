Installation du projet ERP-Achats
Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants installés :

PHP 8.0+

Composer : Télécharger Composer

PostgreSQL (si vous utilisez PostgreSQL comme base de données)

Installation du projet
1. Cloner le projet
Clonez le dépôt dans un dossier de votre choix :

bash
Copier
Modifier
git clone https://votre-repository-url.git
cd erp-achats
2. Installer les dépendances PHP
Exécutez la commande suivante pour installer les dépendances via Composer :

bash
Copier
Modifier
composer install
3. Configurer l'environnement
Copiez le fichier .env en .env.local pour personnaliser votre configuration locale, en particulier la base de données :

bash
Copier
Modifier
cp .env .env.local
Modifiez la variable DATABASE_URL dans le fichier .env.local pour correspondre à votre base de données PostgreSQL :

bash
Copier
Modifier
DATABASE_URL="pgsql://user:password@127.0.0.1:5432/erp_achats"
Remplacez user, password et erp_achats par les informations de votre base de données.

4. Créer la base de données
Créez la base de données et appliquez les migrations :

bash
Copier
Modifier
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
5. Lancer l'application
Lancez le serveur Symfony avec la commande suivante :

bash
Copier
Modifier
php bin/console server:run
Vous pouvez maintenant accéder à l'application dans votre navigateur à l'adresse suivante :

arduino
Copier
Modifier
http://localhost:8000
