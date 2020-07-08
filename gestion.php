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
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Acceuil</a></li>
                <?php
                    if(isset($_SESSION['id_excursion'])){
                        echo '<li class="breadcrumb-item"><a href="excursion.php?n=' . $_SESSION['id_excursion'] . '">Groupe</a></li>';
                    }
                ?>
                <li class="breadcrumb-item active" aria-current="page">Inscrits</li>
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
        
        // Récupération des randonneurs du groupe selon le $_GET
        $id = $_GET['groupe'];
        $_SESSION['id_group'] = $id;
        $req = $bdd -> prepare('SELECT * FROM groupe AS g INNER JOIN randonneur_groupe AS r_grp ON g.id = r_grp.id_groupe INNER JOIN randonneur AS r ON r_grp.id_randonneur = r.id  WHERE g.id = ?');
        $req -> execute(array($id));
        ?>
        <!-- Table liste des randonneurs inscrits -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>

            <?php
            while($donnees = $req -> fetch()){
                ?>
                <tr>
                    <form action="ajout.php" method="POST">
                        <th scope="row"><?php echo htmlspecialchars($donnees['nom']) ?></th>
                        <td><?php echo htmlspecialchars($donnees['prenom']) ?></td>
                        <td><button type="submit" name="remove_randonneur" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-danger">Retirer</button></td>
                    </form>
                </tr>
                <?php
            }
            $req -> closeCursor();
            ?>    
            </tbody>
        </table>
        <?php
        // Compte le nombre de randonneur inscrit dans le groupe
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
        // Si place maximum atteinte
        else{
            echo '<p>Nombre d\'inscrit maximum atteint</p>';
        }

        echo '<br/>';
        // Récupération des guides inscrit au groupe selon le $_GET
        $req = $bdd -> prepare('SELECT * FROM groupe AS grp INNER JOIN guide_groupe AS g_grp ON grp.id = g_grp.id_groupe INNER JOIN guide AS g ON g_grp.id_guide = g.id  WHERE grp.id = ?');
        $req -> execute(array($id));
        ?>

        <!-- Table liste des randonneurs inscrits -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>

            <?php
            while($donnees = $req -> fetch()){
                ?>
                <tr>
                    <form action="ajout.php" method="POST">
                        <th scope="row"><?php echo htmlspecialchars($donnees['nom']) ?></th>
                        <td><?php echo htmlspecialchars($donnees['prenom']) ?></td>
                        <td><?php echo htmlspecialchars($donnees['num_tel']) ?></td>
                        <td><button type="submit" name="remove_guide" value="<?php echo htmlspecialchars($donnees['id']) ?>" class="btn btn-danger">Retirer</button></td>
                    </form>
                </tr>
                <?php
            }
            $req -> closeCursor();
            ?>    
            </tbody>
        </table>
        <?php
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