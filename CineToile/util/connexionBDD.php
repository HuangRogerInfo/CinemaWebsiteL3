<?php
$nom_BDD = "cinema";
$utilisateur = "root";
$mdp = "";

try {
	$dbh = new PDO('mysql:host=localhost;dbname=' . $nom_BDD, $utilisateur, $mdp);
}
catch(PDOException $e) {
	die("Erreur de connexion : " . $e->getMessage() . "<br/>");
}
$dbh->query("SET NAMES UTF8");
?>