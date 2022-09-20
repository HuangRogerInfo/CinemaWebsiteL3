<!-- Projet réalisé par Alain Barbier 11500443 et Roger Huang 11807555 -->

<?php require_once 'util/verifSession.php';
require_once 'util/connexionBDD.php';
require_once 'util/mois_fr.php';

$valid = false;

if(isset($_GET['id']) AND !empty($_GET['id'])) {

	//On récupère l'id du film venant de GET
	$id = htmlspecialchars($_GET['id']);

	//Requête SQL pour récupérer les données du film
	$requete = $dbh->prepare('SELECT * FROM films WHERE id_films = ?');
	$requete->execute(array($id));
	$resultat = $requete->fetch();
	$requete->closeCursor();

	if($resultat){
		if(!empty($resultat['DATE_SORTIE'])){
			$date_sortie = explode('-',$resultat['DATE_SORTIE']);
		}
		$valid = true;	
	}

	//Requête SQL pour récupérer les Catégories du film
	$req_categories = $dbh->prepare('SELECT * FROM categories INNER JOIN films_categories ON categories.id_categories = films_categories.id_categories WHERE films_categories.id_films = ?');
	$req_categories->execute(array($id));

	//Requête SQL pour récupérer les Acteurs du film
	$req_acteurs = $dbh->prepare('SELECT * FROM individus INNER JOIN films_individus ON individus.id_individus = films_individus.id_individus WHERE films_individus.id_films = ? AND films_individus.ROLE = "Acteur"');
	$req_acteurs->execute(array($id));

	//Requête SQL pour récupérer le Réalisateur du film
	$req_realisateur = $dbh->prepare('SELECT * FROM individus INNER JOIN films_individus ON individus.id_individus = films_individus.id_individus WHERE films_individus.id_films = ? AND films_individus.ROLE = "Réalisateur"');
	$req_realisateur->execute(array($id));
	$realisateur = $req_realisateur->fetch();
	$req_realisateur->closeCursor();

	//Requête SQL pour récupérer les 3 derniers avis
	$req_avis = $dbh->prepare('SELECT * FROM films_avis INNER JOIN utilisateur ON films_avis.id_utilisateur = utilisateur.id_utilisateur WHERE films_avis.id_films = ? ORDER BY DATE_AVIS DESC');
	$req_avis->execute(array($id));

	//Requête SQL pour récupérer la moyenne de tous les avis sur ce film
	$req_avis_moyenne = $dbh->prepare('SELECT AVG(NOTE) AS moyenne FROM films_avis WHERE id_films = ?');
	$req_avis_moyenne->execute(array($id));
	$result_avis_moyenne = $req_avis_moyenne->fetch();
	$moyenne = $result_avis_moyenne['moyenne'];

	$note_utilisateur = false;

	if($connecter) {
		//Requête SQL pour récupérer la note de l'utilisateur
		$req_avis_utilisateur = $dbh->prepare('SELECT * FROM films_avis WHERE id_films = ? AND id_utilisateur = ?');
		$req_avis_utilisateur->execute(array($id,$_SESSION['id_utilisateur']));
		$note_utilisateur = $req_avis_utilisateur->fetch();
	}

	//Requête SQL pour récupérer le nombre d'avis sur ce film
	$req_nb_avis = $dbh->prepare('SELECT COUNT(*) AS nb_avis FROM films_avis WHERE id_films = ?');
	$req_nb_avis->execute(array($id));
	$resultat_nb_avis = $req_nb_avis->fetch();
	$nb_avis = $resultat_nb_avis['nb_avis'];
	$req_nb_avis->closeCursor();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head><?php require_once 'base/head.php'; ?>
	<script>
	$(document).ready(function() {
		$('.rating input').on('click', function(){
			let stars = $(this).val();
			$.ajax({
				url: "util/notes.php",
				type: 'get',
				dataType: 'json',
				data: "id=" + <?php if($valid) { echo $id; } ?> + "&stars=" + stars,
				success : function(resp) {
					let result = resp[0].moyenne;
					let result2 = resp[0].nb_avis;
					let e1 = result >= 1 ? "gold;" : "black;";
					let e2 = result >= 2 ? "gold;" : "black;";
					let e3 = result >= 3 ? "gold;" : "black;";
					let e4 = result >= 4 ? "gold;" : "black;";
					let e5 = result >= 5 ? "gold;" : "black;";
					let etoiles_spect = '<i class="fas fa-star" style="color:' + e1 +'"></i> <i class="fas fa-star" style="color:' + e2 +'"></i> <i class="fas fa-star" style="color:' + e3 +'"></i> <i class="fas fa-star" style="color:' + e4 +'"></i> <i class="fas fa-star" style="color:' + e5 +'"></i> ' + result + ' (' + result2 + ' avis)';
					$('#moyenne').html(etoiles_spect);
					$('#note_utilisateur').html(stars);
				}
			});
		});
	});
	</script>
	</head>
	<body style="background-color:black;"><?php require_once 'base/barremenu.php'; ?>

		<div class="container all" style="background-color:white; min-height:100vh;">
		<?php if($valid) { ?>
		
			<div class="row">
				<div class="text-center col-lg-3 col-md-8 offset-md-2 col-10 offset-1">
					<img class="img-fluid afficheFilm" src="img/affiches/<?php echo $resultat['AFFICHE']; ?>" alt="affiche <?php echo $resultat['TITRE']; ?>" />
				</div>

				<div class="col-lg-5 offset-lg-0 col-md-8 offset-md-2 col-10 offset-1">
					<h1 class="text-uppercase" id="titreFilm"><?php echo $resultat['TITRE']; ?></h1>
					<table class="table">
						<tr>
							<th scope="row">Date de sortie</th>
							<td><?php if(!empty($resultat['DATE_SORTIE'])) { echo intval($date_sortie[2]) . " " . $mois_fr[intval($date_sortie[1])] . " " . $date_sortie[0]; } ?></td>
						</tr>
						<tr>
							<th scope="row">Durée</th>
							<td><?php if($resultat['DUREE'] > 60) { echo intdiv($resultat['DUREE'],60) . " h " . $resultat['DUREE'] % 60 . " min"; } else { echo $resultat['DUREE'] . " min"; } ?></td>
						</tr>
						<tr>
							<th scope="row">Pays prod</th>
							<td><?php echo $resultat['PAYS']; ?></td>
						</tr>
						<tr>
							<th scope="row">Réalisateur</th>
							<td><a href="individus.php?id=<?php echo $realisateur['id_individus']; ?>"><?php echo $realisateur['NOM']; ?></a></td>
						</tr>
						<tr>
							<th scope="row">Catégories</th>
							<td>
								<?php while($donnees_categories = $req_categories->fetch()) { ?>
									<a href="categories_recherche.php?id=<?php echo $donnees_categories['id_categories']; ?>"><?php echo $donnees_categories['NOM']; ?></a>
								<?php }
								$req_categories->closeCursor(); ?>
							</td>
						</tr>
					</table>

					<div class="row">
						<div class="col-6">
							<b>SPECTATEURS</b><br/>
							<div id="moyenne">
								<?php for($i = 0; $i < 5; $i++) { ?>
									<i class="fas fa-star" style="color:<?php if($moyenne >= $i + 1) { echo "gold;"; } else { echo "black;"; } ?>"></i>
								<?php }
								if($moyenne != null) { echo $moyenne; } ?> (<?php echo $nb_avis ?> avis)
							</div>
						</div>

						<div class="col-6">
							<?php if($connecter) { ?>
							<b>VOTRE NOTE</b><br />
							<div class="rating">
								<label>
									<input type="radio" name="stars" value="1" <?php if($note_utilisateur) { if($note_utilisateur['NOTE'] >= 1 AND $note_utilisateur['NOTE'] < 2) { echo "checked"; } } ?> />
									<span class="icon">★</span>
								</label>
								<label>
									<input type="radio" name="stars" value="2" <?php if($note_utilisateur) { if($note_utilisateur['NOTE'] >= 2 AND $note_utilisateur['NOTE'] < 3) { echo "checked"; } } ?> />
									<span class="icon">★</span><span class="icon">★</span>
								</label>
								<label>
									<input type="radio" name="stars" value="3" <?php if($note_utilisateur) { if($note_utilisateur['NOTE'] >= 3 AND $note_utilisateur['NOTE'] < 4) { echo "checked"; } } ?> />
									<span class="icon">★</span><span class="icon">★</span><span class="icon">★</span>   
								</label>
								<label>
									<input type="radio" name="stars" value="4" <?php if($note_utilisateur) { if($note_utilisateur['NOTE'] >= 4 AND $note_utilisateur['NOTE'] < 5) { echo "checked"; } } ?> />
									<span class="icon">★</span><span class="icon">★</span><span class="icon">★</span><span class="icon">★</span>
								</label>
								<label>
									<input type="radio" name="stars" value="5" <?php if($note_utilisateur) { if($note_utilisateur['NOTE'] >= 5) { echo "checked"; } } ?> />
									<span class="icon">★</span><span class="icon">★</span><span class="icon">★</span><span class="icon">★</span><span class="icon">★</span>
								</label>
							</div>
							<span id="note_utilisateur"><?php if($note_utilisateur) { echo $note_utilisateur['NOTE']; } ?></span>
							<?php } ?>
						</div>
					</div>	
					
					<div class="row">
						<div class="col-12">
							<?php if($connecter) { ?>
								<a href="films_rediger_avis.php?id=<?php echo $id ?>">> Rédiger votre avis sur ce film</a>
							<?php }
							else { ?>
								Vous devez être connecté pour pouvoir rédiger un avis sur ce film.
							<?php } ?>
						</div>
					</div>
					
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="filmTitre border-bottom">Synopsis</h1>
					<?php echo nl2br($resultat['SYNOPSIS']); ?>
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="filmTitre border-bottom">Bande-annonce</h1>
					<div class="ratio ratio-16x9">
						<iframe width="560" height="315" src="<?php if(!empty($resultat['LIEN_YOUTUBE'])) { ?>https://www.youtube.com/embed/<?php echo $resultat['LIEN_YOUTUBE']; } ?>" title="YouTube video player" style="border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="filmTitre border-bottom">Acteurs principaux</h1>
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<div class="row">
					<?php while($donnees_acteurs = $req_acteurs->fetch()) { ?>
					<div class="col-lg-3 col-md-4 col-6">
						<a href="individus.php?id=<?php echo $donnees_acteurs['id_individus']; ?>"><img class="img-fluid" src="img/individus/<?php echo $donnees_acteurs['PHOTO']; ?>" alt="photo <?php echo $donnees_acteurs['NOM']; ?>"><?php echo $donnees_acteurs['NOM']; ?></a>
					</div>
					<?php }
						$req_acteurs->closeCursor(); ?>	
					</div>
				</div>
			</div>

			<div class="row pt-3 offset-md-2 offset-1">
				<div class="col-10">
					<h1 class="filmTitre border-bottom">Les 3 derniers avis</h1>
				</div>
			</div>

			<div class="row offset-md-2 offset-1">
				<?php for($i = 0; $i < 3 ; $i++) {
					$donnees_avis = $req_avis->fetch();
					$date_avis = "";
					if(!empty($donnees_avis['DATE_AVIS'])){
						$date_avis = explode('-',$donnees_avis['DATE_AVIS']);
					}
					if($donnees_avis) { ?>
					<div class="row">
						<div class="col-12">
							<?php for($j = 0; $j < 5; $j++) { ?>
							<i class="fas fa-star" style="color:<?php if($donnees_avis['NOTE'] >= $j + 1) { echo " gold;"; } else { echo "black;" ; } ?>"></i>
							<?php } echo $donnees_avis['NOTE']; ?> publié le <?php if(!empty($donnees_avis['DATE_AVIS'])) { echo intval($date_avis[2]) . " " . $mois_fr[intval($date_avis[1])] . " " . $date_avis[0]; } ?> par <?php echo $donnees_avis['PSEUDO']; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<?php echo nl2br($donnees_avis['AVIS']); ?>
						</div>
					</div>
				<?php }
				}
				$req_avis->closeCursor(); ?>
			</div>
		<?php }
		else { ?>
			<h1 class="text-center">Erreur : Mauvais ID film ou inexistant</h1>
		<?php } ?>
		</div>
	</body>
</html>