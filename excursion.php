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
        <p class="navbar-nav nav-item"><a href="index.php" class="nav-link">Retour à l'acceuil</a></p>
        <?php
        // Connexion BDD
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exeption $e){
            die('Erreur : ' . $e -> getmessage());
        }
        
        echo '<ul class="list-group">';
        $id = $_GET['n'];
        $req = $bdd -> prepare('SELECT * FROM excursion WHERE id = ?');
        $req -> execute(array($id));
        while($donnees = $req -> fetch()){
            echo '<li class="list-group-item">Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '€.</li>';
        }
        $req -> closeCursor();
        echo '</ul>';
        
        $req = $bdd -> prepare('SELECT * FROM groupe WHERE id_excursion = ?');
        $req -> execute(array($id));
        ?>
        <form action="ajout.php" method="POST" id="add_form">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="nombre_place">Place max</label>
                    <input type="text" name="nombre_place" id="nombre_place" class="form-control"></input>
                </div>
                <input type="hidden" name="id" value="<?php echo $_GET['n']?>">
                <div class="form-group">
                    <input type="submit" value="Créer un nouveau groupe" class="btn btn-primary">
                </div>
            </div>
        </form>
        <form action="gestion.php" method="POST">
            <ul class="list-group">
                <?php
                $i = 1;
                while($donnees = $req -> fetch()){
                    echo '<li class="list-group-item">Groupe ' . $i . ', place maximum : ' . $donnees['place_max'] . ' <button type="submit" name="groupe" value="' . $donnees['id'] . '" class="badge badge-pill badge-info">Voir les paricipants</button></li>';
                    $i++;
                }
                ?>    
            </ul>    
        </form>
    </main>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>