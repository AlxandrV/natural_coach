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
        
        if(isset($_SESSION['id_group'])){
            $id_groupe = $_SESSION['id_group'];
        }
        
        $status = $_GET;
        // Si $_GET randonneur
        if($status['status'] == 'randonneur'){
            if(!isset($_POST['add_randonneur'])){
                ?>
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
            echo '<form action="ajout.php" method="POST">';
            echo '<ul class="list-group">';
            $req = $bdd -> query('SELECT * FROM randonneur');
            while($donnees = $req -> fetch()){
                echo '<li class="list-group-item d-flex justify-content-between"><p>Nom : <span>' . $donnees['nom'] . '</span>, prénom : <span>' . $donnees['prenom'] . '</span></p>';
                
                // Vérifie si déjà inscrit à un groupe
                if(isset($_SESSION['id_group'])){
                    $verification_inscription = $bdd -> prepare('SELECT r_grp.id_groupe AS id_groupe FROM randonneur AS r INNER JOIN randonneur_groupe AS r_grp WHERE r.id = ' . $donnees['id'] . ' AND r_grp.id_groupe = ?');
                    $verification_inscription -> execute(array($id_groupe));
                    $validate = $verification_inscription -> fetch();
                }
                
                if(isset($_POST['add_randonneur']) && $validate['id_groupe'] !== $id_groupe){
                    echo '<button type="submit" name="add_randonneur_group" value="' . $donnees['id'] . '" class="btn btn-primary">Ajouter</button></li>';
                    $verification_inscription -> closeCursor();
                }
                elseif(!isset($_POST['add_randonneur'])){
                    echo '<button type="submit" name="delete_randonneur" value="' . $donnees['id'] . '" class="btn btn-danger">Supprimer</button></li>';
                }
            }
            $req -> closeCursor();
            ?>
            </ul>
            </form>
            <?php
        }
        
        // Si $_GET guide
        if($status['status'] == 'guide'){
            if(!isset($_POST['add_guide'])){
                ?>
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
            echo '<form action="ajout.php" method="POST">';
            echo '<ul class="list-group">';
            
            $req = $bdd -> query('SELECT * FROM guide');
            while($donnees = $req -> fetch()){
                echo '<li class="list-group-item d-flex justify-content-between"><p>Nom : <span>' . htmlspecialchars($donnees['nom']) . '</span>, prénom : <span>' . htmlspecialchars($donnees['prenom']) . '</span>, numéro de téléphone : <span>' . htmlspecialchars($donnees['num_tel']) . '</span></p>';
                
                // Vérifie si déjà inscrit à un groupe
                if(isset($_SESSION['id_group'])){
                    $verification_inscription = $bdd -> prepare('SELECT g_grp.id_groupe AS id_groupe FROM guide AS g INNER JOIN guide_groupe AS g_grp WHERE g.id = ' . $donnees['id'] . ' AND g_grp.id_groupe = ?');
                    $verification_inscription -> execute(array($id_groupe));
                    $validate = $verification_inscription -> fetch();
                    
                    if(isset($_POST['add_guide']) && $validate['id_groupe'] !== $id_groupe){
                        echo '<button type="submit" name="add_guide_group" value="' . htmlspecialchars($donnees['id']) . '" class="btn btn-primary">Ajouter</button></li>';
                    }  
                    $verification_inscription -> closeCursor();          
                }
                elseif(!isset($_POST['add_guide'])){
                    echo '<button type="submit" name="delete_guide" value="' . htmlspecialchars($donnees['id']) . '" class="btn btn-danger">Supprimer</button></li>';
                }
            }
            
            $req -> closeCursor();
            ?>
                </ul>
            </form>
            <?php
        }
        ?>
    </main>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
</body>
</html>