<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

$valid = false;

if(isset($_GET['id']) AND !empty($_GET['id'])) {

	//On récupère l'id de l'individu venant de GET
	$id = htmlspecialchars($_GET['id']);

	//Requête SQL pour récupérer les données de l'individu
	$requete = $dbh->prepare('SELECT * FROM individus WHERE id_individus = ?');
	$requete->execute(array($id));
	$resultat = $requete->fetch();
	$requete->closeCursor();

	if($resultat){
		if(!empty($resultat['DATE_NAISSANCE'])){
			$date_naissance = explode('-',$resultat['DATE_NAISSANCE']);
			$age = date('Y') - date('Y',strtotime($resultat['DATE_NAISSANCE']));
			if(date('md') < date('md',strtotime($resultat['DATE_NAISSANCE']))) {
				$age -= 1;
			}
		}
		$valid = true;	
	}

	//Requête SQL pour récupérer la filmographie de cet individu
	$req_films = $dbh->prepare('SELECT * FROM films INNER JOIN films_individus ON films.id_films = films_individus.id_films WHERE films_individus.id_individus = ?');
	$req_films->execute(array($id));
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body style="background-color:black;"><?php require_once 'base/barremenu.php'; ?>

		<div class="container all" style="background-color:white; min-height:100vh;">
		<?php if($valid) { ?>
			<div class="row">
				<div class="text-center col-lg-3 col-md-8 offset-md-2 col-10 offset-1">
					<img class="img-fluid photoIndividu" src="img/individus/<?php echo $resultat['PHOTO']; ?>" alt="photo <?php echo $resultat['NOM']; ?>">
				</div>
				<div class="col-lg-5 offset-lg-0 col-md-8 offset-md-2 col-10 offset-1">
					<h1 id="tableFilm"><?php echo $resultat['NOM']; ?></h1>
					<table class="table">
						<tr>
							<th scope="row">Date de naissance</th>
							<td><?php if(!empty($resultat['DATE_NAISSANCE'])) { echo intval($date_naissance[2]) . " " . $mois_fr[intval($date_naissance[1])] . " " . $date_naissance[0]; } ?></td>
						</tr>
						<tr>
							<th scope="row">Métiers</th>
							<td><?php echo $resultat['METIERS']; ?></td>
						</tr>
						<tr>
							<th scope="row">Nationalité</th>
							<td><?php echo $resultat['NATIONALITE']; ?></td>
						</tr>
						<tr>
							<th scope="row">Age</th>
							<td><?php if(!empty($resultat['DATE_NAISSANCE'])) { echo $age; ?> ans<?php } ?></td>
						</tr>
						<tr>
							<th scope="row">Genre</th>
							<td><?php echo $resultat['GENRE']; ?></td>
						</tr>
					</table>
				</div>	
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="individuTitre border-bottom">Biographie</h1>
					<?php echo nl2br($resultat['BIOGRAPHIE']); ?>
				</div>
			</div>
			
			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="individuTitre border-bottom">Filmographie</h1>
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<div class="row">
						<?php while($donnees_films = $req_films->fetch()) { ?>
							<div class="col-lg-3 col-md-4 col-6">
								<a href="films.php?id=<?php echo $donnees_films['id_films']?>"><img class= "img-fluid" src="img/affiches/<?php echo $donnees_films['AFFICHE']; ?>" alt="affiche <?php echo $donnees_films['TITRE']; ?>">
								<?php echo $donnees_films['TITRE']; ?></a>
							</div>
						<?php }
						$req_films->closeCursor(); ?>		
					</div>
				</div>
			</div>
		<?php }
		else { ?>
			<h1 class="text-center">Erreur : Mauvais ID individu ou inexistant</h1>
		<?php } ?>
		</div>
    </body>
</html>