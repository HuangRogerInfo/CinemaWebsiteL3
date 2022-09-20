<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

if(!$connecter) {
	header('Location:home.php');
	die();
}

$valid_new_mdp = false;
$id_film_aleatoire = 0;
$id_individu_aleatoire = 0;
$messageErreur = '';

$requete_films_count = $dbh->query('SELECT COUNT(*) AS nb_films FROM films');
$resultat_films_count = $requete_films_count->fetch();
$nb_films = $resultat_films_count['nb_films'];
$requete_films_count->closeCursor();

$requete_individus_count = $dbh->query('SELECT COUNT(*) AS nb_individus FROM individus');
$resultat_individus_count = $requete_individus_count->fetch();
$nb_individus = $resultat_individus_count['nb_individus'];
$requete_individus_count->closeCursor();

if($nb_films > 0) {
	$id_film_aleatoire = rand(1,$nb_films);
	$requete_films = $dbh->prepare('SELECT * FROM films WHERE id_films = ?');
	$requete_films->execute(array($id_film_aleatoire));
	$resultat_films = $requete_films->fetch();
	$requete_films->closeCursor();
	
	//Requête SQL pour récupérer le Réalisateur du film
	$req_realisateur = $dbh->prepare('SELECT * FROM individus INNER JOIN films_individus ON individus.id_individus = films_individus.id_individus WHERE films_individus.id_films = ? AND films_individus.ROLE = "Réalisateur"');
	$req_realisateur->execute(array($id_film_aleatoire));
	$realisateur = $req_realisateur->fetch();
	$req_realisateur->closeCursor();
}

if($nb_individus > 0) {
	$id_individu_aleatoire = rand(1,$nb_individus);
	$requete_individus = $dbh->prepare('SELECT * FROM individus WHERE id_individus = ?');
	$requete_individus->execute(array($id_individu_aleatoire));
	$resultat_individus = $requete_individus->fetch();
	$requete_individus->closeCursor();
}

