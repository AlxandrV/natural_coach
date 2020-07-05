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

    <title>Membre</title>
</head>
<body>
    <main class="container">
        <p class=" nav-item d-flex row"><a href="index.php" class="nav-link">Retour à l'acceuil</a>
        <?php
            if(isset($_POST['add_randonneur']) || isset($_POST['add_guide'])){
                echo '<a href="excursion.php?n=' . $_SESSION['id_excursion'] . '" class="nav-link">Liste des groupes</a>';
                echo '<a href="gestion.php?groupe=' . $_SESSION['id_group'] . '" class="nav-link">Liste des inscrits</a>';
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
        
        if(isset($_SESSION['id_group'])){
            $id_groupe = $_SESSION['id_group'];
        }
        
        $status = $_GET;
        // Si $_GET randonneur
        if($status['status'] == 'randonneur'){
            if(!isset($_POST['add_randonneur'])){
                ?>
                <!-- Formulaire d'ajout de randonneur -->
                <form action="ajout.php" id="membre"method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="text" name="add_nom_randonneur" id="nom" placeholder="Nom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" name="add_prenom_randonneur" id="prenom" placeholder="Prénom" class="form-control" required>                        
                        </div>
                        <div class="form-group col-md-4">
                            <input type="submit" value="Ajouter un randonneur" class="btn btn-primary">                        
                        </div>

                    </div>
                </form>
                <?php
            }
            ?>
            <form action="ajout.php" method="POST">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $i = 1;
                $req = $bdd -> query('SELECT * FROM randonneur');
                // Liste tout les randonneurs inscrits
                while($donnees = $req -> fetch()){
                    ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($donnees['nom']) ?></th>
                        <td><?php echo htmlspecialchars($donnees['prenom']) ?></td>
                        <?php
                        
                        // Si $_SESSION['id_group'] existe
                        if(isset($_SESSION['id_group'])){
                            // Vérifie si inscrit au groupe correspondant à $_SESSION['id_group']
                            $verification_inscription = $bdd -> prepare('SELECT r_grp.id_groupe AS id_groupe, r.id FROM randonneur_groupe AS r_grp INNER JOIN randonneur AS r ON r.id = r_grp.id_randonneur WHERE r.id = ' . $donnees['id'] . ' AND r_grp.id_groupe = ?');
                            $verification_inscription -> execute(array($id_groupe));
                            $validate = $verification_inscription -> fetch();
                        }
                        
                        // Si non inscrit au groupe et place max non atteinte
                        if(isset($_POST['add_randonneur']) && $validate['id_groupe'] !== $id_groupe){
                            // <td> button retirer du groupe </td>
                            echo '<td><button type="submit" name="add_randonneur_group" value="' . $donnees['id'] . '" class="btn btn-primary">Ajouter</button></td>';
                            $verification_inscription -> closeCursor();
                        }
                        
                        // Si page simple pour lister randonneur, button modifier et supprimer
                        elseif(!isset($_POST['add_randonneur'])){
                            ?>
                            <td><button type="button" name="update_randonneur" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-warning" data-toggle="modal" data-target="#form_update_randonneur<?php echo $i ?>">Modifier</button></td>
                            <td><button type="button" name="delete_randonneur" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal_delete">Supprimer</button></td>


                            <?php
                        }
                        ?>
                        <!-- Modal update randonneur -->
                        <div class="modal fade" id="form_update_randonneur<?php echo $i ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Modifier le guide</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="add_form">
                                        <form action="ajout.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="name_excursion">Nom</label>
                                                    <input type="text" name="upd_nom_randonneur" id="name_excursion" class="form-control" value="<?php echo htmlspecialchars($donnees['nom']) ?>" required></input>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="name_excursion">Prénom</label>
                                                    <input type="text" name="upd_prenom_randonneur" id="name_excursion" class="form-control" value="<?php echo htmlspecialchars($donnees['prenom']) ?>" required></input>
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
                    </tr>
                    <?php
                    $i++;
            }
                $req -> closeCursor();
                ?>
                </tbody>
            </table>
            </ul>
            </form>
            <?php
        }
        
        // Si $_GET guide
        if($status['status'] == 'guide'){
            if(!isset($_POST['add_guide'])){
                ?>
                <!-- Formulaire d'ajout de guide -->
                <form action="ajout.php" id="membre"method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <input type="text" name="add_nom_guide" id="nom" placeholder="Nom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" name="add_prenom_guide" id="prenom" placeholder="Prénom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="tel" name="add_telephone_guide" id="add_telephone_guide" pattern="[0-9]{10}" placeholder="Tel : xxxxxxxxxx" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="submit" value="Ajouter un guide" class="btn btn-primary">
                        </div>


                    </div>
                </form>
            <?php
            }
            ?>
            <form action="ajout.php" method="POST">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Nuémro de téléphone</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php            
                    // Liste les guides
                    $i = 1;
                    $req = $bdd -> query('SELECT * FROM guide');
                    while($donnees = $req -> fetch()){
                        ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($donnees['nom']) ?></th>
                            <td><?php echo htmlspecialchars($donnees['prenom']) ?></td>
                            <td><?php echo htmlspecialchars($donnees['num_tel']) ?></td>
                            <?php                        
                            // Vérifie si déjà inscrit à un groupe
                            if(isset($_SESSION['id_group'])){
                                $verification_inscription = $bdd -> prepare('SELECT g_grp.id_groupe AS id_groupe FROM guide AS g INNER JOIN guide_groupe AS g_grp ON g.id = g_grp.id_guide WHERE g.id = ' . $donnees['id'] . ' AND g_grp.id_groupe = ?');
                                $verification_inscription -> execute(array($id_groupe));
                                $validate = $verification_inscription -> fetch();
                            }
                            // Si non inscrit au groupe
                            if(isset($_POST['add_guide']) && $validate['id_groupe'] !== $id_groupe){
                                // <td> button  retirer du groupe </td>
                                echo '<td><button type="submit" name="add_guide_group" value="' . htmlspecialchars($donnees['id']) . '" class="btn btn-primary">Ajouter</button></td>';
                                $verification_inscription -> closeCursor();          
                            }  
                            // Si page simple pour lister guide, button modiffier et supprimer
                            elseif(!isset($_POST['add_guide'])){
                                ?>
                                <td><button type="button" name="update_guide" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-warning" data-toggle="modal" data-target="#form_update_guide<?php echo $i ?>">Modifier</button></td>
                                <td><button type="button" name="delete_guide" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal_delete">Supprimer</button></td>


                                <?php
                            }
                            ?>
                            <!-- Modal update excursion -->
                            <div class="modal fade" id="form_update_guide<?php echo $i ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Modifier le guide</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="add_form">
                                            <form action="ajout.php" method="POST">
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label for="name_excursion">Nom</label>
                                                        <input type="text" name="upd_nom_guide" id="name_excursion" class="form-control" value="<?php echo htmlspecialchars($donnees['nom']) ?>" required></input>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="name_excursion">Prénom</label>
                                                        <input type="text" name="upd_prenom_guide" id="name_excursion" class="form-control" value="<?php echo htmlspecialchars($donnees['prenom']) ?>" required></input>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="name_excursion">Numéro de téléphone</label>
                                                        <input type="text" name="upd_phone_guide" id="name_excursion" class="form-control" value="<?php echo htmlspecialchars($donnees['num_tel']) ?>" required></input>
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
                        </tr>
                        <?php
                        $i++;
                    }
            
            $req -> closeCursor();
            ?>
                </ul>
            </form>
            <?php
        }
        ?>
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