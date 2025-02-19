<?php
require_once "connexion.php"; // Inclusion de la connexion

// Vérifier si un ID est présent dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Recette invalide !");
}

$id = $_GET['id'];

// Récupération des détails de la recette
$query = $pdo->prepare("SELECT titre, preparation FROM recettes WHERE id = :id");
$query->execute(['id' => $id]);
$recette = $query->fetch();

// Si la recette n'existe pas
if (!$recette) {
    die("Recette non trouvée !");
}

// Récupération des ingrédients associés à la recette
$query = $pdo->prepare("
    SELECT a.nom 
    FROM aliments a
    JOIN recettes_aliments ra ON a.id = ra.aliment_id
    WHERE ra.recette_id = :id
");
$query->execute(['id' => $id]);
$ingredients = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recette['titre']) ?></title>
</head>

<body>
    <h1><?= htmlspecialchars($recette['titre']) ?></h1>

    <h2>Ingrédients :</h2>
    <ul>
        <?php foreach ($ingredients as $ingredient): ?>
            <li><?= htmlspecialchars($ingredient['nom']) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Préparation :</h2>
    <p><?= htmlspecialchars($recette['preparation']) ?></p>


    <form action="index.php" method="post">
        <button type="submit" name="retour">Retour à la liste des recettes</button>
    </form>
</body>

</html>