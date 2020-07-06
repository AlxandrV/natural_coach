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
        <div class="d-flex justify-content-between" id="log-out">
            <h5>Natural Coach</h5>
            <!-- Form log out admin -->
            <form action="identification.php" method="POST">
                <button type="submit" name="log_out" value="log_out" class="btn btn-outline-success">Se déconnecter</button>
            </form>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Acceuil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Groupe</li>
            </ol>
        </nav>
        <?php
        // Connexion BDD
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exeption $e){
            die('Erreur : ' . $e -> getmessage());
        }
        ?>
        <!-- Table excursion -->
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
                </tr>
            </thead>
            <tbody>

                <?php
                $id = $_GET['n'];
                $_SESSION['id_excursion'] = $_GET['n'];
                $req = $bdd -> prepare('SELECT * FROM excursion WHERE id = ?');
                $req -> execute(array($id));
                // Afiiche l'excursion selon le $_GET
                while($donnees = $req -> fetch()){
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
                    </tr>
                    <?php
                }
                $req -> closeCursor();
                ?>
            </tbody>
        </table>

        <?php      
        // Formulaire d'ajout de goupe
        $req = $bdd -> prepare('SELECT * FROM groupe WHERE id_excursion = ?');
        $req -> execute(array($id));
        ?>
        <form action="ajout.php" method="POST" id="add_form">
            <div class="form-row">
                <div class="form-group d-flex" id="correctif">
                    <input type="text" name="nombre_place" class="form-control" placeholder="Place maximum" required></input>
                    <input type="hidden" name="id" value="<?php echo $_GET['n']?>">
                    <input type="submit" value="Créer un nouveau groupe" class="btn btn-primary">
                </div>
            </div>
        </form>
        <form action="ajout.php" method="POST">
            <!-- Table liste des groupes -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Groupe</th>
                        <th scope="col">Place maximum</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 1;
                    // liste les groupe pour l'excursion
                    while($donnees = $req -> fetch()){
                        ?>
                        <tr>
                            <th scope="row"><?php echo $i ?></th>
                            <td><?php echo htmlspecialchars($donnees['place_max']) ?></td>
                            <td><a href="gestion.php?groupe=<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-info">Voir les inscrits</a></td>
                            <td><button type="button" name="delete-groupe" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal_delete">Supprimer</button></td>
                            <td><button type="button" name="update_groupe" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-warning" data-toggle="modal" data-target="#form_update_groupe<?php echo $i ?>">Modifier</button></td>
                        </tr>
                        <!-- Modal update randonneur -->
                        <div class="modal fade" id="form_update_groupe<?php echo $i ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Modifier le nombe de place maximum</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="add_form">
                                        <form action="ajout.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="place_max">Place maximum</label>
                                                    <input type="text" name="place_max" id="place_max" class="form-control" value="<?php echo htmlspecialchars($donnees['place_max']) ?>" required></input>
                                                </div>
                                                <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($donnees['id']) ?>">
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

                        <?php
                        $i++;
                    }
                    ?>    
                </tbody>
            </table>
        </form>
        <!-- Modal -->
        <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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