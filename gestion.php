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
    $_SESSION['id_group'] = $id;
    $req = $bdd -> prepare('SELECT * FROM groupe AS g INNER JOIN randonneur_groupe AS r_grp ON g.id = r_grp.id_groupe INNER JOIN randonneur AS r ON r_grp.id_randonneur = r.id  WHERE g.id = ?');
    $req -> execute(array($id));

    // Liste les randonneurs inscrit
    while($donnees = $req -> fetch()){
        echo '<p>' . $donnees['nom'] . ', ' . $donnees['prenom'] . '</p>';
    }
    $req -> closeCursor();

    // Compte le nombre d'inscrit dans le groupe
    $req = $bdd -> prepare('SELECT g.place_max AS max_place, COUNT(r.id_groupe) AS max_inscrit FROM groupe AS g INNER JOIN randonneur_groupe AS r WHERE g.id = ?');
    $req -> execute(array($id));
    
    // Si nombre inscrit inférieur au nombre de place max
    $donnees = $req -> fetch();
    if($donnees['max_inscrit'] < $donnees['max_place']){
    ?>
    
        <!-- Form pour ajout de randonneur au groupe -->
        <form action="membre.php?status=randonneur" method="POST">
            <button type="submit" name="add_randonneur" value="randonneur">Inscrire un randonneur</button>
        </form>

    <?php
    }
    $req = $bdd -> prepare('SELECT * FROM groupe AS grp INNER JOIN guide_groupe AS g_grp ON grp.id = g_grp.id_groupe INNER JOIN guide AS g ON g_grp.id_guide = g.id  WHERE grp.id = ?');
    $req -> execute(array($id));

    // Liste les randonneurs inscrit
    while($donnees = $req -> fetch()){
        echo '<p>' . $donnees['nom'] . ', ' . $donnees['prenom'] . ', ' .$donnees['num_tel'] . '</p>';
    }
    $req -> closeCursor();
    ?>

    <!-- Form pour ajout de guide au groupe -->
    <form action="membre.php?status=guide" method="POST">
        <button type="submit" name="add_guide" value="guide">Inscrire un guide</button>
    </form>

</body>
</html>