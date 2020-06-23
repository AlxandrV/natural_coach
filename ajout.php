<?php
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
    $req -> closeCursor();
}

// Supprime une entrée dans 'excursion'
if(isset($_POST['delete'])){
    $id_to_delete = $_POST['delete'];
    $req = $bdd -> prepare('DELETE FROM excursion WHERE id = :id_delete');
    $req -> execute(array('id_delete' => $id_to_delete));
    $req -> closeCursor();
}

// Création de groupe
if(isset($_POST['nombre_place']) && isset($_POST['id'])){
    $place = $_POST['nombre_place'];
    $id = $_POST['id'];
    // Ajout dans liste de groupe 
    $req = $bdd -> prepare('INSERT INTO groupe(id_excursion, place_max) VALUE(:id, :place)');
    $req -> execute(array('id' => $id, 'place' => $place));
    $req -> closeCursor();

    // Récupération de l'id du groupe le nom de la table à créer
    $req = $bdd -> query('SELECT id FROM groupe ORDER BY id DESC LIMIT 1');
    $table_name = 'groupe';
    $donnees = $req -> fetch();
    $table_name .= $donnees['id'];
    $req -> closeCursor();

    // Création du groupe 
    $req = $bdd -> query('CREATE TABLE ' . $table_name . ' (id INT PRIMARY KEY NOT NULL,
    id_participant INT,
    fonction VARCHAR(20))');
}
header('Location: index.php');