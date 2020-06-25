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


    ?>
</body>
</html>