<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natural Coach</title>
</head>
<body>
    <?php
    // Connexion BDD
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exeption $e){
        die('Erreur : ' . $e -> getmessage());
    }

    // Login pour admin
    if(!isset($_SESSION['id_admin'])){
    ?>
        <form action="identification.php" method="POST" >
            <input type="text" name="login_admin" id="login" placeholder="login">
            <input type="password" name="password_admin" id="password" placeholder="password">
            <input type="submit" value="Valider">
        </form>
    <?php
        $excursion = $bdd -> query('SELECT * FROM  excursion');
        while($donnees = $excursion -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</p>';
        }
    }
    // Fonctionnalité pour admin
    else{
        ?>
        <!-- Form log out admin -->
        <form action="identification.php" method="POST">
            <button type="submit" name="log_out" value="log_out">Se déconnecter</button>
        </form>
        <?php
        $excursion = $bdd -> query('SELECT * FROM  excursion');
        ?>

        <!-- Liste éxcursion et form pour suppression -->
        <form action="ajout.php" method="POST">
        <?php
        while($donnees = $excursion -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '€.<button type="submit" name="delete" value="' . $donnees['id'] . '">Supprimer</button><a href="excursion.php?n=' . $donnees['id'] . '">Détails et groupe</a></p>';
        }
        ?>
        </form>

        <!-- Form pour ajout d'éxcursion -->
        <form action="ajout.php" method="POST" id="reqAjaxSubmit">
            <p>Ajouter une nouvelle excursion :</p>
            <p>Nom de la randonnée <input type="text" name="nom_excursion" required></input>, date de départ <input type="date" name="date_depart" required></input>, date de retour <input type="date" name="date_arrivee" required></input>, point de départ <input type="text" name="point_depart" required></input>
            , point d'arrivée <input type="text" name="point_arrivee" required></input>, région de départ <input type="text" name="region_depart" required></input>, région d'arrivée <input type="text" name="region_arrivee" required></input>, tarif <input type="number" name="tarif" min="0" step="any" required></input></p>
            <input type="submit" value="Créer">
        </form>

        <!-- Form pour lien vers liste des membres -->
        <form action="membre.php">
            <button type="submit" name="status" value="randonneur">Liste des randonneurs</button>
            <button type="submit" name="status" value="guide">Liste des guides</button>
        </form>
    <?php
    }
    $excursion -> closeCursor();
    ?>  
    <script src="script.js"></script>
</body>
</html>