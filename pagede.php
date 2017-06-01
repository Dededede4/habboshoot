<?php
if($verifinculde != '1234')
{
	exit; // Si quelqu'un va sur pagede.php sans que la page soit inclue à index.php, on le renvoie chier.
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Habboshoot ~ Ici ta vie d'habbo est en jeu...</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style type="text/css">
a:link {

	color: #FF9900;

	text-decoration: none;

	font-weight: bold;

	}

a:visited {

	text-decoration: none;

	color: #FF9900;

	font-weight: bold;

}

a:hover {

	text-decoration: underline;

	color: #FF6600;

	font-weight: bold;

}

a:active {

	text-decoration: none;

	color: #FF9900;

	font-weight: bold;

}



/* Si JS activÃ© */
div.secret div.secret1 {
/* Avec JS activÃ©, on masque par dÃ©faut */
	display: none;
}

/* JS est dÃ©sactivÃ© */

body.nojs div.secret div.secret1 {
/* Par dÃ©faut, on affiche au cas ou on a pas JS activÃ© */
	display: block;
}

div.secret div.secret1 {
	display: none;
}

/* Dans tous les cas */
div.secretshow div.secret1 {
	display: block;
}

div.secrethide div.secret1 {
	display: none;
}
td
{
vertical-align: top;

}
	</style>   

<script type="text/javascript" src="chat.js"></script>

	   <script type="text/javascript">
function isJsEnabled ( ) {
    var b = document.getElementsByTagName ( 'body' );
	b.item(0).className = '';
}

if (top != self) {
top.location.href = location.href;
}

</script>

   </head>
   <body class="nojs" onload="javascript: isJsEnabled ( );" style ="padding-top: 5px; background-color: rgb(0, 0, 0);
width : 1009px;
margin : auto;">
      <div style="width : 1009px;
height : 31px;
background-image: url(img/header.png);
float:left;
padding-top:131px;">
<center>
   <a href="index.php"><img src ="img/bt/accueil.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
   <a href="index.php?pg=connection"><img src ="img/bt/connexion.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
   
    <a href="index.php?pg=inscription"><img src ="img/bt/inscription.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
    </center>
   </div>
  
<div style = "width : 1009px;
float:left; height : 100%;">
<div style="float:left; height : 100%;">

<div style="width : 215px;
height : 28px;
background-image: url(img/hm1.png);"></div>

<div style="min-height : 150px;
background-image: url(img/cm1.png);
font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
text-align: center;
	font-style:inherit;
	font-weight: bold;
width:215px;
">Quand tu seras connecté,<br/>
tu pourras voir l'annonce.<br/>
<br/>
Elle contient les<br/>dernières nouvelles du site<br/>
Avec la date de la partie<br/>
ou le mot kill.<br/>

(Pour tuer. ) <br/><br/>
www.habboshoot.fr<br/>

</div>
<div style="width : 215px;
height : 40px;
background-image: url(img/bm1.png);"></div>

<div style="width : 215px;
height : 25px;
background-image: url(img/hm2.png);"></div>
<!--
<div style="width : 215px;
background-image: url(img/cm1.png);
min-height : 100px;
	font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
text-align: center;
	font-style:inherit;
	font-weight: bold;
"><center><div class="vshop" data-key="cy1w3" data-keyword="jeux vidéos film manga" data-color="845EFF" data-ban="2" style="width: 160px; height: 600px;"></div> <script type="text/javascript" src="http://www.vshop.fr/js/freak2.js"></script></center></div>
-->
<div style="width : 215px;
height : 4px;
background-image: url(img/mbbg1.png);"></div>
</div>
<div style="float:left;">

<div style="width : 579px;
height : 5px;
background-image: url(img/mnmh.png);"></div>

<div style="
width : 569px;
min-height : 500px;
background-image: url(img/mmm.png);
font-family:Verdana, Geneva, sans-serif;
padding:5px;
	font-size:10px;
	font-style:inherit;
	color: white;
	font-weight: bold;
">
<p style = "display: inline;">
<?php
if(!isset($_GET['pg']))
{
	include('regles.php');
}
elseif($_GET['pg'] === 'inscription')
{
	/*Tout sauf propre */
?>
Pour pouvoir profiter du fun que te propose Habboshoot il faut t'inscrire puis te connecter.<br/><br/>
Formulaire d'inscription :<br/>
<small> Tous les champs sont obligatoires.  </small>
<form method="post" action="index.php?pg=inscr2">
<p>
<label for="pseudo">Le pseudo de ton habbo sur habbo.fr ( Si ce n'est pas le cas, ton compte sera supprimé. ) :</label>
<input type="text" name="pseudo" id="pseudo"/>
<br />
<label for="pass">Ton mot de passe. Il doit être différent de celui que tu utilises dans Habbo Hôtel. : </label>
<input type="password" name="pass" id="pass" />
<br />
<label for="pass">Retape-le :</label>
<input type="password" name="pass1" id="pass1" />
<br/>
Quel est l'habbo qui t'a fait découvrir habboshoot ? Tu peux laisser vide.<br/>
<input name="parrin"/><br/>
Retape ce code, il vérifie que tu n'es pas un boot :<br/>
<img src="img.php" border="0" /><br/>
<input type="text" name="code" />
<br />
Tu acceptes les <a href ="index.php">règles</a> : <input type="checkbox" name="oui" />
</p>
<input type="submit" value="Envoyer" />
</form>
<?php
}
elseif($_GET['pg'] == "inscr2")
{

if($_POST['pseudo'] === '' OR $_POST['pass'] === '' OR $_POST['pass1'] === '')
{
	echo 'Il y a eu une erreur, tu as surement oublié de remplir un champ.';
}
else
{
if ($_POST['pass'] == $_POST['pass1'])
{
	if(@file_get_contents('http://www.habbo.fr/habbo-imaging/avatarimage?user='.urlencode($_POST['pseudo'])) !== false)
		{
			if(strlen($_POST['pseudo']) < 21 )
			{
				if( $_POST['oui'] === 'on' )
				{
					if ( $_POST['code'] === $_SESSION['chaine'] )
					{
						/* À se stade :
						*  Code de l'image : OK 
						* Pseudo : Pas trop long
						* Le pseudo existe sur Habbo.FR
						* Mot de passe : OK
						* Régles : Acceptées
						* Bref il reste plus que de verifier s'il c'est inscrit il y a moins de 24 heures et puis on l'inscrit avec la table "ip".
						* $_POST['pseudo'];
						* $_POST['pass'];
						* $_SERVER['REMOTE_ADDR'];
						* 
						* 1 journée = 86400 secondes ( J'ai compté )
						* 
						*/
						
						$timeDhier = time() - 86400;
						
						$info = mysql_query('SELECT * FROM ip WHERE ip = \''.$_SERVER['REMOTE_ADDR'].'\' ORDER BY time DESC LIMIT 1')or die(mysql_error());
						$donnees = mysql_fetch_array($info);
						
						if($donnees['time'] < $timeDhier )
						{
		
							/* Voilà voilà, il reste plus que donner 5 pièces pour le champ :
							* «Quel est l'habbo qui t'as fait découvrir habboshoot ? Tu peux laisser vide.»
							* $_POST['parrin'] = le parrin, peux être vide.
							*/
	

							if($_POST['parrin'] !== '')
							{
								$info = mysql_query('SELECT * FROM membres WHERE pseudo = \''.mysql_real_escape_string(htmlspecialchars($_POST['parrin'])).'\' AND ip != \''.$_SERVER['REMOTE_ADDR'].'\' LIMIT 1')or die(mysql_error());
							
								if( mysql_num_rows($info))
								{
									mysql_query('UPDATE membres SET cash = cash + 5 WHERE pseudo=\''.mysql_real_escape_string(htmlspecialchars($_POST['parrin'])).'\'')or die(mysql_error());
								}
							}

	
	
							// j'oublais qu'il fallais empecher les habbos de crée plusieurs comptes avec le même pseudo.
	
							$info = mysql_query('SELECT * FROM membres WHERE pseudo = \''.mysql_real_escape_string(htmlspecialchars($_POST['pseudo'])).'\' LIMIT 1')or die(mysql_error());
							if( !mysql_num_rows($info))
							{
	
								// Pour un compte toutes les 24 heure.
	
								mysql_query('INSERT INTO ip VALUES(\'\',
								\''.$_SERVER['REMOTE_ADDR'].'\',
								'.time().')') or die(mysql_error());

								// Voilà, on l'inscrit !
	
								mysql_query('INSERT INTO membres VALUES(\'\',
								\''.mysql_real_escape_string(htmlspecialchars($_POST['pseudo'])).'\',
								\''.md5(md5($_POST['pass'])).'\',
								0,
								\'Membre\',
								\''.$_SERVER['REMOTE_ADDR'].'\',
								'.time().',
								'.time().',
								\'Aucune\',
								0, 0, 0, 0, 2, 3, 0, 0, 0, 0, 0,
								\''.mysql_real_escape_string($_POST['parrin']).'\',
								0,
								\'a:0:{}\')')or die(mysql_error());

	
								// Puis on démolit le code anti-boot. ( je suis un sauvage. )
	
								$_SESSION['chaine'] = ''; 
	
								// Fini !
	
	
								echo 'Tu t\'es inscrit, tu peux te connecter ;)';
	
							}
							else
							{
								echo 'Un autre killer utilise ce pseudo.';
							}
						}
						else
						{
							echo 'Crée seulement un compte par jour, s\'il te plait.';	
						}
					}
					else
					{
						echo 'Le code sur l\'image n\'est pas bon... <br/> <br/> Tu ne serais pas un boot ?<br/> Le code doit être en majuscule.';
					}
				}
				else
				{
					echo 'Il faut accepter les règles, pour un jeu fun.';
				}
			}
			else
			{
				echo 'Ce n\'est pas ton pseudo habbo !';
			}
		}
		else
		{
			echo 'Cet habbo n\'existe pas sur habbo.fr !!!';
		}
	}
	else
	{
		echo 'Tes deux mots de passe sont différents.';
	}
}
}
elseif($_GET['pg'] == "connection")
{
?>
Formulaire pour te connecter.
<center>
<form method="post" action="connection.php">
<p>
<label for="pseudo">Nom :</label>
<input type="text" name="pseudo" id="pseudo"/>
<br />
<label for="pass">Code :</label>
<input type="password" name="pass" id="pass" />
<br />
</p>
<input type="submit" value="Envoyer" />
</form>
</center>
Il faut accepter les cookies, par défaut dans tous les navigateurs.<br/><br/>

Si tu as perdu ton mot de passe, tape ton pseudo ici :<br/>
<center>
<form method="get" action="index.php">
<input name="habbo" type="text">
	   <input name="pg" value="retrouvetoncode" type="hidden">
   <input value="Envoyer" type="submit">
</form>
</center>
<?php
}
elseif($_GET['pg'] == "retrouvetoncode"){
	$habbo = htmlentities($_GET['habbo']);
	$vide = file_get_contents("http://www.habbo.fr/home/".$habbo);
	$source = preg_replace('#\s#s', '', $vide);
	if(!isset($_SESSION['phrase2recup'])){
		$_SESSION['phrase2recup'] = 'CHANG-MO2PASS-HABBOSHOOT'.genererMDP(8);
	}
	if(strpos($source, '<divclass="profile-motto">'.$_SESSION['phrase2recup']) !== false){
		$habbovalide = true;
	}
	else{
		$habbovalide = false;
	}
	if($_GET['suite'] !== 'true'){
		$result = mysql_query("SELECT * FROM membres WHERE pseudo = '". mysql_real_escape_string($_GET['habbo'])."'");
		if(mysql_num_rows($result) == 0){
			echo 'Ce compte habbo n\'existe pas sur habboshoot.';
		}
		else{
			
			echo 'Procédure de récupération du mot de passe :
			<ol>
			<li>Soit sûr que ta <a href="http://www.habbo.fr/home/'.$habbo.'" target="_back">habbo home page</a> soit accessible au public.</li>
			<li>Soit sûr qu\'il ait le widget « Mon profil »</li>
			<li>Modifie ton statut (c\'est à dire mission la mission apparaîtra sur ta Habbo Home page et sur ton Habbo à l\'intérieur de l\'hôtel) en : <br/><u>'.$_SESSION['phrase2recup'].'</u></li>
			<li>Quand tout est fait, <a href="index.php?pg=retrouvetoncode&amp;habbo='.htmlentities($_GET['habbo']).'&suite=true">clique ici.</a>
			</ol>';
		}
	}
	else{
		if($habbovalide){
			$_SESSION['habbo'] = $_GET['habbo']
			?>
			Tu peux changer ton code :<br/><br/>
			<center>
					 <form action="index.php?pg=modifcode" method="post">
							Ton nouveau code : <input type="password" name="pass1" /> <br/>
							Retape le : <input type="password" name="pass2" /> <br/>
					   <input type="submit" value="Envoyer" />
					</form>
					</center>
			<?php
		}
		else{
			echo "Erreur. Soit habbo hôtel est en panne, soit tu n'as pas effectué les points précédemment énumérés.";
		}
	}
}
elseif($_GET['pg'] == "modifcode" AND isset($_SESSION['habbo'])){
	if ( $_POST['pass1'] != "" AND $_POST['pass2'] != "" ){
		if ( $_POST['pass1'] == $_POST['pass2'] ){
			$habbo = mysql_real_escape_string($_SESSION['habbo']);
			$reponse = mysql_query("SELECT pass FROM membres WHERE pseudo ='".$habbo."'") or die(mysql_error());
			$code = mysql_real_escape_string(htmlspecialchars(md5(md5($_POST['pass2']))));
			mysql_query("UPDATE membres SET pass='$code' WHERE pseudo='".$habbo."'") or die(mysql_error("Tentative de hack"));
			session_destroy();
			echo 'Le mot de passe est changé, tu peux te connecter et remettre ta mission comme tu l\'entends';
		}
		else{
			echo "Tu as tapé 2 codes différents !";
			session_destroy();
		}
	}
	else
	{
		echo "Remplit TOUS les champs pour changer ton code...";
		session_destroy();
	}
}
else{
echo "La page que tu demandes n'est pas accessible sans être connecté à Habboshoot. Inscris-toi et connecte-toi.";
}
?>
</p>
</div>
<div style="width : 579px;
height : 4px;
background-image: url(img/mbbm1.png);"></div>
</div>

<div style="float:left; height : 100%;">

<div style="width : 215px;
height : 19px;
background-image: url(img/mndb1.png);
"></div>

<div style = "background-image: url(img/mmd.png); height: 10px;"></div>

<div style="min-height : 150px;
width : 215px;
background-image: url(img/mmd.png);">

<div style ="margin-left:22px;
width : 153px;
height : 7px;
background-image: url(img/ch.png);
"></div>
<div style ="margin-left:22px;
width : 150px;
background-image: url(img/fch.png);
text-align: left;
padding-left: 3px;
height: 350px;
overflow: auto;
font-family:Verdana, Geneva, sans-serif;
font-size:10px;"
id ="zonechatmillieu"></div>
<div style ="margin-left:22px;
width : 193px;
height : 43px;
background-image: url(img/chb.png);
"></div>
</div>

<div style="width : 215px;
height : 25px;
background-image: url(img/mhd.png);"></div>
<!--
<div style="min-height : 100%;
width : 215px;
background-image: url(img/mmd.png);
	font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
text-align: center;
	font-style:inherit;
	font-weight: bold;
"><center>Pub :<br/><br/>
-->

   </center></div>
<div style="width : 215px;
height : 4px;
background-image: url(img/mnbbd.png);"></div>

</div>

</div>
</div><!-- Piwik -->
<script type="text/javascript"> 
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://piwik.ropno.fr//";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://piwik.ropno.fr/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Code -->

   </body>
</html>
