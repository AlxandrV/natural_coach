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

    $req -> closeCursor();

    header('Location: index.php');
}

// Supprime une entrée dans 'excursion'
if(isset($_POST['delete'])){
    $id_to_delete = $_POST['delete'];
    $req = $bdd -> prepare('DELETE FROM excursion WHERE id = :id_delete');
    $req -> execute(array('id_delete' => $id_to_delete));
    $req -> closeCursor();
    header('Location: index.php');
}

// Modification d'une excursion
if(!empty($_POST['upd_nom_excursion']) && !empty($_POST['upd_date_depart']) && !empty($_POST['upd_date_arrivee']) && !empty($_POST['upd_point_depart']) && !empty($_POST['upd_point_arrivee']) && !empty($_POST['upd_region_depart']) && !empty($_POST['upd_region_arrivee']) && !empty($_POST['upd_tarif']) && !empty($_POST['id'])){

    $nom_excursion = $_POST['upd_nom_excursion'];
    $date_depart = $_POST['upd_date_depart'];
    $date_arrivee = $_POST['upd_date_arrivee'];
    $point_depart = $_POST['upd_point_depart'];
    $point_arrivee = $_POST['upd_point_arrivee'];
    $region_depart = $_POST['upd_region_depart'];
    $region_arrivee = $_POST['upd_region_arrivee'];
    $tarif = $_POST['upd_tarif'];
    $id = $_POST['id'];
    
    $req = $bdd -> prepare('UPDATE excursion SET nom = :nom, date_depart = :date_depart, date_retour = :date_retour, point_depart = :point_depart, region_depart = :region_depart, point_arrivee = :point_arrivee, region_arrivee = :region_arrivee, tarif = :tarif WHERE id = :id');
    
    $req -> bindParam('nom', $nom_excursion, PDO::PARAM_STR);
    $req -> bindParam('date_depart', $date_depart, PDO::PARAM_STR);
    $req -> bindParam('date_retour', $date_arrivee, PDO::PARAM_STR);
    $req -> bindParam('point_depart', $point_depart, PDO::PARAM_STR);
    $req -> bindParam('region_depart', $region_depart, PDO::PARAM_STR);
    $req -> bindParam('point_arrivee', $point_arrivee, PDO::PARAM_STR);
    $req -> bindParam('region_arrivee', $region_arrivee, PDO::PARAM_STR);
    $req -> bindParam('tarif', $tarif, PDO::PARAM_STR);
    $req -> bindValue('id', $id, PDO::PARAM_INT);

    $req -> execute();

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

// Suppression de groupe
if(isset($_POST['delete-groupe'])){
    $delete = $_POST['delete-groupe'];
    $req = $bdd -> prepare('DELETE FROM groupe WHERE id = ?');
    $req -> execute(array($delete));
    $req -> closeCursor();
    header('Location: index.php');
}

// Modifier un groupe
if(isset($_POST['place_max']) && $_POST['id']){
    $max = $_POST['place_max'];
    $id = $_POST['id'];

    $req = $bdd -> prepare('UPDATE groupe SET place_max = :place_max WHERE id = :id');
    
    $req -> bindValue('place_max', $max, PDO::PARAM_INT);
    $req -> bindValue('id', $id, PDO::PARAM_INT);

    $req -> execute();
    $req -> closeCursor();

    header('Location: excursion.php?n=' . $_SESSION['id_excursion']);
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

// Retirer un randonneur d'un groupe
if(isset($_POST['remove_randonneur'])){
    $id_randonneur = $_POST['remove_randonneur'];
    $id_groupe = $_SESSION['id_group'];

    $req = $bdd -> prepare('DELETE FROM randonneur_groupe WHERE id_groupe = :id_groupe AND id_randonneur = :id_randonneur');

    $req -> bindValue('id_groupe', $id_groupe, PDO::PARAM_INT);
    $req -> bindValue('id_randonneur', $id_randonneur, PDO::PARAM_INT);

    $req -> execute();
    $req -> closeCursor();

    header('Location: gestion.php?groupe=' . $id_groupe);
}

// Retirer un guide d'un groupe
if(isset($_POST['remove_guide'])){
    $id_guide = $_POST['remove_guide'];
    $id_groupe = $_SESSION['id_group'];

    $req = $bdd -> prepare('DELETE FROM guide_groupe WHERE id_groupe = :id_groupe AND id_guide = :id_guide');

    $req -> bindValue('id_groupe', $id_groupe, PDO::PARAM_INT);
    $req -> bindValue('id_guide', $id_guide, PDO::PARAM_INT);

    $req -> execute();
    $req -> closeCursor();

    header('Location: gestion.php?groupe=' . $id_groupe);
}


// Modification de randonneur
if(isset($_POST['upd_nom_randonneur']) && isset($_POST['upd_prenom_randonneur']) && isset($_POST['id'])){
    $nom = $_POST['upd_nom_randonneur'];
    $prenom = $_POST['upd_prenom_randonneur'];
    $id = $_POST['id'];

    $req = $bdd -> prepare('UPDATE randonneur SET nom = :nom, prenom = :prenom WHERE id = :id');
    
    $req -> bindParam('nom', $nom, PDO::PARAM_STR);
    $req -> bindParam('prenom', $prenom, PDO::PARAM_STR);
    $req -> bindValue('id', $id, PDO::PARAM_INT);

    $req -> execute();
    $req -> closeCursor();

    header('Location: membre.php?status=randonneur');
}

// Modification de guide
if(isset($_POST['upd_nom_guide']) && isset($_POST['upd_prenom_guide']) && isset($_POST['upd_phone_guide']) && isset($_POST['id'])){
    $nom = $_POST['upd_nom_guide'];
    $prenom = $_POST['upd_prenom_guide'];
    $phone = $_POST['upd_phone_guide'];
    $id = $_POST['id'];

    $req = $bdd -> prepare('UPDATE guide SET nom = :nom, prenom = :prenom, num_tel = :phone WHERE id = :id');
    
    $req -> bindParam('nom', $nom, PDO::PARAM_STR);
    $req -> bindParam('prenom', $prenom, PDO::PARAM_STR);
    $req -> bindValue('phone', $phone, PDO::PARAM_INT);
    $req -> bindValue('id', $id, PDO::PARAM_INT);

    $req -> execute();
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
    header('Location: gestion.php?groupe=' . $id_group);
}

// Ajout guide à un groupe
if(isset($_POST['add_guide_group']) && isset($_SESSION['id_group'])){
    $id_guide = $_POST['add_guide_group'];
    $id_group = $_SESSION['id_group'];

    $req = $bdd -> prepare('INSERT INTO guide_groupe VALUE(:id_groupe, :id_guide)');
    $req -> execute(array('id_groupe' => $id_group, 'id_guide' => $id_guide));
    $req -> closeCursor();
    header('Location: gestion.php?groupe=' . $id_group);
}