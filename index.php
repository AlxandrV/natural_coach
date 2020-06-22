<?php
session_start();
//session_destroy();
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
if(!isset($_SESSION['membre'])){
?>
    <form action="identification.php" method="POST">
        <input type="text" name="login_membre" id="login" placeholder="login">
        <input type="text" name="password_membre" id="password" placeholder="password">
        <input type="submit" value="Valider">
    </form>
<?php
}
else{
    echo $_SESSION['nom_membre'] . '<br/>' . $_SESSION['prenom_membre'];

    try{
        $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exeption $e){
        die('Erreur : ' . $e -> getmessage());
    }

    echo '<form action="incription.php" method="POST">';
    $excursion = $bdd -> query('SELECT * FROM  excursion');
    while($donnees = $excursion -> fetch()){
        echo '<p>Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</p><button type="submit" name"' . $donnees['id'] . '">S\'inscrire</button>';
    }
    echo '</form>';
}
?>  
</body>
</html>