<?php
session_start();
if(!isset($_SESSION['id_admin'])){
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

    $id = $_GET['n'];
    $req = $bdd -> prepare('SELECT * FROM excursion WHERE id = ?');
    $req -> execute(array($id));
    while($donnees = $req -> fetch()){
        echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '€.</p>';
    }
    $req -> closeCursor();

    $req = $bdd -> prepare('SELECT * FROM groupe WHERE id_excursion = ?');
    $req -> execute(array($id));
    ?>
    <form action="ajout.php" method="POST">
        <p> Place max : <input type="text" name="nombre_place" id="nombre_place"><input type="hidden" name="id" value="<?php echo $_GET['n']?>"><input type="submit" value="Créer un nouveau groupe"></p>
    </form>
    <form action="gestion.php" method="POST">
        <ul>
            <?php
            $i = 1;
            while($donnees = $req -> fetch()){
                echo '<li>Groupe ' . $i . ', place maximum : ' . $donnees['place_max'] . ' <button type="submit" name="groupe" value="' . $donnees['id'] . '">Voir les paricipants</button></li>';
                $i++;
            }
            ?>    
        </ul>    
    </form>
</body>
</html>