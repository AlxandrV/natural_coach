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
        $_SESSION['id_excursion'] = $_GET['n'];
        $req = $bdd -> prepare('SELECT * FROM excursion WHERE id = ?');
        $req -> execute(array($id));
        // Afiiche l'excursion selon le $_GET
        while($donnees = $req -> fetch()){
            echo '<li class="list-group-item">Nom : ' . htmlspecialchars($donnees['nom']) . ', date départ : ' . htmlspecialchars($donnees['date_depart']) . ', date retour : ' . htmlspecialchars($donnees['date_retour']) . ', depart : ' . htmlspecialchars($donnees['point_depart']) . ', arrivée : ' . htmlspecialchars($donnees['point_arrivee']) .', tarif : ' . htmlspecialchars($donnees['tarif']) . '€.</li>';
        }
        $req -> closeCursor();
        echo '</ul>';
        
        // Formulaire d'ajout de goupe
        $req = $bdd -> prepare('SELECT * FROM groupe WHERE id_excursion = ?');
        $req -> execute(array($id));
        ?>
        <form action="ajout.php" method="POST" id="add_form">
            <p for="nombre_place">Place max</p>
            <div class="form-row">
                <div class="form-group d-flex" id="correctif">
                    <input type="text" name="nombre_place" class="form-control"></input>
                    <input type="hidden" name="id" value="<?php echo $_GET['n']?>">
                    <input type="submit" value="Créer un nouveau groupe" class="btn btn-primary">
                </div>
            </div>
        </form>
        <form action="ajout.php" method="POST">
            <ul class="list-group">
                <?php
                $i = 1;
                // liste les groupe pour l'excursion
                while($donnees = $req -> fetch()){
                    echo '<li class="list-group-item d-flex justify-content-around"><p>Groupe ' . $i . ', place maximum : ' . htmlspecialchars($donnees['place_max']) . '</p> <a href="gestion.php?groupe=' . htmlspecialchars($donnees['id']) . '" class="badge badge-pill badge-info d-flex align-items-center">Voir les paricipants</a><button type="button" name="delete-groupe" value="' . htmlspecialchars($donnees['id']) . '" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">Supprimer</button></li>';
                    $i++;
                }
                ?>    
            </ul>    
        </form>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Supprimer ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDelete()">Oui</button>
                </div>
                </div>
            </div>
        </div>

    </main>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="delete.js"></script>
</body>
</html>