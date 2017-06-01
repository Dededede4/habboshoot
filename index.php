<?php
$arrayDomain = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
if($arrayDomain[1] !== 'habboshoot'){
	if(header('Location: http://habboshoot.fr'));
	exit;
}

$verifinculde = '1234';
session_start();

include('pass.php'); /* Les Mdp de connexion à la BDD. */
include('fonctions.php'); /*Des fonctions bien pratiques pour gérer plus facilement les kills. */
if ( $_COOKIE['conect_choot'] === 'ok' )
{
	include('pageco.php');
}
else
{
	include('pagede.php');
}
?>