if(isset($_POST['changer_mdp']) AND !empty($_POST['changer_mdp'])) {
	$valid_new_mdp = true;

	// On récupère les informations du formulaire de changement de mot de passe
	$mdp_actuel = htmlspecialchars(trim($_POST['mdp_actuel'])); // On récupère le mot de passe actuel
	$new_mdp = htmlspecialchars(trim($_POST['new_mdp'])); // On récupère le nouveau mot de passe
	$new_mdp2 = htmlspecialchars(trim($_POST['new_mdp2'])); // On récupère la confirmation du nouveau mot de passe

	// Vérification du pseudo
	$req_pseudo = $dbh->prepare('SELECT * FROM utilisateur WHERE PSEUDO = ?');
	$req_pseudo->execute(array($_SESSION['PSEUDO']));
	$result_pseudo = $req_pseudo->fetch();
	$req_pseudo->closeCursor();

	if(strlen($new_mdp) < 4) {
		$valid_new_mdp = false;
		$messageErreur .= "- Le nouveau mot de passe doit contenir au moins 4 caractères<br />";
	}

	if($new_mdp !== $new_mdp2){
		$valid_new_mdp = false;
		$messageErreur .= "- La confirmation du nouveau mot de passe ne correspond pas<br />";
	}
	
	//Si le mdp = email
	if($new_mdp === $result_pseudo['MAIL']){
		$valid_new_mdp = false;
		$messageErreur .= "- Le nouveau mot de passe doit être différent de l'e-mail";
	}

	if(!password_verify($mdp_actuel, $result_pseudo['MOTDEPASSE'])) {
		$valid_new_mdp = false;
		$messageErreur .= "- Le mot de passe actuel est incorrect<br />";
	}

	//Si le nouveau mot de passe est valide
	if($valid_new_mdp) {
		$new_mdp_hache = password_hash($new_mdp, PASSWORD_DEFAULT);
		$modifier_mdp = $dbh->prepare('UPDATE utilisateur SET MOTDEPASSE = ? WHERE PSEUDO = ?');
		$modifier_mdp->execute(array($new_mdp_hache,$_SESSION['PSEUDO']));
		$modifier_mdp->closeCursor(); 
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body>
		<?php require_once 'base/barremenu.php'; ?>
		<div class="container all">

			<div class="row">
				<div class="col-md-2 offset-md-5 col-sm-4 offset-sm-4 col-4 offset-4">
					<img src="img/clapperboard.png" class="img-fluid" alt="cinema img profil">
				</div>
			</div>

			<h2 class="text-center">Votre Profil</h2>

			<div class="row pt-5">
				<div class="col-lg-4 offset-lg-1 col-10 offset-1">
					<div class="row">
						<h2>Vos informations</h2>
						<h5>Pseudo : <?php echo $_SESSION['PSEUDO']; ?></h5>
						<h5>Adresse e-mail : <?php echo $_SESSION['MAIL']; ?></h5>
					</div>

					<div class="row pt-2">
						<h4>Changer mon mot de passe</h4>
						<form action="profil.php" method="POST">
							<div class="pt-1">
								<input type="password" name="mdp_actuel" class="form-control" placeholder="Mot de passe actuel" required="required" autocomplete="off">
							</div>
							<div class="pt-3">
								<input type="password" name="new_mdp" class="form-control" placeholder="Nouveau mot de passe" required="required" autocomplete="off">
							</div>
							<div class="pt-3">
								<input type="password" name="new_mdp2" class="form-control" placeholder="Confirmer le nouveau mot de passe" required="required" autocomplete="off">
							</div>
							<div class="pt-3 pb-3 d-grid gap-2">
								<input type="submit" name="changer_mdp" class="btn btn-success" value="Changer mot de passe">
							</div>
						</form>

						<?php if($valid_new_mdp) { ?>
						<div class="alert alert-success"><b>Votre mot de passe a été modifié avec succès !</b></div>
						<?php }
						else if($messageErreur !== '') { ?>
						<div class="alert alert-danger"><b><?php echo $messageErreur; ?></b></div>
						<?php } ?>

						<h4>Désinscription</h4>
						<div class="d-grid gap-2">
							<a class="btn btn-danger" style="color:white" href="avertissement_desinscription.php" role="button">Supprimer mon compte</a>
						</div>
					</div>
				</div>

				<div class="col-lg-6 offset-lg-0 col-10 offset-1">
					<h2 class="mb-0" id="recommandation">Recommandations (aléatoire)</h2>
					<div class="row">
						<div class="col-6">
							<?php if($nb_films > 0) { ?>
							<div class="affiche">
								<a href="films.php?id=<?php echo $id_film_aleatoire; ?>">
									<img class="img-fluid" src="img/affiches/<?php echo $resultat_films['AFFICHE']; ?>" alt="affiche <?php echo $resultat_films['TITRE']; ?>"/>
									<div class="overlay">
										<div class="titreAffiche text-uppercase"><?php echo $resultat_films['TITRE']; ?></div>
										<div class="soustitreAffiche">Réalisé par <?php echo $realisateur['NOM']; ?></div>
									</div>
								</a>
							</div>
							<?php } ?>
						</div>
						<div class="col-6">
							<?php if($nb_individus > 0) { ?>
							<div class="affiche">
								<a href="individus.php?id=<?php echo $id_individu_aleatoire; ?>">
									<img class="img-fluid" src="img/individus/<?php echo $resultat_individus['PHOTO']; ?>" alt="photo <?php echo $resultat_individus['NOM']; ?>"/>
									<div class="overlay">
										<div class="titreAffiche text-uppercase"><?php echo $resultat_individus['NOM']; ?></div>
										<div class="soustitreAffiche">Métier : <?php echo $resultat_individus['METIERS']; ?></div>
									</div>
								</a>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require_once 'base/footer.php'; ?>
	</body>
</html>