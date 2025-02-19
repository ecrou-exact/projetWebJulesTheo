<?php
require_once "connexion.php"; // Inclusion de la connexion

$ingredient_id = null;

if (isset($_GET['ingredient_id'])) {
    $ingredient_id = (int) $_GET['ingredient_id'];
}

// Récupération des recettes contenant l'ingrédient sélectionné
$recettes_prioritaires = [];
if ($ingredient_id) {
    $query = $pdo->prepare("
        SELECT DISTINCT r.id, r.titre 
        FROM recettes r
        JOIN recettes_aliments ra ON r.id = ra.recette_id
        WHERE ra.aliment_id = :ingredient_id
        ORDER BY r.titre ASC
    ");
    $query->execute(['ingredient_id' => $ingredient_id]);
    $recettes_prioritaires = $query->fetchAll();
}

// Récupération des autres recettes (celles qui ne contiennent PAS l’ingrédient sélectionné)
$query = $pdo->prepare("
    SELECT DISTINCT r.id, r.titre 
    FROM recettes r
    WHERE r.id NOT IN (
        SELECT recette_id FROM recettes_aliments WHERE aliment_id = :ingredient_id
    )
    ORDER BY r.titre ASC
");
$query->execute(['ingredient_id' => $ingredient_id]);
$autres_recettes = $query->fetchAll();


?>


<?php if ($ingredient_id): ?>
    <h2>Recettes contenant l'ingrédient sélectionné</h2>
    <a href="index.php?reinitialiser=1">
        <button type="button">Réinitialiser la liste</button>
    </a>

<?php endif; ?>

<ul>
    <!-- Affichage des recettes contenant l'ingrédient en premier -->
    <?php foreach ($recettes_prioritaires as $recette): ?>
        <li>
            <a href="detailRecette.php?id=<?= $recette['id'] ?>">
                <?= htmlspecialchars($recette['titre']) ?>
            </a>

            <?php
            // Récupérer les ingrédients de la recette
            $query = $pdo->prepare("
                SELECT a.id, a.nom 
                FROM aliments a
                JOIN recettes_aliments ra ON a.id = ra.aliment_id
                WHERE ra.recette_id = :recette_id
            ");
            $query->execute(['recette_id' => $recette['id']]);
            $ingredients = $query->fetchAll();
            ?>

            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <a href="index.php?ingredient_id=<?= $ingredient['id'] ?>">
                            <?= htmlspecialchars($ingredient['nom']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>

    <!-- Séparateur entre les recettes prioritaires et les autres -->
    <?php if ($ingredient_id && count($recettes_prioritaires) > 0): ?>
        <hr>
        <h2>Autres recettes</h2>
    <?php endif; ?>

    <!-- Affichage des autres recettes -->
    <?php foreach ($autres_recettes as $recette): ?>
        <li>
            <a href="detailRecette.php?id=<?= $recette['id'] ?>">
                <?= htmlspecialchars($recette['titre']) ?>
            </a>

            <?php
            // Récupérer les ingrédients de la recette
            $query = $pdo->prepare("
                SELECT a.id, a.nom 
                FROM aliments a
                JOIN recettes_aliments ra ON a.id = ra.aliment_id
                WHERE ra.recette_id = :recette_id
            ");
            $query->execute(['recette_id' => $recette['id']]);
            $ingredients = $query->fetchAll();
            ?>

            <ul>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <a href="index.php?ingredient_id=<?= $ingredient['id'] ?>">
                            <?= htmlspecialchars($ingredient['nom']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>