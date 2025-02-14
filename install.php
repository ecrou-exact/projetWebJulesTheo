<?php

// Paramètres de connexion à MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_cocktails');
define('DB_USER', 'root');
define('DB_PASSWORD', '');


try {
    //fonctione
    // Connexion à MySQL
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);

    // Création des tables
    $pdo->exec("
            CREATE TABLE IF NOT EXISTS Recettes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titre VARCHAR(255) NOT NULL,
                preparation TEXT NOT NULL,
                detailIngredient TEXT 
            ) ENGINE=InnoDB;
        ");

    $pdo->exec("
            CREATE TABLE IF NOT EXISTS Aliments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL UNIQUE
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    $pdo->exec("
            CREATE TABLE IF NOT EXISTS Recettes_Aliments (
                recette_id INT NOT NULL,
                aliment_id INT NOT NULL,
                PRIMARY KEY (recette_id, aliment_id),
                FOREIGN KEY (recette_id) REFERENCES Recettes(id) ON DELETE CASCADE,
                FOREIGN KEY (aliment_id) REFERENCES Aliments(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ");

    $pdo->exec("
            CREATE TABLE IF NOT EXISTS utilisateurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(50) NOT NULL,
                mot_de_passe VARCHAR(255) NOT NULL,
                prenom VARCHAR(50),
                age INT
            ) ENGINE=InnoDB;
        ");

    // Inclusion des données
    include 'Donnees.inc.php';

    /**
     * inclusion des Aliments dans la table aliment
     */

    $alimentTableau = [];

    foreach ($Recettes as $recette) {
        // Insertion de la recette
        $stmt = $pdo->prepare("INSERT INTO recettes (titre, preparation, detailIngredient) VALUES (:titre, :preparation , :detailIngredient)");
        $stmt->execute([
            ':titre' => $recette['titre'],
            ':preparation' => $recette['preparation'],
            ':detailIngredient' => $recette['ingredients']
        ]);
        $recette_id = $pdo->lastInsertId();

        foreach ($recette['index'] as $aliment) {
            // Vérifie si l'aliment n'est pas déjà dans le tableau
            if (!in_array($aliment, $alimentTableau)) {
                $alimentTableau[] = $aliment;

                // Insère l'aliment dans la base de données
                $stmt = $pdo->prepare("INSERT INTO Aliments (nom) VALUES (:nom)");
                $stmt->execute([':nom' => $aliment]);
                $aliment_id = $pdo->lastInsertId(); // Récupère l'ID de l'aliment inséré
            } else {
                // Récupère l'ID de l'aliment si déjà existant dans la base de données
                $stmt = $pdo->prepare("SELECT id FROM Aliments WHERE nom = :nom");
                $stmt->execute([':nom' => $aliment]);
                $aliment_id = $stmt->fetchColumn(); // Récupère l'ID de l'aliment existant
            }

            // Lier l'aliment à la recette dans la table associée
            $stmt = $pdo->prepare("INSERT INTO Recettes_Aliments (recette_id, aliment_id) VALUES (:recette_id, :aliment_id)");
            $stmt->execute([
                ':recette_id' => $recette_id,
                ':aliment_id' => $aliment_id
            ]);
        }
    }



    echo "La base de données et les tables ont été créées avec succès, et les données ont été insérées.";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}




    // les coeurs de rectte favorite d'un utilisateur connecté est affiche mais pas si il n'est pas connecté

    // id pour les utilisateur !!!!!!!!! pas login (login valeur unique 

    // mot de passe pas possible d'etre changé 


    // gestion update ne suffit pas ! on doit vérifier que c'est bien le user qui modifie ses modifs)

    // 6 pas besoin d'admin on donne accet à tout le monde comme liste de recette 

    //interface:
    //créer base de donnée + affichage des recettes  
