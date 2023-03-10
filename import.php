<?php

require 'config.php';
$filename = $argv[1];

/**
 * On vérifie que le fichier existe bien. S'il n'existe pas on affiche simplement un message d'erreur
 */
if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

/**
 * Si on arrive là c'est que le fichier existe bien, on va l'ouvrir en lecture
 * grâce à la fonction fopen()
 */
$file = fopen($filename, "r");


/**
 * On se connecte à la base de données avec PDO et on prépare la requête d'insertion
 */
$pdo = new PDO("mysql:host=localhost;dbname=subscribers01", "root", "");
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdoStatement = $pdo->prepare('INSERT INTO subscribers (date, email,firstname, lastname) VALUES (?,?,?,?)');

/**
 * Ensuite on va lire chaque ligne du fichier CSV avec la fonction fgetcsv()
 * tant qu'il y a des lignes à lire. S'il n'y a plus de nouvelle ligne, fgetcsv() retourne false.
 */
while ($row = fgetcsv($file)) {

    /**
     * $row représente une ligne du fichier CSV, les données sont récupérées dans un tableau
     * La première colone est le nom du produit
     * La deuxième colone est son prix sous forme d'une chaîne de caractères
     */
    //$name = $row[0];
    //$price = $row[1];
   
    $firstname=$row[0];
    $lastname=$row[1];
    $email=$row[2];
    $date= new DateTime();
    $dateformat = $date->format('Y-m-d H-i-s');

    $firstname = ucwords(strtolower($firstname), " -");
    $name = ucwords(strtolower($name), " -");
    $email = strtolower($email);
    $email = str_replace(" ","",$email);

    /**
     * Traitement du prix : 
     * - on supprime le signe €
     * - on remplace la virgule par un point
     * - on convertit la chaîne de caractères en nombre
     */
    //$price = str_replace('€', '', $price);
    //$price = str_replace(',', '.', $price);
    //$price = floatval($price) * 100;

    /**
     * On enregistre ensuite le produit dans la base de données en exécutant la requête préparée plus haut
     */
    $pdoStatement->execute([$firstname, $lastname, $email, $dateformat]);
}

echo 'Import terminé!';
