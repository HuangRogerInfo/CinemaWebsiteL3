<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

if($connecter) {
	header('Location:home.php');
	die();
}

$valid = false;
$messageErreur = '';

if(isset($_POST['inscription']) AND !empty($_POST['inscription'])) {
	$valid = true;

	// On récupère les informations du formulaire d'inscription
	$pseudo = htmlspecialchars(trim($_POST['pseudo'])); // On récupère le pseudo
	$email = htmlspecialchars(trim($_POST['email'])); // On récupère l'email
	$mdp = htmlspecialchars(trim($_POST['mdp'])); // On récupère le mot de passe
	$mdp2 = htmlspecialchars(trim($_POST['mdp2'])); // On récupère la confirmation du mot de passe

	// Vérification du pseudo
	$req_pseudo = $dbh->prepare('SELECT * FROM utilisateur WHERE pseudo = ?');
	$req_pseudo->execute(array($pseudo));
	$result_pseudo = $req_pseudo->fetch();
	$req_pseudo->closeCursor();

	if($result_pseudo) {
		$valid = false;
		$messageErreur .= "- Ce pseudo est déjà pris<br />";
	}

	else if(!preg_match('/^[a-zA-Z0-9]+$/',$pseudo)) {
		$valid = false;
		$messageErreur .= "- Ce pseudo n'est pas valide<br />";
	}

	if(strlen($pseudo) < 4) {
		$valid = false;
		$messageErreur .= "- Le pseudo doit contenir au moins 4 caractères<br />";
	}

	else if(strlen($pseudo) > 20) {
		$valid = false;
		$messageErreur .= "- Le pseudo ne doit pas dépasser 20 caractères<br />";
	}

	if(strlen($mdp) < 4) {
		$valid = false;
		$messageErreur .= "- Le mot de passe doit contenir au moins 4 caractères<br />";
	}

	if($mdp !== $mdp2){
		$valid = false;
		$messageErreur .= "- La confirmation du mot de passe ne correspond pas<br />";
	}

	//Vérification du mail
	if(!filter_var($email, FILTER_VALIDATE_EMAIL) OR !preg_match("^[_a-zA-Z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
		$valid = false;
		$messageErreur .= "- L'e-mail n'est pas valide<br />";
	}

	else {
		$req_email = $dbh->prepare('SELECT * FROM utilisateur WHERE MAIL = ?');
		$req_email->execute(array($email));
		$result_email = $req_email->fetch();		
		$req_email->closeCursor();

		if($result_email) {
			$valid = false;
			$messageErreur .= "- Cet e-mail est déjà utilisé<br />";
		}
	}

	//Si le mdp = email
	if($mdp === $email){
		$valid = false;
		$messageErreur .= "- Le mot de passe doit être différent de l'e-mail";
	}

	//Si l'inscription est valide
	if($valid) {
		$mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
		$inserer = $dbh->prepare('INSERT INTO utilisateur (PSEUDO,MOTDEPASSE,MAIL) VALUES(?,?,?)');
		$inserer->execute(array($pseudo,$mdp_hache,$email));
		$inserer->closeCursor();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body class="body_formulaire"><?php require_once 'base/barremenu.php'; ?>
		<div class="container all">
			<div id="formulaire" class="text-center">
				<h2>Inscription</h2>
				<form action="inscription.php" method="POST">
					<div class="mb-3">
						<input type="text" name="pseudo" class="form-control" placeholder="Pseudo" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="password" name="mdp2" class="form-control" placeholder="Confirmer le mot de passe" required="required" autocomplete="off">
					</div>
					<div class="mb-3">
						<input type="submit" name="inscription" class="btn btn-danger" value="S'inscrire">
					</div>
				</form>
				<?php if($valid) { ?>
					<div class="alert alert-success">Votre inscription a bien été prise en compte !</div>
				<?php }
				else if($messageErreur !== '') { ?>
					<div class="alert alert-danger"><?php echo $messageErreur; ?></div>
				<?php } ?>
			</div>
		</div>
	</body>
</html>