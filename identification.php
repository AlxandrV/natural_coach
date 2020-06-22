<?php
session_start();
try{
    $bdd = new PDO('mysql:host=localhost;dbname=alexandrev_BDD_ACS;charset=utf8', 'vagrantdb', 'vagrantdb',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exeption $e){
    die('Erreur : ' . $e -> getmessage());
}

if(isset($_POST['login']) && isset($_POST['password'])){

}