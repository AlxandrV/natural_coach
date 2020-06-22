<?php
var_dump($_POST);
if(!empty($_POST['nom_excursion']) && !empty($_POST['date_depart']) && !empty($_POST['date_arrivee']) && !empty($_POST['point_depart']) && !empty($_POST['point_arrivee']) && !empty($_POST['region_depart']) && !empty($_POST['region_arrivee']) && !empty($_POST['tarif'])){

    $nom_excursion = $_POST['nom_excursion'];
    $date_depart = $_POST['date_depart'];
    $date_arrivee = $_POST['date_arrivee'];
    $point_depart = $_POST['point_depart'];
    $point_arrivee = $_POST['point_arrivee'];
    $region_depart = $_POST['region_depart'];
    $region_arrivee = $_POST['region_arrivee'];
    $tarif = floatval($_POST['tarif']);
    var_dump($tarif);
    // Connexion BDD
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exeption $e){
        die('Erreur : ' . $e -> getmessage());
    }

    // Requète pérparée pour création de ajout dans table 'excursion'
    $req = $bdd -> prepare('INSERT INTO excursion VALUE(\'\', :nom, :date_dpt, :date_arv, :point_dpt, :region_dpt, :point_arv, :region_arv, :prix');
    $req -> execute(array('nom' => $nom_excursion,
        'date_dpt' => $date_depart,
        'date_arv' => $date_arrivee,
        'pont-dpt' => $point_depart,
        'point_arv' => $point_arrivee,
        'region_dpt' => $region_arrivee,
        'region_arv' => $region_arrivee,
        'prix' => $tarif));
}
header('Location: index.php');