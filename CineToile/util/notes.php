<?php require_once 'verifSession.php';
require_once 'connexionBDD.php';

if(isset($_GET['id']) && !empty($_GET['id']) AND isset($_GET['stars']) && !empty($_GET['stars']) AND $connecter) {
$id = htmlspecialchars($_GET['id']);
$note = htmlspecialchars($_GET['stars']);

$req_verif_note = $dbh->prepare('SELECT * FROM films_avis WHERE id_films = ? AND id_utilisateur = ?');
$req_verif_note->execute(array($id,$_SESSION['id_utilisateur']));
$verif_note = $req_verif_note->fetch();
$req_verif_note->closeCursor();

if(!$verif_note) {
	$req_supprimer_note = $dbh->prepare('DELETE FROM films_avis WHERE id_films = ? AND id_utilisateur = ?');
	$req_supprimer_note->execute(array($id,$_SESSION['id_utilisateur']));
	$req_supprimer_note->closeCursor();

	$req_inserer_note = $dbh->prepare('INSERT INTO films_avis (id_films,id_utilisateur,NOTE,DATE_AVIS) VALUES(?,?,?,?)');
	$req_inserer_note->execute(array($id,$_SESSION['id_utilisateur'],$note,date('Y-m-d')));
	$req_inserer_note->closeCursor();
}
else {
	//Requête SQL pour insérer l'avis de l'utilisateur sur un film
	$update_note = $dbh->prepare('UPDATE films_avis SET DATE_AVIS = ?, NOTE = ? WHERE id_films = ? AND id_utilisateur = ?');
	$update_note->execute(array(date('Y-m-d'),$note,$id,$_SESSION['id_utilisateur']));
	$update_note->closeCursor();
}

$req_avis_moyenne = $dbh->prepare('SELECT AVG(NOTE) FROM films_avis WHERE id_films = ?');
$req_avis_moyenne->execute(array($id));
$moyenne = $req_avis_moyenne->fetch();
$req_avis_moyenne->closeCursor();

$req_nb_avis = $dbh->prepare('SELECT COUNT(*) FROM films_avis WHERE id_films = ?');
$req_nb_avis->execute(array($id));
$nb_avis = $req_nb_avis->fetchColumn();
$req_nb_avis->closeCursor();

$resultat[] = array('moyenne' => $moyenne['AVG(NOTE)'],'nb_avis' => $nb_avis);

echo json_encode($resultat);

}

exit;

?>