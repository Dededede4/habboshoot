<?php

include('pass.php');

$pseudo = $_POST['pseudo'];
$pass = md5($_POST['pass']);
$timestamp_expire = time() + 365*24*3600; // Le cookie expirera dans un an
setcookie('pseudo', $pseudo, $timestamp_expire); // On écrit un cookie
setcookie('pass', $pass, $timestamp_expire); // On écrit un autre cookie...
$pseudo = htmlspecialchars($_POST['pseudo']);
$query = "SELECT * FROM membres WHERE pseudo = '".$pseudo."'"; 
$result = mysql_query($query) or die ();
if($pseudo != "")
{
		if(mysql_num_rows($result) == 0)
			{
			$msgconect = "Erreur : Pseudo inconu.";
			mysql_close();
			}
			else
			{
			$pseudo = mysql_real_escape_string(htmlspecialchars($_POST['pseudo']));
			$pass = mysql_real_escape_string(htmlspecialchars($_POST['pass']));
			$pass1 = md5($pass);
			$pass2= md5($pass1);
			$reponse = mysql_query("SELECT * FROM membres WHERE pseudo ='$pseudo'") or die(mysql_error()); 
			while ($donnees = mysql_fetch_array($reponse)) 
			{
			$code = $donnees['pass'];
			
			if ( $code == $pass2 )
			{
				$date = time();
				$msgconect = 1;
				$ip = $_SERVER['REMOTE_ADDR'];
				mysql_query("UPDATE membres SET dateco='$date' , ip='$ip' WHERE pseudo='$pseudo' LIMIT 1") or die(mysql_error());
				$conf = 1;
			}
			else
				{
				$msgconect = "Erreur : Mot de passe incorrect.";
				}
				}
			}
}
if ( "$msgconect" == 1 )
{
header('Location: index.php');
setcookie('conect_choot', 'ok', $timestamp_expire); // On écrit un autre cookie...
}
else
{

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>HabboShoot ~ Ta vie est en jeu...</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="css.css" />
	   		<script type="text/javascript" src="spoiler.js"></script>
		<script type="text/javascript">
		<!--
		document.write('<link href="spoiler.css" rel="stylesheet" type="text/css" media="print, screen" />');
		-->
		</script>
<?php echo $msgconect; ?>
<br/><br/><a href ="index.php?pg=connection" />Clique ici pour ré-essayer</a>
   </head>
   <body>
   </body>
  </html>
  <?php } ?>
