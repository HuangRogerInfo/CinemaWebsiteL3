<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

$requete_articles = $dbh->query('SELECT * FROM articles ORDER BY DATE_ARTICLE DESC');

?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body><?php require_once 'base/barremenu.php'; ?>
	
        <div class="container all">
		
			<div id="actu">
				<h1 class="border-bottom text-center">Actualités</h1>
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
							<div class="card mt-3">
								<img class="card-img-top" src="img/actu/<?php echo $donnees_articles['IMAGE_ARTICLE']; ?>" alt="actualite <?php echo $donnees_articles['id_articles']; ?>" />
								<div class="card-body">
									<h5 class="card-title"><?php echo $donnees_articles['TITRE']; ?></h5>
									<p class="card-text"><?php echo nl2br($donnees_articles['RESUME']); ?></p>
									<span class="date_article"><?php if(!empty($donnees_articles['DATE_ARTICLE'])) { echo intval($date_article[2]) . " " . $mois_fr[intval($date_article[1])] . " " . $date_article[0]; } ?></span>
								</div>
							</div>
						</a>
					</div>
					<?php }
					} 
					?>
				</div>
				<div class="row pt-3">
				<?php for($i = 0; $i < 4; $i++) {  
					$donnees_articles = $requete_articles->fetch();
					$date_article = "";
					if(!empty($donnees_articles['DATE_ARTICLE'])){
						$date_article = explode('-',$donnees_articles['DATE_ARTICLE']);
					}
					if($donnees_articles) { ?>
					<div class=" col-lg-3 col-md-6 col-sm-12">
						<a href="articles.php?id=<?php echo $donnees_articles['id_articles']; ?>">
							<div class="card mt-3">
								<img class="card-img-top" src="img/actu/<?php echo $donnees_articles['IMAGE_ARTICLE']; ?>" alt="actualite <?php echo $donnees_articles['id_articles']; ?>" />
								<div class="card-body">
									<h5 class="card-title"><?php echo $donnees_articles['TITRE']; ?></h5>
									<p class="card-text"><?php echo nl2br($donnees_articles['RESUME']); ?></p>
									<span class="date_article"><?php if(!empty($donnees_articles['DATE_ARTICLE'])) { echo intval($date_article[2]) . " " . $mois_fr[intval($date_article[1])] . " " . $date_article[0]; } ?></span>
								</div>
							</div>
						</a>
					</div>
					<?php }
					}
					$requete_articles->closeCursor();
				?>
				</div>
			</div>
        </div>
	<?php require_once 'base/footer.php'; ?>
    </body>
</html>