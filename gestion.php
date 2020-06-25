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
    
    // Récupération de participants du gorupe
    $id = $_POST['groupe'];
    $req = $bdd -> prepare('SELECT * FROM groupe WHERE id = ?');
    $req -> execute(array($id));
    $donnees = $req -> fetch();
    var_dump($donnees);

    $array_randonneur = unserialize($donnees['id_randonneur']);
    var_dump($array_randonneur);
    $array_guide = unserialize($donnees['id_guide']);
    var_dump($array_guide);

    // Si liste des randonneurs vide
    if(empty($array_randonneur)){
        echo '<p>Aucun randonneur inscrit.</p>';
    }
    // Liste les randonneurs inscrit
    else{
        echo '<ul>';
        foreach($array_randonneur as $key => $value){
            $randonneur = $bdd -> prepare('SELECT nom, prenom FROM randonneur WHERE id = ?');
            $randonneur -> execute(array($value));
            echo '<li>Nom : ' . $randonneur['nom'] . ', prénom : ' . $randonneur['prenom'] . '</li>';
            $randonneur -> closeCursor();
        }
        echo '</ul>';
    }

    // Si liste des guides vide
    if(empty($array_guide)){
        echo '<p>Aucun guide inscrit.</p>';
    }
    // Liste les guides inscrit
    else{
        echo '<ul>';
        foreach($array_guide as $key => $value){
            $guide = $bdd -> prepare('SELECT nom, prenom, num_tel FROM guide WHERE id = ?');
            $guide -> execute(array($value));
            echo '<li>Nom : ' . $guide['nom'] . ', prénom : ' . $guide['prenom'] . ', numéro de téléphone : ' . $guide['num_tel'] . '</li>';
            $guide -> closeCursor();
        }
        echo '</ul>';

    }
    ?>
</body>
</html>