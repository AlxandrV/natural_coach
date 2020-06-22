<?php
session_start();
try{
    $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exeption $e){
    die('Erreur : ' . $e -> getmessage());
}

if(isset($_POST['login_membre']) && isset($_POST['password_membre'])){
    $login = $_POST['login_membre'];
    $password = $_POST['password_membre'];
    $check_login = $bdd -> prepare('SELECT * FROM membre WHERE login = :login AND password = :password');
    $check_login -> execute(array('login' => $login, 'password' => $password));
    while($donnee = $check_login -> fetch()){
        if(isset($donnee)){
            $_SESSION['membre'] = true;
            $_SESSION['nom_membre'] = $donnee['nom'];
            $_SESSION['prenom_membre'] = $donnee['prenom'];
        }
    }  
    $check_login -> closeCursor();  
}
header('Location: index.php');
