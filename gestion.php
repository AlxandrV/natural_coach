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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <main class="container">
        <p class=" nav-item d-flex row"><a href="index.php" class="nav-link">Retour à l'acceuil</a>
        <?php
            if(isset($_SESSION['id_excursion'])){
                echo '<a href="excursion.php?n=' . $_SESSION['id_excursion'] . '" class="nav-link">Liste des groupes</a>';
            }
        ?>
        </p>
        <?php
        // Connexion BDD
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exeption $e){
            die('Erreur : ' . $e -> getmessage());
        }
        
        // Récupération des randonneurs du groupe selon le $_GET
        $id = $_GET['groupe'];
        $_SESSION['id_group'] = $id;
        $req = $bdd -> prepare('SELECT * FROM groupe AS g INNER JOIN randonneur_groupe AS r_grp ON g.id = r_grp.id_groupe INNER JOIN randonneur AS r ON r_grp.id_randonneur = r.id  WHERE g.id = ?');
        $req -> execute(array($id));

        // Liste les randonneurs inscrit
        echo '<ul class="list-group">';
        while($donnees = $req -> fetch()){
            echo '<li class="list-group-item">' . htmlspecialchars($donnees['nom']) . ', ' . htmlspecialchars($donnees['prenom']) . '</li>';
        }
        $req -> closeCursor();
        echo '</ul>';

        // Compte le nombre d'inscrit dans le groupe
        $req = $bdd -> prepare('SELECT g.place_max AS max_place, COUNT(r.id_groupe) AS max_inscrit FROM groupe AS g INNER JOIN randonneur_groupe AS r WHERE g.id = ? AND r.id_groupe = ?');
        $req -> execute(array($id, $id));
        
        // Si nombre inscrit inférieur au nombre de place max
        $donnees = $req -> fetch();
        if($donnees['max_inscrit'] < $donnees['max_place']){
        ?>
        
            <!-- Form pour ajout de randonneur au groupe -->
            <form action="membre.php?status=randonneur" method="POST" id="inscription">
                <button type="submit" name="add_randonneur" value="randonneur" class="btn btn-primary">Inscrire un randonneur</button>
            </form>

        <?php
        }
        echo '<br/>';
        // Récupération des guides inscrit au groupe selon le $_GET
        $req = $bdd -> prepare('SELECT * FROM groupe AS grp INNER JOIN guide_groupe AS g_grp ON grp.id = g_grp.id_groupe INNER JOIN guide AS g ON g_grp.id_guide = g.id  WHERE grp.id = ?');
        $req -> execute(array($id));

        // Liste les guides inscrit
        echo '<ul class="list-group">';
        while($donnees = $req -> fetch()){
            echo '<li class="list-group-item">' . htmlspecialchars($donnees['nom']) . ', ' . htmlspecialchars($donnees['prenom']) . ', ' . htmlspecialchars($donnees['num_tel']) . '</li>';
        }
        $req -> closeCursor();
        echo '</ul>';
        ?>

        <!-- Form pour ajout de guide au groupe -->
        <form action="membre.php?status=guide" method="POST" id="inscription">
            <button type="submit" name="add_guide" value="guide" class="btn btn-primary">Inscrire un guide</button>
        </form>
    </main>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>