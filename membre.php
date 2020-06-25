<?php
session_start();
if(!isset($_GET) || !isset($_SESSION['id_admin'])){
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
    
    $status = $_GET;
    if($status['status'] == 'randonneur'){
        ?>
        <form action="ajout.php" id="membre"method="POST">
            <input type="text" name="add_nom_randonneur" id="nom" placeholder="Nom" required>
            <input type="text" name="add_prenom_randonneur" id="prenom" placeholder="Prénom" required>
            <input type="submit" value="Ajouter un randonneur">
        </form>

        <form action="ajout.php" method="POST">
        <?php
            $req = $bdd -> query('SELECT * FROM randonneur');
            while($donnees = $req -> fetch()){
                echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'] . '.<button type="submit" name="delete_randonneur" value="' . $donnees['id'] . '">Supprimer</button></p>';
            }
            $req -> closeCursor();
        ?>
        </form>
        <?php
    }
    if($status['status'] == 'guide'){
        ?>
        <form action="ajout.php" id="membre"method="POST">
            <input type="text" name="add_nom_guide" id="nom" placeholder="Nom" required>
            <input type="text" name="add_prenom_guide" id="prenom" placeholder="Prénom" required>
            <input type="tel" name="add_telephone_guide" id="add_telephone_guide" pattern="[0-9]{10}" placeholder="Tel : xxxxxxxxxx" required>
        <input type="submit" value="Ajouter un guide">
        </form>
        <form action="ajout.php" method="POST">
        
        <?php
        $req = $bdd -> query('SELECT * FROM guide');
        while($donnees = $req -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'] . ', numéro de téléphone : ' . $donnees['num_tel'] . '.<button type="submit" name="delete_guide" value="' . $donnees['id'] . '">Supprimer</button></p>';
        }
        
        $req -> closeCursor();
        ?>
        </form>
        <?php
    }
    ?>
</body>
</html>