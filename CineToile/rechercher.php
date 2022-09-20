<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

$nombre_films = 0;
$nombre_individus = 0;

if(isset($_GET['rechercher']) AND !empty($_GET['rechercher']) AND !ctype_space($_GET['rechercher'])) {
	$recherche = strtoupper(htmlspecialchars($_GET['rechercher'])); //mettre en majuscules
	$recherche = str_replace(' ', '', $recherche); // supprimer les espaces

	//Requête SQL pour récupérer les titres des films correspondant à la recherche
	$requete_films = $dbh->query("SELECT * FROM films WHERE UPPER(REPLACE(TITRE,' ', '')) LIKE '%$recherche%'");
	
	//Requête SQL pour récupérer les nom des individus correspondant à la recherche
	$requete_individus = $dbh->query("SELECT * FROM individus WHERE UPPER(REPLACE(NOM,' ', '')) LIKE '%$recherche%'");

	//Requête SQL pour récupérer le nombre de films correspondant à la recherche
	$requete_count_films = $dbh->query("SELECT COUNT(*) AS nb_films FROM films WHERE UPPER(REPLACE(TITRE,' ', '')) LIKE '%$recherche%'");
	$resultat_count_films = $requete_count_films->fetch();
	$nombre_films = $resultat_count_films['nb_films'];
	$requete_count_films->closeCursor();

	//Requête SQL pour récupérer le nombre d'individus correspondant à la recherche
	$requete_count_individus = $dbh->query("SELECT COUNT(*) AS nb_individus FROM individus WHERE UPPER(REPLACE(NOM,' ', '')) LIKE '%$recherche%'");
	$resultat_count_individus = $requete_count_individus->fetch();
	$nombre_individus = $resultat_count_individus['nb_individus'];
	$requete_count_individus->closeCursor();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body style="background-color:black;"><?php require_once 'base/barremenu.php'; ?>
		<div class="container all" style="background-color:white; min-height:100vh;">
			<?php if($nombre_films > 0 OR $nombre_individus > 0) { ?>
					<div class="row">
						<div class="col-12 text-center">
							<b>FILMS :</b> (<?php echo $nombre_films; ?> résultat<?php if($nombre_films > 1) { ?>s<?php } ?> trouvé<?php if($nombre_films > 1) {?>s<?php } ?>)
						</div>
					</div>
					<?php while($donnees_films = $requete_films->fetch()){ ?>
						<div class="row pt-4">
							<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-10 offset-1 caseRecherche">
								<div class="row">
									<div class="col-6 px-0">
										<a href="films.php?id=<?php echo $donnees_films['id_films']; ?>"><img class="img-fluid" src="img/affiches/<?php echo $donnees_films['AFFICHE']; ?>" alt="affiche <?php echo $donnees_films['TITRE']; ?>"/></a>
									</div>
									<div class="col-6 mt-auto mb-auto text-uppercase">
										<a href="films.php?id=<?php echo $donnees_films['id_films']; ?>"><?php echo $donnees_films['TITRE']; ?></a>
									</div>
								</div>
							</div>
						</div>
					<?php }
				$requete_films->closeCursor(); ?>
				<div class="row pt-4">
					<div class="col-12 text-center">
						<b>INDIVIDUS :</b> (<?php echo $nombre_individus; ?> résultat<?php if($nombre_individus > 1) {?>s<?php } ?> trouvé<?php if($nombre_individus > 1) {?>s<?php } ?>)
					</div>
				</div>
				<?php while($donnees_individus = $requete_individus->fetch()){ ?>
					<div class="row pt-4">
						<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-10 offset-1 caseRecherche">
							<div class="row">
								<div class="col-6 px-0">
									<a href="individus.php?id=<?php echo $donnees_individus['id_individus']; ?>"><img class="img-fluid" src="img/individus/<?php echo $donnees_individus['PHOTO']; ?>" alt="photo <?php echo $donnees_individus['NOM']; ?>"/></a>
								</div>
								<div class="col-6 mt-auto mb-auto">
									<a href="individus.php?id=<?php echo $donnees_individus['id_individus']; ?>"><?php echo $donnees_individus['NOM']; ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php }
				$requete_individus->closeCursor();
			}
			else { ?>
				<div class="row">
					<div class="text-center">Aucun résultat trouvé</div>
				</div>
			<?php } ?>

			<div class="row pt-4">
				<div class="col-12 text-center">
					<a href="home.php">Retour</a>
				</div>
			</div>
		</div>
    </body>
</html>