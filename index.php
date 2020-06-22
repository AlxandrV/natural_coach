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
if(!isset($_SESSION['id_membre'])){
?>
    <form action="identification.php" method="POST">
        <input type="text" name="login_admin" id="login" placeholder="login">
        <input type="text" name="password_admin" id="password" placeholder="password">
        <input type="submit" value="Valider">
    </form>
<?php
    $excursion = $bdd -> query('SELECT * FROM  excursion');
    while($donnees = $excursion -> fetch()){
        echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</p>';
    }
}
else{
    $excursion = $bdd -> query('SELECT * FROM  excursion');
    while($donnees = $excursion -> fetch()){
        echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</p>';
    }
?>
    <form action="ajout.php" method="POST" novaldate>
        <p>jouter une nouvelle excursion :</p>
        <p>Nom de la randonnée <input type="text" name="nom_excursion"></input>, date de départ <input type="date" name="date_depart" required></input>, date de retour <input type="date" name="date_retour" required></input>, point de départ <input type="text" name="point_depart" required></input>
        , point d'arrivée <input type="text" name="point_arrivee" required></input>, région de départ <input type="text" name="region_depart" required></input>, région d'arrivée <input type="text" name="region_arrivee" required></input>, tarif <input type="number" name="tarif" min="0" step="any" required></input></p>
        <input type="submit" value="Créer">
    </form>
<?php
}
$excursion -> closeCursor();
?>  
</body>
</html>