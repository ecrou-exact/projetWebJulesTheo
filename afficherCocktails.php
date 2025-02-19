<?php

/***************************************************AncienCode**************************************************** */
// Fonction de connexion unique à la base de données
function getDatabaseConnection()
{
    static $pdo = null; // Utilisation d'une variable statique pour éviter les reconnections multiples

    if ($pdo === null) {
        $host = "localhost";
        $dbname = "gestion_cocktails";
        $username = "root";
        $password = "";

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    return $pdo;
}

// Fonction pour récupérer toutes les recettes
function getRecettes()
{
    $pdo = getDatabaseConnection();
    $sql = "SELECT id, titre FROM recettes ORDER BY titre";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les aliments associés à une recette par son nom
function getAlimentRecettes($nomRecettes)
{
    $pdo = getDatabaseConnection();

    // Requête pour récupérer l'ID de la recette en fonction de son nom
    $sql = "SELECT id FROM recettes WHERE titre = :titre LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titre' => $nomRecettes]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null; // Retourne null si la recette n'existe pas
    }

    $idRecettes = $result['id'];

    // Requête pour récupérer tous les aliments associés à cette recette
    $sqlAliments = "
        SELECT a.nom 
        FROM aliments a
        JOIN recettes_aliments ra ON a.id = ra.aliment_id
        WHERE ra.recette_id = :recette_id
    ";
    $stmtAliments = $pdo->prepare($sqlAliments);
    $stmtAliments->execute([':recette_id' => $idRecettes]);

    return $stmtAliments->fetchAll(PDO::FETCH_COLUMN);
}
