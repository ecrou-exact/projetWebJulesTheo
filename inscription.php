<!-- Formulaire d'inscription -->
<div class="formulaire-inscription">
    <h2>Inscription</h2>
    <form action="index.php" method="POST">
        <label for="loginInscription"><span class="obligatoire">*</span>Login&nbsp;: </label><input type="text" id="loginInscription" name="login" value="<?php if (isset($_POST["login"])) {
                                                                                                                                                                echo $_POST["login"];
                                                                                                                                                            } ?>" required>
        <?php
        if ($_SESSION['erreurLogin']) {
            echo ' <span class="erreur" >  ' . $_SESSION['erreurLogin'] . '</span> ';
        }
        ?>
        <br>

        <label for="motDePasseInscription"><span class="obligatoire">*</span>Mot de passe&nbsp;:</label><input type="password" name="motDePasse" id="motDePasseInscription" value="<?php if (isset($_POST['motDePasse'])) echo $_POST["motDePasse"]; ?>" required><br>



        <label for="prenomInscription"><span class="obligatoire">*</span>Pr&eacute;nom&nbsp;:</label><input type="text" name="prenom" id="prenomInscription" value="<?php if (isset($_POST["prenom"])) {
                                                                                                                                                                        echo $_POST["prenom"];
                                                                                                                                                                    } ?>">
        <?php
        if ($_SESSION['erreurPrenom']) {
            echo ' <span class="erreur" >  ' . $_SESSION['erreurPrenom'] . '</span> ';
        }
        ?>
        <br>

        <label for="AgeInscription"><span class="obligatoire">*</span>Age&nbsp;:</label><input type="text" id="ageInscription" name="age" value="<?php if (isset($_POST["age"])) {
                                                                                                                                                        echo $_POST["age"];
                                                                                                                                                    } ?>">
        <?php
        if ($_SESSION['erreurAge']) {
            echo " <span class='erreur' >  " . $_SESSION['erreurAge'] . "</span> ";
        }
        ?>
        <br>

        <button type="submit" name="afficheInscription" value="inscrit">S'inscrire</button>
        <?php if (
            $_POST['afficheInscription'] == "inscrit" &&
            $_SESSION['erreurPrenom'] == '' &&
            $_SESSION['erreurAge'] == '' &&
            $_SESSION['erreurLogin'] == ''
        ) echo "<p class='success'>Vos informations on bien &eacute;t&eacute; enregistrées.</p>"; ?>
    </form>
</div>