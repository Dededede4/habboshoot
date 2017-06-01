<?php
include('pass.php');
$pseudopoi = mysql_real_escape_string(htmlspecialchars($_COOKIE['pseudo']));
$reponse = mysql_query('SELECT * FROM membres WHERE pseudo = \''.$pseudopoi.'\'') or die(mysql_error());
$donnees = mysql_fetch_array($reponse);

$code = $donnees['pass'];
$pass = htmlspecialchars(md5($_COOKIE['pass']));


/* On assigne à des variables toutes connes les infos du membres.
 * Il n'y aura plus qu'a les utiliser à chaque page. */

if ( $code === $pass ){
    include('fonctions.php');
    echo getKeyTime($donnees['id']);
}

?>