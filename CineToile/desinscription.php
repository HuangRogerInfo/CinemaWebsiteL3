<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

if(!$connecter) {
	header('Location:home.php');
	die();
}

$requete_supprimer_avis = $dbh->prepare('DELETE FROM films_avis WHERE id_utilisateur = ?');
$requete_supprimer_avis->execute(array($_SESSION['id_utilisateur']));


$requete_desinscription = $dbh->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
$requete_desinscription->execute(array($_SESSION['id_utilisateur']));

header('Location:deconnexion.php');
die();

?>