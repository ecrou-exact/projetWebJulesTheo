<?php
// Vérifier si le tableau des utilisateurs existe déjà dans la session
if (!isset($_SESSION['utilisateur'])) {
    $_SESSION['utilisateur'] = []; // Initialiser le tableau des utilisateurs dans la session
}

function utilisateurExiste($login)
{
    // Informations de connexion à la base de données
    $host = "localhost";
    $dbname = "gestion_cocktails";
    $username = "root";
    $password = "";

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Préparer la requête pour vérifier si l'utilisateur existe
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE user = :user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user' => htmlspecialchars($login)]);

    // Récupérer le nombre de résultats
    $count = $stmt->fetchColumn();
    // Retourner vrai si l'utilisateur existe, faux sinon
    return $count > 0;
}



// Fonction pour ajouter un utilisateur à la base de données
function ajouterUtilisateur($login, $prenom, $motDePasse, $age)
{
    // Informations de connexion à la base de données
    $host = "localhost";
    $dbname = "gestion_cocktails";
    $username = "root";
    $password = "";

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Validation des entrées
    $userBD = htmlspecialchars($login);
    $prenomBD = htmlspecialchars($prenom);
    $motDePasseBD = $motDePasse;
    $ageBD = $age;


    // Requête SQL pour insérer l'utilisateur
    $sql = "INSERT INTO utilisateurs (user, prenom, mot_de_passe, age) VALUES (:user, :prenom, :motDePasse, :age)";
    $stmt = $pdo->prepare($sql);

    // Exécution de la requête
    try {
        $stmt->execute([
            ':user' => $userBD,
            ':prenom' => $prenomBD,
            ':motDePasse' => $motDePasseBD,
            ':age' => $ageBD
        ]);
        echo "Utilisateur ajouté avec succès !";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
}



function verifierFormatLogin($login)
{
    // Expression régulière : uniquement lettres (A-Z, a-z) et chiffres (0-9)
    if (preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        return true; // Login valide
    } else {
        return false; // Login invalide
    }
}

function verifierFormatPrenom($prenom)
{
    // Expression régulière : lettres avec accents, majuscules/minuscules, espaces et tirets autorisés
    if (preg_match('/^[\p{L} \-]+$/u', $prenom)) {
        return true; // Prénom valide
    } else {
        return false; // Prénom invalide
    }
}
function verifierFormatAge($age)
{
    // Vérifier si c'est un entier et s'il est dans une plage raisonnable (1-120 ans)
    if (preg_match('/^[0-9]+$/', $age) && $age > 0 && $age <= 120) {
        return true; // Âge valide
    } else {
        return false; // Âge invalide
    }
}


function verifierUtilisateurMotDePasseConnection($login, $motDePasse)
{
    // Informations de connexion à la base de données
    $host = "localhost"; // Remplace par ton hôte
    $dbname = "gestion_cocktails"; // Remplace par le nom de ta base de données
    $username = "root"; // Remplace par ton utilisateur MySQL
    $password = ""; // Remplace par ton mot de passe MySQL

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Requête pour récupérer l'utilisateur avec ce login et ce mot de passe en clair
    $sql = "SELECT * FROM utilisateurs WHERE user = :user AND mot_de_passe = :motDePasse";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user' => htmlspecialchars($login),
        ':motDePasse' => $motDePasse // Pas de hash car les mots de passe sont stockés en clair
    ]);

    // Vérifier si un utilisateur correspondant est trouvé
    if ($stmt->rowCount() > 0) {
        return true; // Utilisateur trouvé
    } else {
        return false; // Identifiants incorrects
    }
}


function recupererUtilisateur($login)
{
    // Informations de connexion à la base de données
    $host = "localhost"; // Remplace par ton hôte
    $dbname = "gestion_cocktails"; // Remplace par le nom de ta base de données
    $username = "root"; // Remplace par ton utilisateur MySQL
    $password = ""; // Remplace par ton mot de passe MySQL

    try {
        // Connexion à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT user, prenom, mot_de_passe, age FROM utilisateurs WHERE user = :user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user' => htmlspecialchars($login)]);

    // Vérifier si un utilisateur a été trouvé
    if ($utilisateur = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Stocker les informations dans la session
        $_SESSION['utilisateur']['login'] = $utilisateur['user'];
        $_SESSION['utilisateur']['prenom'] = $utilisateur['prenom'];
        $_SESSION['utilisateur']['motDePasse'] = $utilisateur['mot_de_passe'];
        $_SESSION['utilisateur']['age'] = $utilisateur['age'];
        $_SESSION['utilisateur']['connecte'] = true;

        return true; // Succès
    } else {
        return false; // Utilisateur non trouvé
    }
}


function mettreAJourUtilisateur($login, $nouveauLogin, $nouveauPrenom, $nouveauMotDePasse, $nouvelAge)
{
    // Informations de connexion à la base de données
    $host = "localhost"; // Remplace par ton hôte
    $dbname = "gestion_cocktails"; // Remplace par le nom de ta base de données
    $username = "root"; // Remplace par ton utilisateur MySQL
    $password = ""; // Remplace par ton mot de passe MySQL

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    $sql = "SELECT id FROM utilisateurs WHERE user = :user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user' => htmlspecialchars($login)]);

    if ($utilisateur = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idUtilisateurs = $utilisateur['id'];
        $sqlUpdate = "UPDATE utilisateurs SET user = :newUser, prenom = :prenom, mot_de_passe = :motDePasse, age = :age WHERE id = :id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':id' => $idUtilisateurs,
            ':newUser' => htmlspecialchars($nouveauLogin),
            ':prenom' => htmlspecialchars($nouveauPrenom),
            ':motDePasse' => $nouveauMotDePasse,
            ':age' => $nouvelAge
        ]);


        $_SESSION['utilisateur']['login'] = $nouveauLogin;
        $_SESSION['utilisateur']['prenom'] = $nouveauPrenom;
        $_SESSION['utilisateur']['motDePasse'] = $nouveauMotDePasse;
        $_SESSION['utilisateur']['age'] = $nouvelAge;





        return $idUtilisateurs;
    } else {
        return false;
    }
}
