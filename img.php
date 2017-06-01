<?php
session_start();
$chaine = 'ABCDEFGHIJKLMNPQRSTUVWXYZ'; // $Chaine Contient toute ces letres et chifres
$chaine = str_shuffle($chaine); // On mélange se qu'il y a dans $chaine et on le mais dans $chaine ;o)
$chaine = substr($chaine, 0, 5); // On garde uniquement les 5 premierres lettres de $chaines, on les mais dans $ chaines :p
//$Chaines contient donc 5 caractaire majuscule entre A et Z, et/ou des chifres.
$_SESSION['chaine'] = $chaine; // On conserve $chaine dans la session, pour pouvoir conparer se que a taper le type plus tar,

header ("Content-type: image/png"); // On dit que c'est un *.php mais considéré en image
$image = imagecreate(42,15); // On cree une image blanche de 42 L pat 15l.

$noir = imagecolorallocate($image, 51, 51, 51); // Un joli font noir
$blanc = imagecolorallocate($image, 255, 255, 255); // Et $blanc contient du blanc.

imagestring($image, 4, 1, 0, $chaine, $blanc); // On ecris sur l'image le code alléatoire, a partir 1 de largeur et 0 de longeur a partir du haux a gauche.
imagepng($image); // ;o)
?>
