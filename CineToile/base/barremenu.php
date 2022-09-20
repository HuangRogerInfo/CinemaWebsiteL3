<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="home.php">Ciné-toile</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="categories.php">Catégories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="actualites.php">Actualités</a>
				</li>
			</ul>
			<div class="navbar-text" style="margin-right:5px;">
				<?php if($connecter) { ?> Bienvenue <a style="color:#808080;" href="profil.php"><?php echo $_SESSION['PSEUDO']; ?></a><?php } ?>
			</div>
			<a class="nav-link" id="Compte" href="<?php if($connecter) { ?>deconnexion.php<?php } else { ?>connexion.php<?php } ?>">
				<?php if($connecter) { ?>Se déconnecter<?php } else { ?>Se connecter<?php } ?>
			</a>
			<form method="GET" action="rechercher.php" class="d-flex">
				<input class="form-control me-2" id="autocomplete" type="search" name="rechercher" placeholder="un acteur, un film..." aria-label="Rechercher">
				<button class="btn btn-warning" type="submit">Rechercher</button>
			</form>
		</div>
	</div>
</nav>