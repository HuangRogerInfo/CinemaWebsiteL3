<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';

if(!$connecter) {
	header('Location:home.php');
	die();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body><?php require_once 'base/barremenu.php'; ?>
		<div class="container all" style="min-height:100vh;">
			<div class="row">
				<div class="col-12 text-center font-weight-bold"><b>Souhaitez-vous réellement vous désinscrire du site ?</b></div>
			</div>
			<div class="row pt-4">
				<div class="col-4 offset-2 text-center">
					<a class="btn btn-danger" style="color:white" href="desinscription.php" role="button">Oui</a></div>
				<div class="col-4 text-center">
					<a class="btn btn-success" style="color:white" href="profil.php" role="button">Non</a>		
				</div>
			</div>
		</div>
		<?php require_once 'base/footer.php'; ?>
	</body>
</html>