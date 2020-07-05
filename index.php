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

        // Si $_GET['page'] n'existe pas vaut 1
        if(!isset($_GET['page']) || empty($_GET['page'])){
            $currentPage = 1;
        }
        else{
            $currentPage = $_GET['page'];
        }

        // Compte nombre d'élément pour pagination 
        $count = $bdd -> query('SELECT COUNT(id) FROM excursion');
        $total = $count -> fetch();

        // Défini le début du compte des éléments dans excursion selon la page
        $limit = 5;
        $debut = $currentPage * $limit - $limit;

        // Login pour admin
        if(!isset($_SESSION['id_admin'])){
            if(isset($_SESSION['false_admin']) && $_SESSION['false_admin'] === true){
                ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Erreur !</h4>
                    <hr>
                    <p class="mb-0">Mot de passe ou identifiant incorrect.</p>
                </div>
                <?php
                unset($_SESSION['false_admin']);
            }
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
            // Boucle liste des pages
            echo '<ul class="pagination">';
            echo '<li class="page-item page-link">Page</li>';
            for($i = 1; $i <= ceil($total[0] / $limit); $i++){
                ?>
                <li class="page-item"><a href="index.php?page=<?php echo $i ?>" class="page-link"><?php echo $i ?></a></li>
                <?php
            }
            echo '</ul>';

            // Liste des excursions
            echo '<ul class="list-group">';
            $excursion = $bdd -> prepare('SELECT * FROM  excursion ORDER BY id  DESC LIMIT :debut, :limit');
            $excursion -> bindValue('debut', $debut, PDO::PARAM_INT);
            $excursion -> bindValue('limit', $limit, PDO::PARAM_INT);
            $excursion -> execute();
            while($donnees = $excursion -> fetch()){
                echo '<li class="list-group-item">Nom : <span>' . htmlspecialchars($donnees['nom']) . '</span>, date départ : <span>' . htmlspecialchars($donnees['date_depart']) . '</span>, date retour : <span>' . htmlspecialchars($donnees['date_retour']) . '</span>, depart : <span>' . htmlspecialchars($donnees['point_depart']) . '</span>, arrivée : <span>' . htmlspecialchars($donnees['point_arrivee']) .'</span>, tarif : <span>' . htmlspecialchars($donnees['tarif']) . '</span>.</li>';
            }
            echo '<ul>';
        }
        // Fonctionnalité pour admin
        else{
            ?>
            <main class="container">
            <div class="d-flex justify-content-end" id="log-out">
                </p>
            
                <!-- Form log out admin -->
                <form action="identification.php" method="POST">
                    <button type="submit" name="log_out" value="log_out" class="btn btn-outline-success">Se déconnecter</button>
                </form>
            </div>
            <?php
            $excursion = $bdd -> prepare('SELECT * FROM  excursion ORDER BY id  DESC LIMIT :debut, :limit');
            $excursion -> bindValue('debut', $debut, PDO::PARAM_INT);
            $excursion -> bindValue('limit', $limit, PDO::PARAM_INT);
            $excursion -> execute();
            ?>

            <div class="d-flex justify-content-between align-items-center">
                <?php
                // Boucle liste des pages
                echo '<ul class="pagination">';
                echo '<li class="page-item page-link">Page</li>';
                for($i = 1; $i <= ceil($total[0] / $limit); $i++){
                    ?>
                    <li class="page-item"><a href="index.php?page=<?php echo $i ?>" class="page-link"><?php echo $i ?></a></li>
                    <?php
                }
                echo '</ul>';
                ?>
                <!-- Button trigger modal form add excursion -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#form_add_excursion">Ajouter une excursion</button>
                    
                <!-- Form pour lien vers liste des membres -->
                <form action="membre.php" id="list_member">
                    <button type="submit" name="status" value="randonneur" class="btn btn-info">Liste des randonneurs</button>
                    <button type="submit" name="status" value="guide" class="btn btn-info">Liste des guides</button>
                </form>
            </div>
            <!-- Liste excursion et form pour suppression -->
            <form action="ajout.php" method="POST">
                <!-- <ul class="list-group" id="list"> -->
                <!-- Table liste excursion -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Date de départ</th>
                            <th scope="col">Date de retour</th>
                            <th scope="col">Ville de départ</th>
                            <th scope="col">Ville d'arrivée</th>
                            <th scope="col">Région de départ</th>
                            <th scope="col">Région d'arrivée</th>
                            <th scope="col">Tarif</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    while($donnees = $excursion -> fetch()){
                        ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($donnees['nom']) ?></th>
                            <td><?php echo htmlspecialchars($donnees['date_depart']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['date_retour']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['point_depart']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['point_arrivee']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['region_depart']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['region_arrivee']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['tarif']) ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <button type="button" name="delete" value="<?php echo htmlspecialchars($donnees['id'])?>" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter" >Supprimer</button>
                                        <a href="excursion.php?n=<?php echo htmlspecialchars($donnees['id']) ?>" class="dropdown-item">Détails et groupe</a>
                                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#form_update_excursion">Modifier</button>

                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- <li class="list-group-item row d-flex justify-content-between">
                            <p class="col-12"> Nom : <span></span>, date départ : <span></span>, date retour : <span></span>, depart : <span></span>, arrivée : <span></span>, tarif : <span></span>€.</p>
                            
                            
                        </li> -->

                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <template id="reponse_ajax">
                    <li class="list-group-item row d-flex justify-content-between" id="clone">
                        <p class="col-12">Nom : <span></span>, date départ : <span></span>, date retour : <span></span>, depart : <span></span>, arrivée : <span></span>, tarif : <span></span>€.</p>
                        <button type="submit" name="delete" value="" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">Supprimer</button>
                        <a href="" class="badge badge-pill badge-info d-flex align-items-center">Détails et groupe</a>
                    </li>
                </template>
                </ul>
            </form>

            <!-- Modal Form pour ajout d'excursion -->
            <div class="modal fade" id="form_add_excursion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter une nouvelle excursion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="add_form">
                            <form action="ajout.php" method="POST" id="reqAjaxSubmit">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name_excursion">Nom de la randonnée</label>
                                        <input type="text" name="nom_excursion" id="name_excursion" class="form-control" required></input>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_depart_excursion">Date de départ</label>
                                        <input type="date" name="date_depart" id="date_depart_excursion" class="form-control" required></input>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_arrivee_excursion">Date d'arrivée</label>
                                        <input type="date" name="date_arrivee" id="date_arrivee_excursion" class="form-control" required></input>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="point_depart_excursion">Ville de départ</label>
                                        <input type="text" name="point_depart" id="point_depart_excursion" class="form-control" required></input>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="point_arrviee_excursion">ville d'arrivée</label>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

        <?php
        }
        $excursion -> closeCursor();
        $count -> closeCursor();
        ?>  
        <!-- Modal delete  -->
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

        <!-- Modal update excursion -->
        <div class="modal fade" id="form_update_excursion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modifier l'excursion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="add_form">
                        <form action="ajout.php" method="POST" id="reqAjaxUpdate">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="name_excursion">Nom de la randonnée</label>
                                    <input type="text" name="upd_nom_excursion" id="name_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_depart_excursion">Date de départ</label>
                                    <input type="date" name="upd_date_depart" id="date_depart_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_arrivee_excursion">Date d'arrivée</label>
                                    <input type="date" name="upd_date_arrivee" id="date_arrivee_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="point_depart_excursion">Ville de départ</label>
                                    <input type="text" name="upd_point_depart" id="point_depart_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="point_arrviee_excursion">Ville d'arrivée</label>
                                    <input type="text" name="upd_point_arrivee" id="point_arrviee_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="region_depart_excursion">Région de départ</label>
                                    <input type="text" name="upd_region_depart" id="region_depart_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="region_arrivee_excursion">Région d'arrivée</label>
                                    <input type="text" name="upd_region_arrivee" id="region_arrivee_excursion" class="form-control" value="" required></input>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="tarif">Tarif</label>
                                    <input type="number" name="upd_tarif" min="0" step="any" id="tarif" class="form-control" value="" required></input>
                                </div>
                                <input type="hidden" name="id" id="id" value="">
                                <input type="submit" value="Enregistrer" class="btn btn-primary">                
                            </div>
                        </form>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

    </main>
    <script src="script.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="delete.js"></script>
</body>
</html>