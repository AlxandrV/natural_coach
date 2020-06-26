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
    <p><a href="index.php">Retour à l'acceuil</a></p>
    <?php
    // Connexion BDD
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exeption $e){
        die('Erreur : ' . $e -> getmessage());
    }

    $id_groupe = $_SESSION['id_group'];
    
    $status = $_GET;
    // Si $_GET randonneur
    if($status['status'] == 'randonneur'){
        if(!isset($_POST['add_randonneur'])){
            ?>
            <form action="ajout.php" id="membre"method="POST">
                <input type="text" name="add_nom_randonneur" id="nom" placeholder="Nom" required>
                <input type="text" name="add_prenom_randonneur" id="prenom" placeholder="Prénom" required>
                <input type="submit" value="Ajouter un randonneur">
            </form>
        <?php
        }
        echo '<form action="ajout.php" method="POST">';
        $req = $bdd -> query('SELECT * FROM randonneur');
        while($donnees = $req -> fetch()){
            echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'];

            // Vérifie si déjà inscrit à un groupe
            $verification_inscription = $bdd -> prepare('SELECT r_grp.id_groupe AS id_groupe FROM randonneur AS r INNER JOIN randonneur_groupe AS r_grp WHERE r.id = ' . $donnees['id'] . ' AND r_grp.id_groupe = ?');
            $verification_inscription -> execute(array($id_groupe));
            $validate = $verification_inscription -> fetch();
    
            if(isset($_POST['add_randonneur']) && $validate['id_groupe'] !== $id_groupe){
                echo '. <button type="submit" name="add_randonneur_group" value="' . $donnees['id'] . '">Ajouter</button></p>';
            }
            elseif(!isset($_POST['add_randonneur'])){
                echo '. <button type="submit" name="delete_randonneur" value="' . $donnees['id'] . '">Supprimer</button></p>';
            }
        }
        $verification_inscription -> closeCursor();
        $req -> closeCursor();
        ?>
        </form>
        <?php
    }

    // Si $_GET guide
    if($status['status'] == 'guide'){
        if(!isset($_POST['add_guide'])){
        ?>
            <form action="ajout.php" id="membre"method="POST">
                <input type="text" name="add_nom_guide" id="nom" placeholder="Nom" required>
                <input type="text" name="add_prenom_guide" id="prenom" placeholder="Prénom" required>
                <input type="tel" name="add_telephone_guide" id="add_telephone_guide" pattern="[0-9]{10}" placeholder="Tel : xxxxxxxxxx" required>
            <input type="submit" value="Ajouter un guide">
            </form>
        <?php
        }
        echo '<form action="ajout.php" method="POST">';
        
        $req = $bdd -> query('SELECT * FROM guide');
        while($donnees = $req -> fetch()){

            // Vérifie si déjà inscrit à un groupe
            $verification_inscription = $bdd -> prepare('SELECT g_grp.id_groupe AS id_groupe FROM guide AS g INNER JOIN guide_groupe AS g_grp WHERE g.id = ' . $donnees['id'] . ' AND g_grp.id_groupe = ?');
            $verification_inscription -> execute(array($id_groupe));
            $validate = $verification_inscription -> fetch();
            
            echo '<p>Nom : ' . $donnees['nom'] . ', prénom : ' . $donnees['prenom'] . ', numéro de téléphone : ' . $donnees['num_tel'];
            if(isset($_POST['add_guide']) && $validate['id_groupe'] !== $id_groupe){
                echo '. <button type="submit" name="add_guide_group" value="' . $donnees['id'] . '">Ajouter</button></p>';
            }
            elseif(!isset($_POST['add_guide'])){
                echo '. <button type="submit" name="delete_guide" value="' . $donnees['id'] . '">Supprimer</button></p>';
            }
        }
        
        $req -> closeCursor();
        ?>
        </form>
        <?php
    }
    ?>
</body>
</html>