<?php require_once 'connexionBDD.php';

if(isset($_GET['rechercher']) AND !empty($_GET['rechercher']) AND !ctype_space($_GET['rechercher'])) {
$recherche = strtoupper(htmlspecialchars($_GET['rechercher']));
$recherche = str_replace(' ', '', $recherche); // supprimer les espaces

$requete_films = $dbh->prepare("SELECT * FROM films WHERE UPPER(REPLACE(TITRE,' ', '')) LIKE '%$recherche%'");
$requete_films->execute();

$requete_individus = $dbh->prepare("SELECT * FROM individus WHERE UPPER(REPLACE(NOM,' ', '')) LIKE '%$recherche%'");
$requete_individus->execute();

$resultat = array();

while($donnees_films = $requete_films->fetch()) {
	$resultat[] = array('value' => strtoupper($donnees_films['TITRE']),'label' => strtoupper($donnees_films['TITRE']));
}

while($donnees_individus = $requete_individus->fetch()) {
	$resultat[] = array('value' => $donnees_individus['NOM'],'label' => $donnees_individus['NOM']);
}

$requete_films->closeCursor();
$requete_individus->closeCursor();

echo json_encode($resultat);

}

exit;

?>