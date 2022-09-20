<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

if($connecter) {
	header('Location:home.php');
	die();
}

$valid = false;
$messageErreur = '';

if(isset($_POST['connexion']) AND !empty($_POST['connexion'])) {
	$valid = true;

	// On récupère les informations du formulaire de connexion
	$email = htmlspecialchars(trim($_POST['email'])); // On récupère le mail
	$mdp = htmlspecialchars(trim($_POST['mdp'])); // On récupère le mot de passe

	// Récupération de l'e-mail et de du mdp utlisateur hashé dans la BDD
	$requete = $dbh->prepare('SELECT * FROM utilisateur WHERE MAIL = ?');
	$requete->execute(array($email));
	$resultat = $requete->fetch();
	$requete->closeCursor();

	if(!$resultat) {
		$valid = false;
		$messageErreur .= "- Cet email n'existe pas<br />";
	}

	else if(!password_verify($mdp, $resultat['MOTDEPASSE'])) {
		$valid = false;
		$messageErreur .= "- Le mot passe est incorrect<br />";
	}

	if($valid) {
		$_SESSION['id_utilisateur'] = $resultat['id_utilisateur'];
		$_SESSION['PSEUDO'] = $resultat['PSEUDO'];
		$_SESSION['MAIL'] = $resultat['MAIL'];
		header('Location:profil.php');
		die();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body class="body_formulaire"><?php require_once 'base/barremenu.php'; ?>
		<div class="container all">
			<div id="formulaire" class="text-center">
				<h2>Connexion</h2>
				<form action="connexion.php" method="POST">
					<div class="mb-3">
						<input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="submit" name="connexion" class="btn btn-danger" value="Se connecter">
					</div>
				</form>
				<p>Pas encore inscris ? <a class="text-danger" href="inscription.php">Incrivez-vous!</a></p>
				<?php if($messageErreur !== '') { ?>
					<div class="alert alert-danger"><?php echo $messageErreur; ?></div>
				<?php } ?>
			</div>
		</div>
    </body>
</html>