<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
	if($connecter) {
		unset($_SESSION['id_utilisateur'],$_SESSION['PSEUDO'],$_SESSION['MAIL']);
		session_destroy();
		header('Location:home.php');
		die();
	}
	else {
		header('Location:home.php');
		die();
	}
?>