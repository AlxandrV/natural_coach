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
    <main class="container">
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
            <div class="d-flex justify-content-end">
                <form action="identification.php" method="POST" class="form-inline">
                    <input type="text" name="login_admin" id="login" placeholder="login = admin" class="form-control mr-sm-2" aria-label="Search">
                    <input type="password" name="password_admin" id="password" placeholder="password = admin" class="form-control mr-sm-2" aria-label="Search">
                    <input type="submit" value="Valider" class="btn btn-outline-success my-2 my-sm-0">
                </form>
            </div>
            <h1>Natural Coach</h1>
            <p>Bienvenue.</p>
            <p>Nous effectuons plusieurs randonnée à travers le pays, vous trouverez la liste de prochaines que nous organisons ici bas, pour vous insrcire merci de nous contacter au : <a href="tel:+164896327">01 64 89 63 27</a>.</p>
        <?php
            echo '<ul class="list-group">';
            $excursion = $bdd -> query('SELECT * FROM  excursion');
            while($donnees = $excursion -> fetch()){
                echo '<li class="list-group-item">Nom : <span>' . htmlspecialchars($donnees['nom']) . '</span>, date départ : <span>' . htmlspecialchars($donnees['date_depart']) . '</span>, date retour : <span>' . htmlspecialchars($donnees['date_retour']) . '</span>, depart : <span>' . htmlspecialchars($donnees['point_depart']) . '</span>, arrivée : <span>' . htmlspecialchars($donnees['point_arrivee']) .'</span>, tarif : <span>' . htmlspecialchars($donnees['tarif']) . '</span>.</li>';
            }
            echo '<ul>';
        }
        // Fonctionnalité pour admin
        else{
            ?>
            <main class="container">
            
            <!-- Form log out admin -->
            <div class="d-flex justify-content-end" id="log-out">
                <form action="identification.php" method="POST">
                    <button type="submit" name="log_out" value="log_out" class="btn btn-outline-success">Se déconnecter</button>
                </form>
            </div>
            <?php
            $excursion = $bdd -> query('SELECT * FROM  excursion');
            ?>

            <!-- Liste éxcursion et form pour suppression -->
            <form action="ajout.php" method="POST">
                <ul class="list-group" id="list">
                <?php
                while($donnees = $excursion -> fetch()){
                    ?>
                    <li class="list-group-item row d-flex justify-content-between">
                        <p class="col-12"> Nom : <span><?php echo htmlspecialchars($donnees['nom']) ?></span>, date départ : <span><?php echo htmlspecialchars($donnees['date_depart']) ?></span>, date retour : <span><?php echo htmlspecialchars($donnees['date_retour']) ?></span>, depart : <span><?php echo htmlspecialchars($donnees['point_depart']) ?></span>, arrivée : <span><?php echo htmlspecialchars($donnees['point_arrivee']) ?></span>, tarif : <span><?php echo htmlspecialchars($donnees['tarif']) ?></span>€.</p>
                        <button type="submit" name="delete" value="<?php echo htmlspecialchars($donnees['id'])?>" class="btn btn-danger">Supprimer</button>
                        <a href="excursion.php?n=<?php echo htmlspecialchars($donnees['id']) ?>" class="badge badge-pill badge-info d-flex align-items-center">Détails et groupe</a>
                    </li>
                    <?php
                }
                ?>
                <template id="reponse_ajax">
                    <li class="list-group-item row d-flex justify-content-between" id="clone">
                        <p class="col-12">Nom : <span></span>, date départ : <span></span>, date retour : <span></span>, depart : <span></span>, arrivée : <span></span>, tarif : <span></span>€.</p>
                        <button type="submit" name="delete" value="" class="btn btn-danger">Supprimer</button>
                        <a href="" class="badge badge-pill badge-info d-flex align-items-center">Détails et groupe</a>
                    </li>
                </template>
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
    </main>
</body>
</html>