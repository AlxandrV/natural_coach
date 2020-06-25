<?php
session_start();
try{
    $bdd = new PDO('mysql:host=localhost;dbname=natural_coach;charset=utf8', 'vagrantdb', 'vagrantdb',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exeption $e){
    die('Erreur : ' . $e -> getmessage());
}

if(isset($_POST['login_admin']) && isset($_POST['password_admin'])){
    $login = $_POST['login_admin'];
    $password = $_POST['password_admin'];

    $check_login = $bdd -> prepare('SELECT * FROM admin WHERE login_admin = :login');
    $check_login -> execute(array('login' => $login));
    $donnee = $check_login -> fetch();

    // Check hash password
    if(password_verify($password, $donnee['password_admin'])){
        $_SESSION['id_admin'] = $donnee['id'];
    }
    
    $check_login -> closeCursor();  
}
header('Location: index.php');
