<?php
require "identifiant_db.php";

try {
    // Créer un objet PDO pour se connecter à MariaDB
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $login, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>