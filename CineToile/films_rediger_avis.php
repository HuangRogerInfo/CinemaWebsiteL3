<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

if(!$connecter) {
	header('Location:home.php');
	die();
}

$valid = false;

if(isset($_GET['id']) AND !empty($_GET['id'])) {

	//On récupère l'id du film venant de GET
	$id = htmlspecialchars($_GET['id']);

	//Requête SQL pour récupérer les données du film
	$requete = $dbh->prepare('SELECT * FROM films WHERE id_films = ?');
	$requete->execute(array($id));
	$resultat = $requete->fetch();
	$requete->closeCursor();

	if($resultat){
		$valid = true;
	}

	//Requête SQL pour récupérer l'avis (si il existe) et la note de l'utilisateur sur ce film
	$req_avis = $dbh->prepare('SELECT * FROM films_avis INNER JOIN utilisateur ON films_avis.id_utilisateur = utilisateur.id_utilisateur WHERE films_avis.id_films = ? AND films_avis.id_utilisateur = ?');
	$req_avis->execute(array($id,$_SESSION['id_utilisateur']));
	$donnees_avis = $req_avis->fetch();
	$req_avis->closeCursor();

}

if(isset($_POST['envoyer']) AND !empty($_POST['envoyer'])) {

	//On récupère l'avis de l'utilisateur avec la méthode POST
	$avis = htmlspecialchars($_POST['avis']);

	//Requête SQL pour insérer l'avis de l'utilisateur sur un film
	$update_avis = $dbh->prepare('UPDATE films_avis SET DATE_AVIS = ?, AVIS = ? WHERE id_films = ? AND id_utilisateur = ?');
	$update_avis->execute(array(date('Y-m-d'),$avis,$id,$_SESSION['id_utilisateur']));
	$update_avis->closeCursor();

	header('Location:films.php?id=' . $id);
	die();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body style="background-color:black;"><?php require_once 'base/barremenu.php'; ?>

		<div class="container all text-center" style="background-color:white; min-height:100vh;">
		<?php if($valid) { ?>
			<?php if($donnees_avis) { ?>
				<h5>Vous avez noté ce film :
					<?php for($j = 0; $j < 5; $j++) { ?>
					<i class="fas fa-star" style="color:<?php if($donnees_avis['NOTE'] >= $j + 1) { echo " gold;"; } else { echo "black;" ; } ?>"></i>
					<?php } echo $donnees_avis['NOTE']; ?>
				</h5>
			<h5>Maintenant, rédigez un avis pour le film "<?php if($valid) { echo $resultat['TITRE']; } ?>" :</h5>
			<form method="POST" action="films_rediger_avis.php?id=<?php if($valid) { echo $id; } ?>">
				<div class="mt-3">
					<textarea name="avis" rows="10" cols="80" maxlength="100"></textarea>
				</div>
				<div class="mt-3">
					<input type="submit" name="envoyer" class="btn btn-primary" value="Envoyer">
				</div>
			</form>
		<?php } else { ?>
			<h5>Vous n'avez pas encore noté ce film, par conséquent vous ne pouvez pas donner votre avis.</h5>
		<?php } ?>
		<div class="pt-3">
			<a href="films.php?id=<?php echo $id; ?>">Retour</a>
		</div>
		<?php }
		else { ?>
			<h1 class="text-center">Erreur : Mauvais ID film ou inexistant</h1>
		<?php } ?>
		</div>
    </body>
</html>