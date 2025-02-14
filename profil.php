<?php if (isset($_SESSION['utilisateur'])): ?>
    <div class="modifier">
        <h2>Modification des donn&eacute;es personnelles</h2>
        <form action="index.php" method="POST">

            <label for="loginProfil">Login&nbsp;:</label>
            <input type="text" name="login" id="loginProfil" value="<?= htmlspecialchars($_SESSION['utilisateur']['login']) ?>">
            <?php
            if ($_SESSION['erreurLogin']) {
                echo ' <span class="erreur" >  ' . $_SESSION['erreurLogin'] . '</span> ';
            }
            ?>
            <br>
            <label for="motDePasseProfil">Mot de passe&nbsp;:</label>
            <input type="text" id="motDePasseProfil" name="motDePasse" value="<?= $_SESSION['utilisateur']['motDePasse'] ?>">
            <br>
            <label for="prenomProfil">Pr&eacute;nom&nbsp;:</label>
            <input type="text" name="prenom" id="prenomProfil" value="<?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?>">
            <?php
            if ($_SESSION['erreurPrenom']) {
                echo ' <span class="erreur" >  ' . $_SESSION['erreurPrenom'] . '</span> ';
            }
            ?>
            <br>

            <label for="ageProfil">Age&nbsp;:</label>
            <input type="text" id="ageProfil" name="age" value="<?= $_SESSION['utilisateur']['age'] ?>">
            <?php
            if ($_SESSION['erreurAge'] != '') {
                echo ' <span class="erreur" >  ' . $_SESSION['erreurAge'] . '</span> ';
            }
            ?>
            <br>

            <button type="submit" name="afficheProfil" value="update">Mettre à jour</button>
            <?php if (
                $_POST['afficheProfil'] == "update" &&
                $_SESSION['erreurLogin'] == '' &&
                $_SESSION['erreurPrenom'] == '' &&
                $_SESSION['erreurAge'] == ''
            ) echo "<p class='success'>Vos informations ont &eacute;t&eacute; mises à jour avec succès.</p>"; ?>
        </form>
    </div>
<?php endif; ?>