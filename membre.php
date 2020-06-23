<?php
session_start();
if(!isset($_POST) || !isset($_SESSION['id_admin'])){
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
    <a href="index.php">Retour à l'acceuil</a>
    <?php
    // Connexion BDD
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exeption $e){
        die('Erreur : ' . $e -> getmessage());
    }

    $status = $_POST;
    if(isset($status['membre'])){
        $req = $bdd -> query('SELECT * FROM membre');
        while($donnees = $req -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'] . '.</p>';
        }
    }
    elseif(isset($status['guide'])){
        $req = $bdd -> query('SELECT * FROM guide');
        while($donnees = $req -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'] . ', numéro de téléphone : ' . $donnees['num_tel'] . '.</p>';
        }

    }
    $req -> closeCursor();
    ?>
</body>
</html>