<?php
session_start();
$connecter = false;
if(isset($_SESSION['id_utilisateur']) && isset($_SESSION['PSEUDO']) AND !empty($_SESSION['id_utilisateur']) && !empty($_SESSION['PSEUDO'])) {
	$connecter = true;
}
?>