<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Natural Coach</title>
</head>
<body>
    <div class="container">
        <?php
        // Connexion BDD
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exeption $e){
            die('Erreur : ' . $e -> getmessage());
        }

        // Login pour admin
        if(!isset($_SESSION['id_admin'])){
        ?>
            <div class="navbar">
                <form action="identification.php" method="POST" class="form-inline">
                    <input type="text" name="login_admin" id="login" placeholder="login" class="form-control mr-sm-2" aria-label="Search">
                    <input type="password" name="password_admin" id="password" placeholder="password" class="form-control mr-sm-2" aria-label="Search">
                    <input type="submit" value="Valider" class="btn btn-outline-success my-2 my-sm-0">
                </form>
            </div>
        <?php
            echo '<ul class="list-group">';
            $excursion = $bdd -> query('SELECT * FROM  excursion');
            while($donnees = $excursion -> fetch()){
                echo '<li class="list-group-item">Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '.</li>';
            }
            echo '<ul>';
        }
        // Fonctionnalité pour admin
        else{
            ?>
            <div class="container">
            
            <!-- Form log out admin -->
            <div class="navbar">
                <form action="identification.php" method="POST">
                    <button type="submit" name="log_out" value="log_out" class="btn btn-outline-success">Se déconnecter</button>
                </form>
            </div>
            <?php
            $excursion = $bdd -> query('SELECT * FROM  excursion');
            ?>

            <!-- Liste éxcursion et form pour suppression -->
            <form action="ajout.php" method="POST" id="req_ajax">
                <ul class="list-group">
                <?php
                while($donnees = $excursion -> fetch()){
                    echo '<li class="list-group-item"><button type="submit" name="delete" value="' . $donnees['id'] . '" class="btn btn-danger">Supprimer</button> Nom : ' . $donnees['nom'] . ', date départ : ' . $donnees['date_depart'] . ', date retour : ' . $donnees['date_retour'] . ', depart : ' . $donnees['point_depart'] . ', arrivée : ' . $donnees['point_arrivee'] .', tarif : ' . $donnees['tarif'] . '€. <a href="excursion.php?n=' . $donnees['id'] . '" class="badge badge-pill badge-info">Détails et groupe</a></li>';
                }
                ?>
                </ul>
            </form>

            <!-- Form pour ajout d'éxcursion -->
            <div id="add_form">
                <p>Ajouter une nouvelle excursion :</p>
                <form action="ajout.php" method="POST" id="reqAjaxSubmit">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name_excursion">Nom de la randonnée</label>
                            <input type="text" name="nom_excursion" id="name_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date_depart_excursion">date de départ</label>
                            <input type="date" name="date_depart" id="date_depart_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date_arrivee_excursion">date de d'arrivée</label>
                            <input type="date" name="date_arrivee" id="date_arrivee_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="point_depart_excursion">Point de départ</label>
                            <input type="text" name="point_depart" id="point_depart_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="point_arrviee_excursion">Point de d'arrivée</label>
                            <input type="text" name="point_arrivee" id="point_arrviee_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="region_depart_excursion">Région de départ</label>
                            <input type="text" name="region_depart" id="region_depart_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="region_arrivee_excursion">Région d'arrivée</label>
                            <input type="text" name="region_arrivee" id="region_arrivee_excursion" class="form-control" required></input>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="tarif">Tarif</label>
                            <input type="number" name="tarif" min="0" step="any" id="tarif" class="form-control" required></input>
                        </div>
                        <input type="submit" value="Créer" class="btn btn-primary">                
                    </div>
                </form>
            </div>

            <!-- Form pour lien vers liste des membres -->
            <form action="membre.php" id="list_member">
                <button type="submit" name="status" value="randonneur" class="btn btn-info">Liste des randonneurs</button>
                <button type="submit" name="status" value="guide" class="btn btn-info">Liste des guides</button>
            </form>
        <?php
        }
        $excursion -> closeCursor();
        ?>  
        <script src="script.js"></script>
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </div>
</body>
</html>