<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

$valid = false;

if(isset($_GET['id']) AND !empty($_GET['id'])) {

	//On récupère l'id de l'article venant de GET
	$id = htmlspecialchars($_GET['id']);

	//Requête SQL pour récupérer les données de l'article
	$requete_article = $dbh->prepare('SELECT * FROM articles WHERE id_articles = ?');
	$requete_article->execute(array($id));
	$resultat_article = $requete_article->fetch();
	$requete_article->closeCursor();

	if($resultat_article){
		if(!empty($resultat_article['DATE_ARTICLE'])){
			$date_article = explode('-',$resultat_article['DATE_ARTICLE']);
		}
		$valid = true;
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?></head>
	<body><?php require_once 'base/barremenu.php'; ?>
		<div class="container all" style="min-height:100vh;">
		<?php if($valid) { ?>
			<div class="row">
				<div class="dateArticle text-center"><?php if(!empty($resultat_article['DATE_ARTICLE'])) { echo intval($date_article[2]) . " " . $mois_fr[intval($date_article[1])] . " " . $date_article[0]; } ?></div>
			</div>
			<div class="row pt-2">
				<div class="col-8 offset-2">
					<div class="titreArticle border-bottom text-center pb-1"><?php echo $resultat_article['TITRE']; ?></div>
				</div>
			</div>

            <div class="row">
                <div class="col-8 offset-2 pt-3 text-center"><?php echo nl2br($resultat_article['RESUME']); ?></div>
			</div>
			
			<div class="row">
                <div class="col-10 offset-1 pt-3 text-center"><img class="img-fluid" src="img/actu/<?php echo $resultat_article['IMAGE_ARTICLE']; ?>" alt="image article"></div>

                <div class="col-10 offset-1 pt-3" style="text-align: justify;"><?php echo nl2br($resultat_article['CONTENU']); ?></div>
            </div>

			<div class="row pt-4">
				<div class="col-12 text-center">
					<a href="home.php">Retour</a>
				</div>
			</div>
		<?php }
		else { ?><h1 class="text-center">Erreur : Mauvais ID article ou inexistant</h1><?php } ?>
        </div>
	<?php require_once 'base/footer.php'; ?>
    </body>
</html>