<?php
session_start();
include "user.php";




// On prépare les potentielles erreurs lors de la vérification de la saisie de profil ou même inscription
$_SESSION['erreurPrenom'] = '';
$_SESSION['erreurAge'] = '';
$_SESSION['erreurLogin'] = '';

//ici on fait la vérification des différents éléments du formulaire (inscription et profil)
if ((isset($_POST['afficheProfil']) && $_POST['afficheProfil'] == 'update') ||
    (isset($_POST['afficheInscription']) && $_POST['afficheInscription'] == 'inscrit')
) {
    $valide = true;


    if (isset($_POST['login']) && verifierFormatLogin($_POST['login']) == false || isset($_POST['loginProfil']) && verifierFormatLogin($_POST['loginProfil'])) {
        $valide = false;
        $_SESSION['erreurLogin'] = 'le login peut être composé de lettres non accentuées, minuscules ou MAJUSCULES, et/ou de chiffres ;';
    }
    if (isset($_POST['prenom']) && verifierFormatPrenom($_POST['prenom']) == false || isset($_POST['prenomProfil']) && verifierFormatPrenom($_POST['prenomProfil']) == false) {
        $valide = false;
        $_SESSION['erreurPrenom'] = 'le prénom est composés de lettres minuscules et/ou de 
                                    lettres MAJUSCULES, ainsi que les caractères « - », «
                                    » (espace). Les lettres peuvent être accentuées.';
    }
    if (isset($_POST['age']) && verifierFormatAge($_POST['age']) == false || isset($_POST['ageProfil']) && verifierFormatAge($_POST['ageProfil']) == false) {
        $valide = false;
        $_SESSION['erreurAge'] = 'l’âge est une valeur entière.';
    }


    if (isset($_POST['afficheProfil']) && $_POST['afficheProfil'] == 'update') {
        if ($valide == true) {
            $m = mettreAJourUtilisateur($_SESSION['utilisateur']['login'], $_POST['login'], $_POST['prenom'], $_POST['motDePasse'], $_POST['age']);
            echo "mise à jour effectués ";
            echo $m;
        } else {
            echo "mise à jour non effectués ";
        }
    }



    if (isset($_POST['afficheInscription']) && $_POST['afficheInscription'] == 'inscrit') {
        if ($valide == true) {
            if (!utilisateurExiste($_POST['login'])) {
                ajouterUtilisateur($_POST['login'], $_POST['prenom'], $_POST['motDePasse'], $_POST['age']);
            } else {
                echo "Utilisateur existe déjà ! ";
            }
        }
    }
}
if (isset($_POST['connexion']) && isset($_POST['loginConnexion']) && isset($_POST['motDePasseConnexion'])) {
    if (verifierUtilisateurMotDePasseConnection($_POST['loginConnexion'], $_POST['motDePasseConnexion'])) {
        echo 'Connexion réussite';
        //Je mets mes données dans la session
        recupererUtilisateur($_POST['loginConnexion']);
        print_r($_SESSION['utilisateur']);
    } else {
        echo "Login ou mot de passe incorrect.";
    }
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gestion de Cocktails</title>
</head>

<body>
    <div class="barreNavigation">
        <form method="POST">
            <button name="ListeRecettes">Recettes</button>
        </form>
        <?php if (isset($_SESSION["utilisateur"]['connecte']) && $_SESSION["utilisateur"]['connecte'] == true): ?>
            <!-- Affichage du profil si l'utilisateur est connecté -->
            <span><b><?php echo $_SESSION['utilisateur']['login']; ?></b></span>
            <!-- Le bouton profil -->
            <form method="post" action="">
                <button type="submit" name="afficheProfil">Profil</button>
            </form>
            <!-- Formulaire pour la déconnexion -->
            <form action="deconnexion.php" method="post">
                <button type="submit" name="deconnexion">Se déconnecter</button>
            </form>

        <?php else: ?>
            <!-- Formulaire de connexion si l'utilisateur n'est pas connecté -->
            <form class="first-connection" method="post" action="">
                <label for="loginIndex"><span class="obligatoire">*</span>Login&nbsp;:</label><input type="text" id="loginConnexion" name="loginConnexion" required>
                <label for="passwordIndex"><span class="obligatoire">*</span>Mot de passe&nbsp;:</label><input type="password" id="motDePasseConnexion" name="motDePasseConnexion" required>
                <button type="submit" name="connexion">Connexion</button>
            </form>
            <form method="post">
                <button type="submit" name="afficheInscription">S'inscrire</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="erreur"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        <?php endif; ?>

    </div>

    <?php if (isset($_POST["afficheInscription"])) {
        include "inscription.php";
    } else if (isset($_POST["afficheProfil"])) {
        include "profil.php";
    } else if (isset($_POST['ListeRecettes']) || isset($_POST['retour'])  || isset($_GET['ingredient_id']) || isset($_GET['reinitialiser'])) {
        //include  'afficherCocktails.php'; 
    ?>
        <h1>Liste des Recettes</h1>
        <?php
        include 'listesRecette.php';
        ?>
    <?php
    } else { ?>
        <h1>Bienvenue sur l'application de gestion de cocktails</h1>
        <p><a href="install.php" target="_blank">Créer la base de données</a></p>

    <?php
    }
    ?>
</body>

</html>