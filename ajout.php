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

// SUpprime une entrée dans 'excursion'
if(isset($_POST['delete']) && !empty($_POST['delete'])){
    $id_to_delete = $_POST['delete'];
    $req = $bdd -> prepare('DELETE FROM excursion WHERE id = :id_delete');
    $req -> execute(array('id_delete' => $id_to_delete));
    $req -> closeCursor();
}
header('Location: index.php');