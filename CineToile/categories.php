<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';

$nb_categories = 0;
$requete = $dbh->query('SELECT * FROM categories');

$requete_Count = $dbh->query('SELECT COUNT(*) AS nb_categories FROM categories');
$resultat_Count = $requete_Count->fetch();
$nb_categories = $resultat_Count['nb_categories'];
$requete_Count->closeCursor();

?>
<!DOCTYPE html>
<html lang="fr">
    <head><?php require_once 'base/head.php'; ?></head>
    <body><?php require_once 'base/barremenu.php'; ?>
        <div class="container all">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 id="categories">Catégories <small class="text-muted">(<?php echo $nb_categories; ?> résultat<?php if($nb_categories > 1) {?>s<?php } ?>)</small></h1>
                </div>
            </div>

			
            <div class="row text-center pt-2">
                <?php if($nb_categories > 0) {
					$nb_resultat = 0;
					while($donnees_categories = $requete->fetch()) { ?>
                    <div class="col-2 offset-<?php if($nb_resultat % 3 == 0) { echo 2; } else { echo 1; } ?> mt-3 p-2 boxCategories">
                    <a class="lienCategories" href="categories_recherche.php?id=<?php echo $donnees_categories['id_categories']; ?>"><?php echo $donnees_categories['NOM']; ?></a>
                    </div>
					<?php $nb_resultat++;
					}
                $requete->closeCursor();
                }
                else { ?>Aucune catégorie trouvée<?php } ?>
            </div>
        </div>
	<?php require_once 'base/footer.php'; ?>
    </body>
</html>