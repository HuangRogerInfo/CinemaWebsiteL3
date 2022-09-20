<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

$nb_films = 0;

if(isset($_GET['id']) AND !empty($_GET['id'])) {
	$id = htmlspecialchars($_GET['id']);
	$requete = $dbh->prepare('SELECT * FROM films INNER JOIN films_categories ON films.id_films = films_categories.id_films WHERE id_categories = ?');
	$requete->execute(array($id));

	$requete_Count = $dbh->prepare('SELECT COUNT(*) AS nb_films FROM films INNER JOIN films_categories ON films.id_films = films_categories.id_films WHERE id_categories = ?');
	$requete_Count->execute(array($id));
	$resultat_Count = $requete_Count->fetch();
	$nb_films = $resultat_Count['nb_films'];
	$requete_Count->closeCursor();

	$requete_nom_categorie = $dbh->prepare('SELECT * FROM categories WHERE id_categories = ?');
	$requete_nom_categorie->execute(array($id));
	$nom_categorie = $requete_nom_categorie->fetch();
	$requete_nom_categorie->closeCursor();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body style="background-color:black;"><?php require_once 'base/barremenu.php'; ?>

		<div class="container all" style="background-color:white; min-height:100vh;">

			<div class="row">
				<div class="col-12 text-center">
					<b>FILMS :</b> (<?php echo $nb_films; ?> résultat<?php if($nb_films > 1) {?>s<?php } ?> trouvé<?php if($nb_films > 1) {?>s<?php } ?> dans la catégorie "<?php echo $nom_categorie['NOM']; ?>")
				</div>
			</div>

			<?php if($nb_films > 0) { ?>
				<?php while($donnees_films = $requete->fetch()){ ?>
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
				$requete->closeCursor();
			}
			else { ?>
			<div class="row pt-4">
				<div class="col-12 text-center">Aucun film trouvé dans la catégorie "<?php echo $nom_categorie['NOM']; ?>"</div>
			</div>
			<?php } ?>

			<div class="row pt-4">
				<div class="col-12 text-center">
					<a href="categories.php">Retour</a>
				</div>
			</div>
		</div>

    </body>
</html>