<?php
session_start();

// Connexion BDD
try{
    $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exeption $e){
    die('Erreur : ' . $e -> getmessage());
}

// Ajout nouvelle entrée 'excursion'
if(!empty($_POST['nom_excursion']) && !empty($_POST['date_depart']) && !empty($_POST['date_arrivee']) && !empty($_POST['point_depart']) && !empty($_POST['point_arrivee']) && !empty($_POST['region_depart']) && !empty($_POST['region_arrivee']) && !empty($_POST['tarif'])){

    $nom_excursion = $_POST['nom_excursion'];
    $date_depart = $_POST['date_depart'];
    $date_arrivee = $_POST['date_arrivee'];
    $point_depart = $_POST['point_depart'];
    $point_arrivee = $_POST['point_arrivee'];
    $region_depart = $_POST['region_depart'];
    $region_arrivee = $_POST['region_arrivee'];
    $tarif = $_POST['tarif'];

    // Requète pérparée pour création de ajout dans table 'excursion'
    $req = $bdd -> prepare('INSERT INTO excursion(nom, date_depart, date_retour, point_depart, region_depart, point_arrivee, region_arrivee, tarif) VALUE(:nom, :date_dpt, :date_arv, :point_dpt, :region_dpt, :point_arv, :region_arv, :prix)');
    $req -> execute(array('nom' => $nom_excursion,
        'date_dpt' => $date_depart,
        'date_arv' => $date_arrivee,
        'point_dpt' => $point_depart,
        'region_dpt' => $region_depart,
        'point_arv' => $point_arrivee,
        'region_arv' => $region_arrivee,
        'prix' => $tarif));

    // Réponse Ajax
    $reponse_ajax = $bdd -> query('SELECT * FROM `excursion` ORDER BY id DESC LIMIT 1');
    //var_dump($reponse_ajax)
    $donnees = $reponse_ajax -> fetch();
    echo json_encode($donnees);

    $reponse_ajax -> closeCursor();
    $req -> closeCursor();
    
}

// Supprime une entrée dans 'excursion'
if(isset($_POST['delete'])){
    $id_to_delete = $_POST['delete'];
    $req = $bdd -> prepare('DELETE FROM excursion WHERE id = :id_delete');
    $req -> execute(array('id_delete' => $id_to_delete));
    $req -> closeCursor();
    header('Location: index.php');
}

// Création de groupe
if(isset($_POST['nombre_place']) && isset($_POST['id'])){
    $place = $_POST['nombre_place'];
    $id = $_POST['id'];
    $id_default = '0';
    // Ajout dans liste de groupe 
    $req = $bdd -> prepare('INSERT INTO groupe(id_excursion, place_max) VALUE(:id, :place)');
    $req -> execute(array('id' => $id, 'place' => $place));
    $req -> closeCursor();
    header('Location: excursion.php?n=' . $id);
}

// Ajout de randonneur
if(isset($_POST['add_nom_randonneur']) && isset($_POST['add_prenom_randonneur'])){

    $nom = $_POST['add_nom_randonneur'];
    $prenom = $_POST['add_prenom_randonneur'];

    $req = $bdd -> prepare('INSERT INTO randonneur(nom, prenom) VALUE(:nom, :prenom)');
    $req -> execute(array('nom' => $nom, 'prenom' => $prenom));
    $req -> closeCursor();

    header('Location: membre.php?status=randonneur');
}

// Ajout de guide
if(isset($_POST['add_nom_guide']) && isset($_POST['add_prenom_guide']) && isset($_POST['add_telephone_guide'])){

    $nom = $_POST['add_nom_guide'];
    $prenom = $_POST['add_prenom_guide'];
    $telephone = $_POST['add_telephone_guide'];

    $req = $bdd -> prepare('INSERT INTO guide(nom, prenom, num_tel) VALUE(:nom, :prenom, :telephone)');
    $req -> execute(array('nom' => $nom, 'prenom' => $prenom, 'telephone' => $telephone));
    $req -> closeCursor();
    header('Location: membre.php?status=guide');
}

// Suppression de randonneur
if(isset($_POST['delete_randonneur'])){

    $id = $_POST['delete_randonneur'];

    $req = $bdd -> prepare('DELETE FROM randonneur WHERE id = ?');
    $req -> execute(array($id));
    $req -> closeCursor();
    header('Location: membre.php?status=randonneur');
}

// Suppression de guide
if(isset($_POST['delete_guide'])){

    $id = $_POST['delete_guide'];

    $req = $bdd -> prepare('DELETE FROM guide WHERE id = ?');
    $req -> execute(array($id));
    $req -> closeCursor();
    header('Location: membre.php?status=guide');
}

// Ajout randonneur à un groupe
if(isset($_POST['add_randonneur_group']) && isset($_SESSION['id_group'])){
    $id_randonneur = $_POST['add_randonneur_group'];
    $id_group = $_SESSION['id_group'];

    $req = $bdd -> prepare('INSERT INTO randonneur_groupe VALUE(:id_groupe, :id_randonneur)');
    $req -> execute(array('id_groupe' => $id_group, 'id_randonneur' => $id_randonneur));
    $req -> closeCursor();
    header('Location: index.php');
}

// Ajout guide à un groupe
if(isset($_POST['add_guide_group']) && isset($_SESSION['id_group'])){
    $id_guide = $_POST['add_guide_group'];
    $id_group = $_SESSION['id_group'];

    $req = $bdd -> prepare('INSERT INTO guide_groupe VALUE(:id_groupe, :id_guide)');
    $req -> execute(array('id_groupe' => $id_group, 'id_guide' => $id_guide));
    $req -> closeCursor();
    header('Location: index.php');
}

/*else{
    header('Location: index.php');
}*/