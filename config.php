<?php

//D�but des informations � modifier
$poids_max=4194304; //Poids maximal du fichier en octets
$extensions_autorisees=array('png','PNG'); //Extensions autoris�es
//Fin des informations � modifier


function getName($pre,$name_file,$post)
{
	$time1=microtime();
	$time2=str_replace(array(' ','.'),'',$time1);
	$time=substr($time2, 0, 5);
	$cle=mt_rand(0,9);
	return $pre.$name_file.$time.$cle.'.'.$post;
}
?>
