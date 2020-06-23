<?php
session_start();
if(!isset($_GET['n']) || !isset($_SESSION['id_admin'])){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    $id = $_GET['n'];
    $req= $bdd -> prepare('SELECT * FROM excursion WHERE id = ?');
    $req -> execute(array($id));
    while($donnees = $req -> fetch()){
        echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</p>';
    }
    ?>
    <form action="ajout.php" method="POST">
        <p> Place max : <input type="text" name="nombre_place" id="nombre_place"><input type="submit" value="Gréer un nouveau groupe">
</p>
    </form>
</body>
</html>