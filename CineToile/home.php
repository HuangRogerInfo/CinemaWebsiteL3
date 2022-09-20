<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

$requete_films = $dbh->query('SELECT * FROM films');
$requete_articles = $dbh->query('SELECT * FROM articles ORDER BY DATE_ARTICLE DESC');
$requete_realisateur = $dbh->query('SELECT * FROM individus INNER JOIN films_individus ON individus.id_individus = films_individus.id_individus WHERE films_individus.ROLE = "Réalisateur"');

?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body><?php require_once 'base/barremenu.php'; ?>

		<div class="container all">
			<div id="caroussel">
				<div class="owl-carousel owl-theme">
					<div class="slide slide-1">
						<div class="slide-content text-center">
							<a href="films.php?id=4"><img class="img-fluid" src="img/interstellar.jpeg" alt="Interstellar"/>
								<p><span class="carouselTitre">Interstellar</span><br />
								<span class="legende">Le film culte de Christopher Nolan</span></p>
							</a>
						</div>
					</div>
					<div class="slide slide-2">
						<div class="slide-content text-center">
							<a href="films.php?id=1"><img class ="img-fluid" src="img/oss_117_alerte_rouge.jpg" alt="OSS 117 : Alerte rouge en Afrique noire" />
								<p><span class="carouselTitre">OSS 117 : Alerte rouge en Afrique noire</span><br />
								<span class="legende">Les nouvelles aventures d'Hubert Bonisseur de La Bath</span></p>
							</a>
						</div>
					</div>
					<div class="slide slide-3">
						<div class="slide-content text-center">
							<a href="films.php?id=2"><img class ="img-fluid" src="img/mourir_peut_attendre.jpg" alt="007 : Mourir peut attendre" />
								<p><span class="carouselTitre">007 : Mourir peut attendre</span><br />
								<span class="legende">James Bond plus classe que jamais</span></p>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="pt-2" id="affiche">
				<div class="row">
					<div class="col-12">
						<h1 class="border-bottom">A l'affiche</h1>
					</div>
				</div>
				<div class="row">
				<?php for($i = 0; $i < 8; $i++) {
					$donnees_films = $requete_films->fetch();
					$realisateur = $requete_realisateur->fetch();

					if($donnees_films AND $realisateur) { ?>
						<div class="col-6 col-md-4 col-lg-3">
							<div class="affiche">
								<a href="films.php?id=<?php echo $donnees_films['id_films']; ?>">
									<img class="img-fluid" src="img/affiches/<?php echo $donnees_films['AFFICHE']; ?>" alt="affiche <?php echo $donnees_films['TITRE']; ?>"/>
									<div class="overlay">
										<div class="titreAffiche text-uppercase"><?php echo $donnees_films['TITRE']; ?></div>
										<div class="soustitreAffiche">Réalisé par <?php echo $realisateur['NOM']; ?></div>
									</div>
								</a>
							</div>
						</div>
					<?php }
				}
				$requete_films->closeCursor();
				$requete_realisateur->closeCursor();
				?>
				</div>
			</div>

			<div class="pt-2" id="actu">
				<div class="row">
					<div class="col-12">
						<h1 class="border-bottom">Actualités</h1>
					</div>
				</div>
				<div class="row">
				<?php for($i = 0; $i < 2; $i++) {  
					$donnees_articles = $requete_articles->fetch();
					$date_article = "";
					if(!empty($donnees_articles['DATE_ARTICLE'])){
						$date_article = explode('-',$donnees_articles['DATE_ARTICLE']);
					}
					if($donnees_articles) { ?>
					<div class="col-12 col-md-6">
						<a href="articles.php?id=<?php echo $donnees_articles['id_articles']; ?>">
							<div class="card mt-2">
								<img class="card-img-top" src="img/actu/<?php echo $donnees_articles['IMAGE_ARTICLE']; ?>" alt="actualite <?php echo $donnees_articles['id_articles']; ?>" />
								<div class="card-body">
									<h5 class="card-title"><?php echo $donnees_articles['TITRE']; ?></h5>
									<p class="card-text"><?php echo nl2br($donnees_articles['RESUME']); ?></p>
									<p class="card-text"><small class="text-muted"><?php if(!empty($donnees_articles['DATE_ARTICLE'])) { echo intval($date_article[2]) . " " . $mois_fr[intval($date_article[1])] . " " . $date_article[0]; } ?></small></p>
								</div>
							</div>
						</a>
					</div>
					<?php }
					} 
					?>
				</div>
				<div class="row pt-2" id="actu_secondaire">
				<?php for($i = 0; $i < 4; $i++) {  
					$donnees_articles = $requete_articles->fetch();
					$date_article = "";
					if(!empty($donnees_articles['DATE_ARTICLE'])){
						$date_article = explode('-',$donnees_articles['DATE_ARTICLE']);
					}
					if($donnees_articles) { ?>
					<div class="col-6 col-lg-3">
						<a href="articles.php?id=<?php echo $donnees_articles['id_articles']; ?>">
							<div class="card mt-2">
								<img class="card-img-top" src="img/actu/<?php echo $donnees_articles['IMAGE_ARTICLE']; ?>" alt="actualite <?php echo $donnees_articles['id_articles']; ?>" />
								<div class="card-body">
									<h5 class="card-title"><?php echo $donnees_articles['TITRE']; ?></h5>
									<p class="card-text"><?php echo nl2br($donnees_articles['RESUME']); ?></p>
									<p class="card-text"><small class="text-muted"><?php if(!empty($donnees_articles['DATE_ARTICLE'])) { echo intval($date_article[2]) . " " . $mois_fr[intval($date_article[1])] . " " . $date_article[0]; } ?></small></p>
								</div>
							</div>
						</a>
					</div>
					<?php }
					}
					$requete_articles->closeCursor();
					?>
				</div>
				<div class="row pt-3">
					<div class="col-12">
						<a href="actualites.php">Voir toute l'actualité ></a>
					</div>
				</div>
			</div>
		</div>
	<?php require_once 'base/footer.php'; ?>
	</body>
</html>