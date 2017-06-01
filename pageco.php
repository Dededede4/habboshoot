<?php 
if($verifinculde != '1234')
{
	exit; // Si quelqu'un va sur pageco.php sans que la page soit inclue à index.php, on le renvoie chier.
}


$motkill = partie(); // Déclanche le système de partie. 


/* À chaque actualisation de la page, on récupére les infos du membres.
 */

$pseudopoi = mysql_real_escape_string(htmlspecialchars($_COOKIE['pseudo']));
$reponse = mysql_query('SELECT * FROM membres WHERE pseudo = \''.$pseudopoi.'\'') or die(mysql_error());
$donnees = mysql_fetch_array($reponse);

/*
 * On vérifie s'il est banni, et quand son ban est fini on le déban.
 * à noter que dans la table membre, si la case pv est égale à :
 * 
 * 0 = Mort
 * 1 = En attente de validation
 * 2 = Vivant
 * 3 = Banni
 */

$reponseban = mysql_query('SELECT * FROM ban WHERE pseudo =\''.$pseudopoi.'\' OR ip = \''.$_SERVER['REMOTE_ADDR'].'\' ORDER BY time LIMIT 1 ') or die(mysql_error());
$donneesban = mysql_fetch_array($reponseban);

	if ($donneesban['time'] >= time() OR $donneesban['time'] == 'deff' )
	{
		if($ban === 'oui')
		{
			break;
		}
		else
		{
			$ban = 'oui';
			$timeban = $donneesban['time'];
			$resonban = $donneesban['reson'];
		}
	}
	elseif ($donnees['pv'] == '3')
	{
		mysql_query('UPDATE membres SET pv = \'2\' WHERE id=\''.$donnees['id'].'\'') or die(mysql_error());
	}

/* On met à jour l'IP et ça date de connexion. */

mysql_query('UPDATE membres SET dateco=\''.time().'\' , ip=\''.$_SERVER['REMOTE_ADDR'].'\' WHERE id=\''.$donnees['id'].'\'') or die(mysql_error());

$code = $donnees['pass'];
$pass = htmlspecialchars(md5($_COOKIE['pass']));
$contenu = $donnees['description'];

/* On assigne à des variables toutes connes les infos du membres.
 * Il n'y aura plus qu'a les utiliser à chaque page. */

if ( $code === $pass ){

	$date = time();
	$msgconect = 1;
	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $donnees['id'];
	$oui = "oui";
	$grade = $donnees['statu'];
	$pseudo = $donnees['pseudo'];
	$contenu = $donnees['description'];
	$pieces = $donnees['cash'];
	$totalp = $donnees['totalp'];
	$newmsg = $donnees['vmsg'];
	$espionage = $donnees['espionage'];
	$discretion = $donnees['discretion'];
	$balles = $donnees['balles'];
	$gilet = $donnees['pareballes'];
	$pv = $donnees['pv'];
	$couteau = $donnees['couteau'];
	$meurtre = $donnees['meutre'];
	$parrain = $donnees['parrin'];
	$datein = $donnees['datein'];
	$iddesonclan = $donnees['id_clan'];
	$vote = $donnees['vote'];
	$esquive = $donnees['esquive'];
	$invisible = $donnees['invisible'];
	$_SESSION['grade'] = $grade;
	$_SESSION['pseudo'] = $pseudo;
	$_SESSION['id'] = $id;
}
else
{
	$oui = 'non';
	@header('Location: deconnection.php');
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title id="titre"><?php echo getKeyTime($id) ?> ~ Habboshoot</title>
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
		
		
		/* Là, bah c'est les forumulaires. */
		input, textarea {
			font-family:Verdana, Geneva, sans-serif;
			font-size:10px;
			font-style:inherit;
			color: white;
			font-weight: bold;
			background-color: #464646;
			border: 1px solid #696E75;
		}
		
		/* Fin des formulaires */
		
		
		
		td {
			vertical-align: top;
		}
	</style>   

<script type="text/javascript" src="chat.js"></script>
<script type="text/javascript">
	
function confirmSubmit(texte) {
	var okconfirm=confirm(texte);
	if (okconfirm)
		return true ;
	else
		return false ;
}
function isJsEnabled () {
document.getElementsByTagName('body').item(0).className = '';
}
var timestamp = <?php echo time()+1; ?>; // +1 car setinterval 1000,
function WinTime(){
	timestamp++;
	
	var now = new Date(timestamp*1000);
	var heure = now.getHours();
	var min = now.getMinutes();
	var sec = now.getSeconds();
	if (heure<10) {heure = "0" + heure}
	if (min<10) {min = "0" + min}
	if (sec<10) {sec = "0" + sec}
	document.getElementById('horloge').innerHTML=heure+":"+min+":"+sec;
	
	if(min == '01' && sec == '00'){
		var ajaxvar = new Ajax();
		
		ajaxvar.onreadystatechange = function() {
			if(ajaxvar.readyState == 4 && ajaxvar.status == 200){
				 document.title = ajaxvar.responseText+' ~ Habboshoot';
				document.getElementById('horlogekeytime').innerHTML = ajaxvar.responseText;
			}
		};
		ajaxvar.open("GET", 'keytime.php', true);
		ajaxvar.send(null);
	}
	// document.title=heure+":"+min+":"+sec+" ~ HabboShoot";
}				
                </script>

 </head>
<body class="nojs" onload="javascript: isJsEnabled (); setInterval(WinTime,1000);" style ="padding-top: 5px; background-color: rgb(0, 0, 0);
width : 1009px;
margin : auto;
background-image: url(img/dede.png); /* Le fond est l'image dede.png */
background-repeat: no-repeat; /* Le fond ne se répète pas */
background-position: bottom right;
background-color: #000000;
">
<?php
$reponse = mysql_query('SELECT * FROM message WHERE pseudo =\''.$pseudo.'\' AND lut = \'non\'') or die(mysql_error());

if(mysql_num_rows($reponse) > 0)
{
	while($donnees = mysql_fetch_array($reponse))
	{
		?>
		
		<script>alert("Le <?php echo date('d/m/Y \à H:i', $donnees['time']); ?>, par <?php echo $donnees['lancer']; ?>.\n\n<?php echo stripslashes(str_replace(array("\n", "\r"), array("\\n", ''), $donnees['message'])); ?>\n\n Ce message t'es envoyé de la part de la modération d'habboshoot.")</script>
		
		<?php
		
		mysql_query("UPDATE message SET lut = 'oui' WHERE ID ='".$donnees['ID']."'") or die(mysql_error());
	}
}
?>
<div style="width : 1009px;
height : 31px;
background-image: url(img/header.png);
float:left;
padding-top:131px;">
<center>
   <a href="index.php"><img src ="img/bt/accueil.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
    <a href="index.php?pg=assassinat"><img src ="img/bt/assassinats.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
   <a href="index.php?pg=killeurs"><img src ="img/bt/killeurs.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
   <?php
   if($grade === 'Admin'){ echo '<!--- <a href="index.php?pg=roomdead"><img src ="img/bt/groupes.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a> --> '; } ?>
    <a href="index.php?pg=fiches&habbo=<?php echo $pseudo; ?>"><img src ="img/bt/profil.gif" border = "0" style = "margin-right:15px; margin-left:15px;" /></a>
	<a href="index.php?pg=forum" ><img src ="img/bt/forum.gif" style = "margin-right:15px; margin-left:15px;" border = "0"/></a>
	<a href="deconnection.php"><img src ="img/bt/deconnexion.gif" style = "margin-right:15px; margin-left:15px;" border = "0"/></a></center>
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
width:215px;
font-size:10px;
text-align: center;
font-style:inherit;
font-weight: bold;
">
	<?php
	
	if($motkill != '')
	{
		echo 'La partie est ouverte,<br/>bonne chance !!<br/><br/>Le mot kill est '.$motkill;
	}
	else
	{
		echo 'La partie est en pause,<br/> elle reprend demain.';
	}
	echo '<br/><br/>';
	$result = mysql_query('SELECT * FROM msg LIMIT 1');
	$donnees = mysql_fetch_array($result);
	
	$messagedelanonce = stripslashes($donnees['message']);
	
	/* À noter que l'on réutilise $messagedelanonce dans la page $_GET['Admin'] */
	
	echo nl2br(bbcode($messagedelanonce));
	?>
	<br/><br/>
www.habboshoot.fr<br/>
<br/>

<?php
if($grade === 'Admin' OR $grade === 'Arbitre')
{
	echo '<br/><a href="index.php?pg=Admin"><br/>Change !</a>';
}
?></div>

<div style="width : 215px;
height : 40px;
background-image: url(img/bm1.png);"></div>

<div style="width : 215px;
height : 25px;
background-image: url(img/hm2.png);"></div>

<div style="width : 215px;
background-image: url(img/cm1.png);
min-height : 100px;
	font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
text-align: center;
	font-style:inherit;
	font-weight: bold;
"><?php echo $pseudo ?> :<br/><br/>
   <a href="index.php?pg=boutique">Boutique</a>
   <br/><?php //echo '<a href="index.php?pg=tresor">Trésor Public</a><br/>'; ?>
<br/>
      <br/><br/>
Tu es
<?php if($pv == 0) echo 'mort'; elseif($pv == 1) echo 'en attente de validation'; elseif($pv == 2) echo 'en vie'; elseif($pv == 3) echo 'banni'; ?>.<br/>
   Tu as  <?php echo $pieces ?> pièces.<br/>
   Tu as <?php echo $balles ?> balle(s).<br/>
   Tu as <?php echo $couteau ?> couteau(x).<br/>
   <br/>
   
   Heure serveur : <div id="horloge"></div>
   (KeyTime : <span id="horlogekeytime"><?php echo getKeyTime($id) ?></span>)
   <br/><br/>
<a href="index.php?pg=news&id=7">Codes sources</a><center>
</div>

<div style="width : 215px;
height : 28px;
background-image: url(img/hm1.png);">
</div>
<!--
<div style="width : 215px;
background-image: url(img/cm1.png);
min-height : 100px;
	font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
text-align: center;
	font-style:inherit;
	font-weight: bold;
"><center>
<div class="vshop" data-key="cy1w3" data-keyword="jeux vidéos film manga" data-color="845EFF" data-ban="2" style="width: 160px; height: 600px;"></div> <script type="text/javascript" src="http://www.vshop.fr/js/freak2.js"></script></center></div>
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
if ( $ban == 'oui' )
{
	if ( $timeban == 'deff' )
	{
		echo 'Tu es banni définitivement.<br/>Le responsable qui a fait cela a donné cette raison : '.$resonban;
	}
	else
	{
		$date = date('d/m/Y à H:i', $timeban);
		echo 'Tu es banni.<br/> Il faut attendre le '.$date.' . <br/> Le responsable qui a fait ça donne la raison : '.$resonban;
	}
}
else
{
	
if(!isset($_GET['pg']))
{ 
	include('regles.php');
}
elseif($_GET['pg'] === 'killeurs')
{
	?><center>
	<form method="get" action="index.php">
		   Cherche un killeur : <input type="text" name="habbo"/>
		   <input type="hidden" name="pg" value="fiches" />
	   <input type="submit" value="Envoyer" />
	</form><br/></center>

	<div style="height:171px;">
	<div style = "width:150px; background-color: #414141; margin: auto; min-height:100%; margin-top:10px; margin-left:25px; padding:5px; padding-top:3px; float:left;">

	Derniers killeurs inscrit :
	<ol>
	<?php
	$reponse = mysql_query('SELECT * FROM membres WHERE invisible < '.time().' ORDER BY datein DESC LIMIT 0,10')or die(mysql_error());
	while ($donnees = mysql_fetch_array($reponse))
	{
		?>
		
		<li>
		<a href ="index.php?pg=fiches&habbo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a>
		</li>
		
		<?php
	}
		   ?>
	   
	</ol>
	</div>
	 <div style = "width:150px; background-color: #414141; margin: auto; min-height:100%;  margin-top:10px; margin-left:25px; padding:5px; padding-top:3px; float:left;">
	Killeurs online :<ol>
	<?php
	$reponse = mysql_query('SELECT * FROM membres WHERE dateco > '.time().' - 60*5 AND invisible < '.time().' ORDER BY dateco DESC')or die(mysql_error());
	while ($donnees = mysql_fetch_array($reponse))
	{
		echo '<li><a href ="index.php?pg=fiches&habbo='.$donnees['pseudo'].'">'.$donnees['pseudo'].'</a></li>';
	}
	?></ol>
	</div>
	<div style = "width:150px; background-color: #414141; min-height:100%; margin: auto; margin-top:10px; padding:5px; margin-left:25px; padding-top:3px; float:left;">
	<?php
	echo 'Têtes mises à prix :';
	$result = mysql_query('SELECT * FROM miseprix WHERE zigouiller = \'non\' GROUP BY tete ORDER BY SUM(prix) DESC LIMIT 10')or die(mysql_error());
	echo '<ol>';
	while($donnees = mysql_fetch_array($result)){
		$reponse = mysql_query('SELECT COUNT(*) AS is_invisible FROM membres WHERE pseudo = \''.mysql_real_escape_string($donnees['tete']).'\' AND invisible > '.time().' LIMIT 1')or die(mysql_error());
		$rep = mysql_fetch_array($reponse);
		if(!$rep['is_invisible']){
		echo '<li><a href = "index.php?pg=fiches&habbo='.$donnees['tete'].'">'.$donnees['tete'].'</a></li>';
		}
	}
	?>
	</ol>
	</div></div>
<div style="clear:left;">Exceptionnellement pour la réouverture, nous donnons la liste des killeurs connectés dans l'hotel, même s'ils sont inactifs, même s'ils sont invisibles :
<?php
	$result = mysql_query('SELECT * FROM connecteHabbo')or die(mysql_error());
	echo '<ol>';
	while($donnees = mysql_fetch_array($result)){
		echo '<li><a href = "index.php?pg=fiches&habbo='.$donnees['pseudo'].'">'.$donnees['pseudo'].'</a></li>';
	}
	echo '</ol>';
	?>
	</div>
	 <?php
	$reponse = mysql_query("SELECT * FROM membres WHERE dateco  + (24*3600*14) > '".time()."' AND pv = '2' AND invisible < ".time()." ORDER BY pseudo")or die(mysql_error());

	$retour = mysql_query("SELECT COUNT(*) AS nbre_entrees FROM membres");
	$donnees303 = mysql_fetch_array($retour);
	?><div style="clear:left;"><br/><?php
	echo mysql_num_rows($reponse).' victimes potentielles pour '.$donnees303['nbre_entrees'].' membres :'; 
	if($motkill === '')
	{
		echo '<br/><font color="red">La partie est finie, tu ne peux donc tuer personne jusqu\'à demain.</font>';
		}?>
	</div><?php
	echo '<div style = "width:560px; background-color: #414141; margin: auto;">';
	while ($donnees = mysql_fetch_array($reponse) )
	{
	if(strtoupper($donnees['pseudo']['0']) !==  strtoupper($thelettre) )
	{
		echo '</div><div style = "width:560px; background-color: #414141; margin: auto; margin-top:10px; padding:5px; padding-top:0px;clear:left;">';
		echo ' <h3>'.strtoupper($donnees['pseudo']['0']).'</h3>';
	}
	else{
		echo ' | ';
	}
	$thelettre = $donnees['pseudo']['0'];
	?>
	
	<a href ="index.php?pg=fiches&habbo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a>
	<?php
	if($donnees['pareballes'] > time())
	{
	echo ' [Gilet] ';
	}
	}
	?>
	<?php

	 ?>

	</div>
	 
	<?php

}
elseif($_GET['pg'] === 'fiches')
{
$query = mysql_query('SELECT * FROM membres WHERE pseudo = \''.mysql_real_escape_string(htmlspecialchars($_GET['habbo'])).'\'')or die(mysql_error());
		if(!mysql_num_rows($query))
			{
			echo 'Le pseudo «'.htmlspecialchars($_GET['habbo']).'» n\'existe pas dans habboshoot. <br/> Voici les pseudos qui commencent avec les mêmes caractères :';
$pseudorecherche = mysql_real_escape_string(htmlspecialchars(substr($_GET['habbo'], 0, 3)));
$resulta = mysql_query('SELECT * FROM membres WHERE pseudo LIKE \''.$pseudorecherche.'%\'')or die(mysql_error());
		if(mysql_num_rows($resulta) == 0)
			{
echo '<br/>Aucune réponse...';
			}
			else
			{
			echo '<ul>';
			while($donnee = mysql_fetch_array($resulta))
			{
			echo '<li><a href ="index.php?pg=fiches&amp;habbo='.$donnee['pseudo'].'">'.$donnee['pseudo'].'</a></li>';
			}
			echo '</ul>';
			}
		
			}
		else
		{
$donnees = mysql_fetch_array($query);
echo '<div style="float:left; background-color: rgb(65, 65, 65); padding:5px; width: 219px; height:48px;"><h2 style="display: inline">'.$donnees['pseudo'].'</h2>';
// Pour savoir s'il est connecté sur habbo.
if(preg_match('#habbo_online_anim.gif"#', @file_get_contents('http://habbo.fr/home/'.$donnees['pseudo']))) 
{
echo ' <img src="img/online.gif" border="0" title="On estime qu\'il est en ligne sur habbo.">';
}
else
{
echo ' <img src="img/offline.gif" border="0" title="On estime qu\'il est pas en ligne sur habbo.">';
}

echo '<br/>'.gradelvl($donnees['meutre']).'.<br/>
<a href="http://www.habbo.fr/home/'.$donnees['pseudo'].'">Home page</a></div>';

echo '<div style="float:right; background-color: rgb(65, 65, 65); padding:5px; margin-left:5px; width:70px; height:48px;""><h2 style="display: inline">'.$donnees['cash'].' </h2> <img src="/img/pieces.gif" border="0">
<br/>Pièce(s)</div>';

echo '<div style="float:right; background-color: rgb(65, 65, 65); padding:5px; margin-left:5px; width:70px; height:48px;""><h2 style="display: inline">'.$donnees['meutreparty'].' </h2>
<br/>Meurtre(s) cette partie </div>';

echo '<div style="float:right; background-color: rgb(65, 65, 65); padding:5px; margin-left:5px; width:70px; height:48px;""><h2 style="display: inline">'.$donnees['meutre'].' </h2>
<br/>Meurtre(s) au total</div>';

echo '<div style="float:right; background-color: rgb(65, 65, 65); padding:5px; margin-left:5px; width:70px; height:48px;""><h2 style="display: inline">'.round($donnees['ratio'], 3).' </h2>
<br/>Ratio </div>';



echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">';
if($donnees['dateco'] + ( 3600*24*14) > time()){if($donnees['pv'] == 0) echo 'Mort'; elseif($donnees['pv'] == 1) echo 'En attente'; elseif($donnees['pv'] == 2) echo 'En vie'; elseif($donnees['pv'] == 3) echo 'Banni'; }else{ echo 'Inactif'; }
echo'</h2></div>';
echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">'.$donnees['balles'].'</h2> balle(s)</div>';
echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">'.$donnees['couteau'].'</h2> couteau(x)</div>';
if($donnees['pareballes'] > time()){
	echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">Gilet</h2> pare-balles</div>';
}

$resultMisePrix = mysql_query('SELECT * FROM miseprix WHERE zigouiller = \'non\' AND tete=\''.$donnees['pseudo'].'\' ')or die(mysql_error());
$liste = '';
$sommePrix = 0;
$donneurs = array();
while($donneesMisePrix = mysql_fetch_array($resultMisePrix))
{
	$donneurs[] = $donneesMisePrix['acheteur'];
	$sommePrix = $sommePrix + $donneesMisePrix['prix'];
}
$donneurs = array_unique($donneurs);
if($sommePrix > 0){
	echo '<div style="float:left; background-color: rgb(65, 65, 65); padding:5px; width: 219px; height:48px;margin-top: 5px;">';
	foreach($donneurs as $value){
		echo $value.' ; ';
	}
	echo 'donne(nt) '.$sommePrix.'pièces pour sa tête.</div>';
}
if($donnees['esquive'] > 0){
	echo '<div style="clear:left; float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">Esquive</h2></div>';
}
if($donnees['invisible'] > time()){
	echo '<div style="clear:left; float:right; background-color: rgb(65, 65, 65); margin-left:5px; width: 70px; margin-top: 5px; height:48px; padding: 5px;"><h2 style="display: inline">Invisible</h2></div>';
}

echo '<div style="float:left; background-color: rgb(65, 65, 65); padding:2px; width:480px; min-height:100px; clear:left; margin-top: 5px;">'.nl2br(bbcode(stripslashes($donnees['description']))).'</div>';

// Partie qui colle à droite du profil.
echo '<div style="text-align: right; float:right;"><img src="http://www.habbo.fr/habbo-imaging/avatarimage?user='.$donnees['pseudo'].'&amp;action=std&amp;frame=3&amp;direction=4&amp;head_direction=4&amp;gesture=agr&amp;size=b&amp;img_format=gif">';
if($donnees['dateco'] + ( 3600*24*14) > time()){if ($pv == 2 AND $donnees['pv'] == 2) {
if($motkill !== ''){/*if($parrain != $donnees['pseudo']) {*/?><br/><a href="index.php?pg=formulaire&amp;habbo=<?php echo $donnees['pseudo']; ?>">Je l'ai tué !!!  </a><?php }}}//}
echo '<br/>';
//Pour afficher les badges des pseudo :

//Les automatiques ( Pour admins ou Arbites )

if($donnees['statu'] === 'Admin')
{
	echo '<img src="img/admin.png" border="0" title="Admin" style="margin-right: 16px; margin-top:2px;">';
}
if($donnees['statu'] === 'Arbitre')
{
	echo '<img src="img/modo.png" border="0" title="Arbitre" style="margin-right: 16px; margin-top:2px;">';
}

// Ceux rajouté à la main ( Par ex après un concours )

$info = mysql_query('SELECT * FROM badges WHERE pseudo = \''.$donnees['pseudo'].'\'')or die(mysql_error());
while($donneesbadge = mysql_fetch_array($info))
{
echo '<br/><img src="'.$donneesbadge['url'].'" alt="Badge" title="'.$donneesbadge['blabla'].'" border="0" style="margin-right: 16px; margin-top:2px;"/>';
}
echo '</div>';


echo '<div style="float:left; margin-top: 10px; width: 500px;">Petites infos en plus :
<ul>
<li>Dernère connexion : '.date('d/m/Y H:i', $donnees['dateco']).'</li>
<li>Inscrit le : '.date('d/m/Y H:i', $donnees['datein']).'</li>
</ul>
';
if($grade === 'Admin' OR $grade === 'Arbitre')
{
	echo '<ul>
	<li>IP : '.$donnees['ip'].'</li>
	</ul>';
}
?>

<?php
echo '</div>';
// meutres, échanges, actions arbitraires.
echo '<div style="float:right; background-color: rgb(65, 65, 65); clear:right; margin-top: 10px; padding:2px; width: 270px; height: 120px;">Il a été victime de :';
$info = mysql_query('SELECT * FROM screen WHERE victime = \''.$donnees['pseudo'].'\' ORDER BY time DESC LIMIT 5')or die(mysql_error());
if( mysql_num_rows($info))
{
	echo '<ul>';
while($donneesvictimes = mysql_fetch_array($info))
{
	echo '<li><a href="/index.php?pg=voirkill&pagekillid='.$donneesvictimes['ID'].'">'.$donneesvictimes['killeur'].'</a>';
	if($donneesvictimes['valid'] === 'pas valide')
	{
	echo ' [Loupé]';	
	}
	
	if($donneesvictimes['valid'] === 'none')
	{
		
	echo ' [Attente]';
	}
	echo '</li>';
}
echo '</ul>
<div style="text-align: right;"><a href ="index.php?pg=info&amp;voir=victime&pseudo='.$donnees['pseudo'].'">Voir tout</a></div>';
}
else
{
echo '<br/><br/>Personne !';
}
echo '</div>';
echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-top: 10px; margin-right: 19px; padding:2px; width: 270px; height: 120px; ">Il a shooté sur :';
$info = mysql_query('SELECT * FROM screen WHERE killeur = \''.$donnees['pseudo'].'\' ORDER BY time DESC LIMIT 5')or die(mysql_error());
if( mysql_num_rows($info))
{
	echo '<ul>';
while($donneeskilleur = mysql_fetch_array($info))
{
	echo '<li><a href="/index.php?pg=voirkill&pagekillid='.$donneeskilleur['ID'].'">'.$donneeskilleur['victime'].'</a>';
	if($donneeskilleur['valid'] === 'pas valide')
	{
	echo ' [Loupé]';	
	}
	
	if($donneeskilleur['valid'] === 'none')
	{
		
	echo ' [Attente]';
	}
	echo '</li>';
}
echo '</ul>
<div style="text-align: right;"><a href ="index.php?pg=info&amp;voir=meurtres&pseudo='.$donnees['pseudo'].'">Voir tout</a></div>';
}
else
{
echo '<br/><br/>Personne !';
}
echo '</div>';
echo '<div style="float:right; background-color: rgb(65, 65, 65); clear:right; margin-top: 10px; padding:2px; width: 270px; height: 120px; margin-bottom : 10px;">
Son trafic de pièces :';
$info = mysql_query('SELECT * FROM trafic WHERE ( id_donneur = \''.$donnees['id'].'\' OR id_reseveur = \''.$donnees['id'].'\' ) ORDER BY time DESC LIMIT 5')or die(mysql_error());
if( mysql_num_rows($info))
{
	echo '<ul>';
	while($donneestrafic = mysql_fetch_array($info))
	{
		echo '<li>';
		
		if( $donneestrafic['id_donneur'] === $donnees['id'] AND $donneestrafic['arbitre'] !== 'oui')
		{
			echo ' -'.$donneestrafic['somme'].'p, don à '.$donneestrafic['pseudo_reseveur'].'.';
		}
		elseif( $donneestrafic['id_reseveur'] === $donnees['id'] AND $donneestrafic['arbitre'] !== 'oui' )
		{
			
			if($donneestrafic['achat_magasin'] === 'oui')
			{
				echo $donneestrafic['somme'].'p, achat «'.$donneestrafic['motif'].'».';
			}
			else
			{
				echo ' +'.$donneestrafic['somme'].'p, par '.$donneestrafic['pseudo_donneur'].'.';
			}
		}
		elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_donneur'] !== $donnees['id'])
		{
			if($donneestrafic['somme'] > 0 )
			{
				echo ' +';
			}
			echo $donneestrafic['somme'].'p, par l\'arbitre : '.$donneestrafic['pseudo_donneur'];
		}
		elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_reseveur'] !== $donnees['id'])
		{
			echo 'Transfert d\'arbitre de '.$donneestrafic['somme'].'p.';
		}
		elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_reseveur'] === $donnees['id'])
		{
			echo 'Il s\'est créé '.$donneestrafic['somme'].'p.';
		}
		else
		{
			echo 'Erreur transfert ID '.$donneestrafic['id'].' Resev : '.$donneestrafic['id_reseveur'].' Donn : '.$donneestrafic['id_donneur'].' Acc : '.$donnees['id'];
		}
		echo '</li>';
	}
	echo '</ul>
	<div style="text-align: right;"><a href ="index.php?pg=info&amp;voir=traffic&pseudo='.$donnees['pseudo'].'">Voir tout</a></div>';
}
else
{
	echo '<br/><br/>Vide !';
}
echo '</div>';
echo '<div style="float:right; background-color: rgb(65, 65, 65); margin-top: 10px; margin-right: 19px; padding:2px; width: 270px; height: 120px;">Il est parrain de :';
$info = mysql_query('SELECT * FROM membres WHERE parrin = \''.$donnees['pseudo'].'\' ORDER BY datein DESC LIMIT 5')or die(mysql_error());
if( mysql_num_rows($info))
{
	echo '<ul>';
while($donneesparrain = mysql_fetch_array($info))
{
	echo '<li><a href="index.php?pg=fiches&habbo='.$donneesparrain['pseudo'].'">'.$donneesparrain['pseudo'].'</a></li>';
}
echo '<ul>
<div style="text-align: right;"><a href ="index.php?pg=info&amp;voir=parrain&pseudo='.$donnees['pseudo'].'">Voir tout</a></div>';
}
else
{
echo '<br/><br/>Personne.';
}
echo '</div>';


// les messages du membre
echo '<div style="border=1px solid #000000; width: 100%px; height: 450px;overflow: auto; clear: both;">';
if(isset($_POST['message']))
{
mysql_query('INSERT INTO message_fiche VALUES(\'\',
\''.$pseudo.'\',
\''.$donnees['pseudo'].'\',
\''.mysql_real_escape_string(htmlspecialchars($_POST['message'])).'\',
'.time().')') or die(mysql_error());
}

//mysql_real_escape_string(htmlspecialchars())
$info = mysql_query('SELECT * FROM message_fiche WHERE pseudo_fiche = \''.$donnees['pseudo'].'\' ORDER BY time DESC LIMIT 15')or die(mysql_error());
if(mysql_num_rows($info))
{
while($donneesmessage = mysql_fetch_array($info))
{
				
				/* Code source piqué à celuit des commantaires des news */
				
				if ($donnees['pseudo'] == $donneesmessage['pseudo'])
				{
				   $position = 'right';
				}
				else
				{
					// $i = pair
					$position = 'left';
				}
				
				$margin = $position == 'right' ? 'margin-right: 10px;' : '';
				$direction = $position == 'right' ? '4' : '2';

			
		
			// On affiche l'avatar
			echo '<img height="94px" width="68px" src ="img/avatarimage.gif"
			style = "float:'.$position.'; clear: both; display:inline margin-top: -10px; background-image:url(http://www.habbo.fr/habbo-imaging/avatarimage?user='.$donneesmessage['pseudo'].'&action=null&direction='.$direction.'&head_direction='.$direction.'&gesture=sml&size=b&img_format=gif); background-repeat: no-repeat; background-position: 0px -15px; margin-top: -6px;" />';
			
			// On crée le div et on affiche le message
			echo '<div style = "padding: 2px; background-color: rgb(65, 65, 65); width: 460px; min-height: 80px; margin-'.$position.':80px; margin-top: 10px; '.$margin.'">';
			echo nl2br(stripslashes(bbcode($donneesmessage['message'])));
			
			// Infos sur l'auteur et la date
			echo '<br /><br /><br /><br /><em>Posté par <a href="?pg=fiches&habbo='.stripslashes($donneesmessage['pseudo']).'">'.stripslashes($donneesmessage['pseudo']).'</a> le '.date('d/m/Y à H:i', $donneesmessage['time']).'</em>';
			
			// On ferme le div
			echo '</div>';
/* echo '<i>Par <a href="index.php?pg=fiches&habbo='.$donneesmessage['pseudo'].'">'.$donneesmessage['pseudo'].'</a> le '.date('d/m/Y à H:i',$donneesmessage['time']).'</i><br/><br/>
'.bbcode(nl2br(stripslashes($donneesmessage['message']))).'
<hr style="width: 90%; height: 2px;">'; */


}


}
else
{
echo '<i>Il n\'a pas de message...</i>';
}
?></div>

<form method="post"> 
<p> 
Nouveau message: 
 <textarea name="message" style="width: 100%; height: 40px;"></textarea>
<div style="text-align: right;">
<input type="submit" value="Envoyer" />
</form>
</div>
<?php
if( $donnees['pseudo'] === $pseudo )
{
?>
<div style = "clear:right;">
<br/><br/>
Modifie ton profil :
<form method="post" action="index.php?pg=textmod">
   <p>
       <label for="description">Ta description :</label><br />
       <textarea name="description" id="description" maxlength="200" rows="5" cols="50"><?php if($contenu == "Aucune"){ echo "Tape ta description ici..."; } else{ echo stripslashes($contenu);} ?></textarea>
   </p>
   <input type="submit" value="Envoyer" />
</form></center>
   		 <form action="index.php?pg=modifcode" method="post">
			<p>Change ton mot de passe <br/> Ton code actuel :  <input type="password" name="pass" /> <br/>
				Ton nouveau code  <input type="password" name="pass1" /> <br/>
				Encore, le nouveau : <input type="password" name="pass2" /> <br/>
			</p>
		   <input type="submit" value="Envoyer" />
		</form>
</div>
<?php
}
else
{
?>
<div style = "clear:right;">
<center>
<form method="get" action="index.php"><br/>
       Don de pièces : <input name="somme" type="text"><br/>
	   Un petit message avec ? <input name="msg" type="text">
	   <br/><br/>
	   <input name="pg" value="doncash" type="hidden">
	   <input name="id" value="<?php echo $donnees['id']; ?>" type="hidden">
	   
   <input value="Envoyer" type="submit">
</form>
</center>
</div>
<?php
}

if ( $grade === 'Arbitre' OR $grade == 'Admin' )
{
?>
<a href="index.php?pg=modifprofil&amp;habbo=<?php echo $donnees['pseudo']; ?>">Modifie son profil</a>
<?php
}
		
		

		}}
elseif ($_GET['pg'] == "modifprofil")
{
if($grade == "Arbitre" OR $grade == "Admin")
{
$habbo = $_GET['habbo'];
if($habbo !== '' )
{
echo "Tu veux modifier le profil de $habbo <br/>";
$habbo = mysql_real_escape_string($habbo);
$reponse = mysql_query("SELECT * FROM membres WHERE pseudo = '$habbo' ")or die(mysql_error());
if(mysql_num_rows($reponse) == 0)
{
echo "Tu veux modifier le pseudo d'un membre qui existe pas.";
}
else
{
while ($donnees = mysql_fetch_array($reponse))
{
?>
<form method="post" action="index.php?pg=textmod&amp;habbo=<?php echo $habbo; ?>">
   <p>
       <label for="ameliorer">Sa description :</label><br />
      <textarea name="description" id="description" maxlength="200" rows="5" cols="50"> <?php echo stripslashes($donnees['description']); ?></textarea>
   </p>
   <input type="submit" value="Envoyer" />
</form>

<form method="post" action="index.php?pg=pointsmod&amp;habbo=<?php echo $habbo; ?>">
 Points a rajouter, rentre un valeur négative pour en retirer : 
<input type="text" name="points" size="4" maxlength="4" /><br/>Quelle raison ? <input type="text" name="reson" /><input type="submit" value="Envoyer" />
</form>
<form method="post" action="index.php?pg=statumod&amp;habbo=<?php echo $habbo; ?>">
   <p>
       <label for="action">Sanctions.</label><br />
       <select name="action" id="action">
		   <option value="ban1">Bannir une semaine</option>
           <option value="ban2">Bannir un mois</option>
           <option value="ban3">Bannir trois mois</option>
           
		   <?php
		   if( $grade == "Admin")
		   {
		   ?>
		   <option value="sup">Supprimer</option>
		   <option value="ban4">Bannir définitivement</option>
           <option value="modo">En faire un arbitre</option>
           <option value="admin">En faire un admin</option>
		   <?php
		   if( $pseudo == $habbo OR $grade == "Admin" )
		   {
		   ?>
		   <option value="delgrade">Demisionner du post de <?php echo $donnees['statu']; ?></option>
		   <?php
		   }
		   }
		   ?>
       </select><br/>
	   Quel raisons ? <input type="text" name="reson" id="reson"/>
   </p>
      <input type="submit" value="Envoyer" />
</form>

<?php
}
}
}
}
}
elseif ($_GET['pg'] === 'pointsmod')
{
if ($grade === 'Admin' OR $grade === 'Arbitre')
{
if ( $_POST['points'] !== ''  AND $_POST['reson'] !== '')
{
if(is_numeric($_POST['points']) AND $_POST['points'] != 0)
{
$chifre = mysql_real_escape_string(htmlspecialchars($_POST['points']));
$ajou = mysql_real_escape_string(htmlspecialchars($_POST['points']));
$reson = mysql_real_escape_string(htmlspecialchars($_POST['reson']));
$habbo = mysql_real_escape_string(htmlspecialchars($_GET['habbo']));
mysql_query('UPDATE membres SET cash= cash + '.$ajou.' WHERE pseudo=\''.$habbo.'\'')or die(mysql_error());

$info = mysql_query('SELECT * FROM membres WHERE pseudo = \''.$habbo.'\' LIMIT 1')or die(mysql_error());
$donnees = mysql_fetch_array($info);

mysql_query('INSERT INTO trafic VALUES(\'\', \'oui\', \'non\', \''.$reson.'\', \''.$id.'\', \''.$donnees['id'].'\',
\''.$pseudo.'\', \''.$habbo.'\', \''.$ajou.'\', '.time().')') or die(mysql_error());


echo 'C\'est fait !';
}
}
else
{
echo 'Tu n\'as pas tapé un chiffre correct.';
}
}
else
{
echo 'Tout est obligatoire !';
}
}
elseif( $_GET['pg'] == "textmod" )
{
if ( $_POST['description'] != "" )
{
$habbo = $_GET['habbo'];
$texte = mysql_real_escape_string(htmlspecialchars($_POST['description']));
if($habbo == "")
{
mysql_query("UPDATE membres SET description='$texte' WHERE pseudo='$pseudo'");
echo "T'a description est modifiée.";
}
elseif($grade == "Admin" AND $habbo != "")
{
$habbo = mysql_real_escape_string($habbo);
mysql_query("UPDATE membres SET description='$texte' WHERE pseudo='$habbo'");
echo "C'est fait !";
}
elseif($grade == "Arbitre" AND $habbo != "")
{
$habbo = mysql_real_escape_string($habbo);
mysql_query("UPDATE membres SET description='$texte' WHERE pseudo='$habbo'");
echo "C'est fait !";
}
else
{
echo "Probléme interne";
}
}
else
{
echo "Tu ne peux pas laisser ce champ vide, désolé.";
}
}
elseif($_GET['pg'] == "modifcode")
{
if ( $_POST['pass'] != "" AND $_POST['pass1'] != "" AND $_POST['pass2'] != "")
{
if ( $_POST['pass1'] == $_POST['pass2'] )
{
$reponse = mysql_query("SELECT pass FROM membres WHERE pseudo ='$pseudo'") or die(mysql_error());
$code = mysql_real_escape_string(htmlspecialchars(md5(md5($_POST['pass']))));
$donnees = mysql_fetch_array($reponse);
if ( $donnees['pass'] == $code )
{
$code = mysql_real_escape_string(htmlspecialchars(md5(md5($_POST['pass2']))));
mysql_query("UPDATE membres SET pass='$code' WHERE pseudo='$pseudo'") or die(mysql_error("Tentative de hack"));

echo "Code modifié. <br /> Deconnecte toi !";
}
else
{
echo "C'est pas ton code !";
}
}
else
{
echo "Tu a tapé 2 nouveau codes différents !";
}
}
else
{
echo "Remplit TOUT les champs pour changer ton code...";
}
}
elseif($_GET['pg'] == "boutique")
{

?>
<center><a href ="index.php?pg=fiches&habbo=<?php echo $pseudo; ?>">Ton profil contient la liste de tes armes et la somme de tes pièces, clique ici.</a></center><br/>
Tu peux acheter les armes et bonus ici :<br/>
<br/><center>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Une balle</td>
      <td style="text-align: center;">5 Pi&egrave;ces</td>
      <td style="text-align: right;"><a href="index.php?pg=acheter&amp;item=balle">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">
      <p>Une balle te permet de tirer sur les autres killeurs.<br>
Si tu les touches, tu gagneras des pi&egrave;ces.<br>
Tu auras une balle qui sera retir&eacute;e pour chaque screen
envoyer.&nbsp; </p>
      </td>
    </tr>
  </tbody>
</table>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Six balles</td>
      <td style="text-align: center;">25 Pi&egrave;ces</td>
      <td style="text-align: right;"><a href="index.php?pg=acheter&amp;item=6balles">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Six balles pour
25 pi&egrave;ces au lieu de 30 !</td>
    </tr>
  </tbody>
</table>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Couteau</td>
      <td style="text-align: center;">10 Pi&egrave;ces</td>
      <td style="text-align: right;"><a
 href="index.php?pg=acheter&amp;item=couteau">Ach&egrave;te !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Un couteau te
permet de tuer discr&egrave;tement quelqu'un qui porte un gilet
pare-balle.<br>
Pour l'utiliser, il suffit de te placer derri&egrave;re ta victime
et de lui murmurer : *Couic*
    </tr>
  </tbody>
</table>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 108px;">Gilet pare-balle</td>
      <td style="text-align: center; width: 117px;">25
Pi&egrave;ces</td>
      <td style="text-align: right;"><a
 href="index.php?pg=acheter&amp;item=gilet">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">Le
Gilet pare-balle t'assure une protection contre les tirs, pendants 2
jours.<br>
Pratique... Mais fait attention aux porteur de couteaux ! </td>
    </tr>
  </tbody>
  </table>
<br/>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Visite à l'hopital</td>
      <td style="text-align: center;">30 Pi&egrave;ces</td>
      <td style="text-align: right;"><a
 href="index.php?pg=acheter&amp;item=tds">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">
 Si tu es mort,<br/>
une petite visite à l'hôpital ne fera pas de mal,<br/>
les docteurs ne posent pas de question ;).<br/>
Tu seras soigné et tu pourras de nouveau tuer. :D
	  </td>
    </tr>
  </tbody>
</table>
<br/>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Invisible</td>
      <td style="text-align: center;">20 Pi&egrave;ces</td>
      <td style="text-align: right;"><a
 href="index.php?pg=acheter&amp;item=invisible">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">
Pendant deux jours, tu n'apparaitras pas dans les listes qui sont sur la page « killeurs ».
	  </td>
    </tr>
  </tbody>
</table>
<br/>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Esquive</td>
      <td style="text-align: center;">10 Pi&egrave;ces</td>
      <td style="text-align: right;"><a
 href="index.php?pg=acheter&amp;item=esquive">Achete !</a><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1">
Ce bonus te donnes une chance sur trois d'esquiver ta prochaine mort valide.
	  </td>
    </tr>
  </tbody>
</table>
<br/>
<br/>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 159px;">Mise &agrave; prix</td>
      <td style="width: 159px; text-align: right;">10
Pi&egrave;ces
+ M.&Agrave;.P</td>
    </tr>
    <tr>
      <td style="width: 159px;" colspan="2" rowspan="1">Les
mises &agrave; prix
te permettent d'offrir de l'argent contre la mort d'un habbo...
<form method="post" action="index.php?pg=acheter&item=MAP">
       Victime : <input type="text" name="victime"/><br/>
	   Mise a prix ( en pièce ! ): <input type="text" name="somme" size="4"/>
  <input type="submit" value="Envoyer" />
</form>
</td>
    </tr> 
  </tbody>
</table> 
<br/>
<?php if($grade == "Admin" OR $grade == "Arbitre")
{
?>
<table style="text-align: left; width: 338px; height: 60px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 159px;">Envoye un message à un membre</td>
      <td style="width: 159px; text-align: right;">0 pièces</td>
    </tr>
    <tr>
      <td style="width: 159px;" colspan="2" rowspan="1">
	  Exclusivement pour la modération du site, tu peut envoyer un message directement a un membre :

<form method="post" action="index.php?pg=acheter&item=envoyerunmessageaunmembre">
       Membre : <input type="text" name="membre"/><br/>
	   Message : <textarea name="msg" id="msg" rows="7" cols="40"></textarea>
  <input type="submit" value="Envoyer" />
</form>
</td>
    </tr>
  </tbody>
</table>
<?php
}
?>
<br/><br/>
</center>
<?php
//if($pseudo != 'gaby' AND $_SERVER['REMOTE_ADDR']!= '82.227.77.61')
if(1===2)
{
//echo '<div style="text-align: right;"><a href="index.php?pg=propos_pay">En manque de pièces ?</a></div>';
}
?>
<?php
}
elseif($_GET['pg'] === 'acheter' AND $_GET['item'] !== '')
	{
	switch ($_GET['item']) { // on indique sur quelle variable on travaille

		case 'balle': // dans le cas où $note vaut 0
		if( $pieces >= 5 )
		{
		$somme = 5;
		$object = 'Une balle';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , balles=balles + 1 WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;

		case '6balles': // dans le cas où $note vaut 5
		if( $pieces >= 25 )
		{
		$somme = 25;
		$object = 'Six balles';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , balles=balles + 6 WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;

		case 'couteau':
		if( $pieces >= 10 )
		{
		$somme = 10;
		$object = 'Un couteau';
		mysql_query('UPDATE membres SET cash=cash-\''.$somme.'\' , couteau=couteau + 1 WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Il te manque des sous !';
		}
		break;

		case 'gilet':
		if( $pieces >= 25 )
		{
		if($gilet < time())
		{
		$somme = 25;
		$object = 'Gilet pare-balles';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , pareballes='.time().' + 3600*24*2  WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Tu as déjà ce bonus...';
		}
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;

		case 'invisible':
		if( $pieces >= 20 )
		{
		if($invisible < time())
		{
		$somme = 20;
		$object = 'Invisible';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , invisible='.time().' + 3600*24*2  WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Tu as déjà ce bonus...';
		}
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;
		
		case 'esquive':
		if( $pieces >= 10 )
		{
		if(!$esquive)
		{
		$somme = 10;
		$object = 'Esquive';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , esquive=33 WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'C\'est fait !';
		}
		else
		{
		echo 'Tu as déjà ce bonus...';
		}
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;

		case 'tds': // etc etc
		if( $pieces >= 30 )
		{
		if($pv == 0)
		{
		$somme = 30;
		$object = 'Hopital';
		mysql_query('UPDATE membres SET cash=cash-'.$somme.' , pv = 2  WHERE id=\''.$id.'\'') or die(mysql_error());
		echo 'Tu es soigné.';
		}
		else
		{
		echo 'Tu ne peux pas te soigner car tu n\'es pas mort...';
		$object = '';
		}
		}
		else
		{
		echo 'Il te manque des pièces !';
		}
		break;

		case "envoyerunmessageaunmembre": // etc etc
		if($grade == "Admin" OR $grade == "Arbitre" )
		{
		$mec = mysql_real_escape_string($_POST['membre']);
		$msg = mysql_real_escape_string($_POST['msg']);
		if( $mec != '' AND $msg != '')
		{
		mysql_query("INSERT INTO message VALUES('', '$mec', '$msg', '$pseudo', 'non', '".time()."')");
		echo "C'est OK ! :)";
		}
		else
		{
		echo "Entre toute les info s'il te plaît.";
		}
		}
		break;

		case "MAP": // etc etc
		if(is_numeric($_POST['somme']) AND $_POST['somme'] > 0)
		{
			$habbo = mysql_real_escape_string(htmlspecialchars($_POST['victime']));
			$query = "SELECT * FROM membres WHERE pseudo = '$habbo'";
			$result = mysql_query($query) or die ();
			$pv = mysql_fetch_array($result);
			if(mysql_num_rows($result) == 0 )
			{
				echo "Cet habbo ne joue pas à habboshoot...";
			}
			else
			{
				if( ($_POST['somme'] + 10 ) <= $pieces )
				{
					$object = 'Mise à prix de '.$habbo;
					$somme = mysql_real_escape_string(htmlspecialchars($_POST['somme']));
					mysql_query("INSERT INTO miseprix VALUES('', '$habbo', '$pseudo', '$somme', '".time()."', 'non')");
					$somme = $somme + 10;
					mysql_query("UPDATE membres SET cash=cash-$somme WHERE id='$id'") or die(mysql_error());
					echo "C'est fait !";
				}
				else
				{
					echo "Tu n'as pas assez de pièces...";
				}
			}
		}
		else
		{
			echo "Il faut entrer le nombre de piéces que tu donnera au killer qui tuera ta victime.";
		}
		break;

		default:
		echo "Désolé, je n'ai pas l'item corespondant...<br/>Tu touches probablement trop à la barre d'adresse ! :p";

	}
	if(!empty($object))
	{
		mysql_query('INSERT INTO trafic VALUES(\'\',
		\'non\',
		\'oui\',
		\''.$object.'\',
		0,
		\''.$id.'\',
		\'\',
		\''.$pseudo.'\',
		\'-'.$somme.'\',
		\''.time().'\')') or die(mysql_error());
	}


}
elseif($_GET['pg'] == "formulaire" AND $_GET['habbo'] != "" AND $pv == 2 AND $motkill != "")
{
	include('config.php');
?>
<h1>Rapport  d'assassinat.</h1>
Pour prouver que tu as tué <?php echo htmlspecialchars($_GET['habbo']) ?>, tu dois nous montrer le screen que tu as pris, <span style="text-decoration: underline;">au format png</span>, pour qu'il soit vérifié par un arbitre.<br/><br/>
<form method="post" action="index.php?pg=screenpost&amp;habbo=<?php echo urlencode($_GET['habbo']); ?>" enctype="multipart/form-data">
<div id="zonescreenpost">Screen :
<input name="fichier" id="fichier" size="30" type="file" /><br /></div>
<input name="fichier_dropdrag" id="fichier_dropdrag" type="hidden" /><br />
<input type="checkbox" name="couteau" id="couteau"/> Meurtre avec couteau ! ( Tu as murmuré *Couic* pour le tuer ) <br/>
<input type="checkbox" name="cagoule" id="cagoule"/> Meurtre ou tu portais une cagoule. +3 pièces.<br/>
<input type="submit" value="Envoyer" style="float:right; margin:1em;"/>
</form>
<br/><br/><i>Astuce : Avec un navigateur web récent, il est possible de glisser-poser ta screen depuis ton bureau jusqu'à cette page.</i>
<div style="width:90%; margin-left:5%; margin-top: 2em;" id="preview-screen"></div>
<script>
    var dropper = document.querySelector('html');

    dropper.addEventListener('dragover', function(e) {
        e.preventDefault(); // Annule l'interdiction de "drop"
    }, false);

    dropper.addEventListener('drop', function(e) {
        e.preventDefault();

        var files = e.dataTransfer.files;
        var file = files[0];
		
		var imageType = /image\/png/;
		if (!file.type.match(imageType)) {
			alert('Ce n\'est pas une image PNG');
			return false;
		}
		
		if(file.size >= <?php echo $poids_max; ?>){
			alert('Le fichier est trop lourd.');
			return false;
		}
		
		
		document.getElementById('zonescreenpost').style.display = 'none';
		
		document.getElementById('preview-screen').innerHTML = '';
		
        var img = document.createElement("img");
		img.file = file;
		img.id = 'img_screen';
		img.style.width = '100%';
		document.getElementById('preview-screen').appendChild(img);

		var reader = new FileReader();
		reader.onloadend = (function(aImg, aImg2) { return function(e) { aImg.src = e.target.result;  aImg2.value = e.target.result;}})(img, fichier_dropdrag);
		
		reader.readAsDataURL(file);
		
		
    }, false);
</script>
<?php
}
elseif($_GET['pg']=="screenpost"){
	require_once('config.php');
	$habbo = mysql_real_escape_string(htmlspecialchars($_GET['habbo']));
	$result = mysql_query("SELECT * FROM membres WHERE pseudo = '$habbo' LIMIT 1") or die (mysql_error());
	$donneespertials = mysql_fetch_array($result);
	if($donneespertials['pareballes'] >= time()){
		$parebalenclancher = 'Oui';
	}
	else{
		$parebalenclancher = 'Non';
	}
	if($_FILES['fichier']['size']<=$poids_max){
		if($pv == 2 AND $motkill != ""){
			$result = mysql_query("SELECT * FROM membres WHERE pseudo = '$habbo' LIMIT 1") or die (mysql_error());
			$pv = mysql_fetch_array($result);
			if(mysql_num_rows($result) == 0 OR $pv['pv'] != 2 OR $pv['dateco'] + ( 3600*24*14) <= time())
			{
			echo "Cet habbo ne joue pas à habboshoot...";
			}
			else{
				$niveauVictime= getlvl($pv['meutre']);
				$niveauTueur = getlvl($meurtre);
				
				
				// 8 + ou moin la differance de niveau si le killer à plus de kill
				// 7 - 1 à chaque 2 niveau de differance si le  killer a moin de kill
				if($niveauTueur > $niveauVictime){
					$somme = 7 - (($niveauTueur-$niveauVictime)/2);
					$somme = ceil($somme);
				}
				elseif($niveauTueur < $niveauVictime){
					$somme = 8 + ($niveauVictime-$niveauTueur);
					$somme = ceil($somme);
				}
				else{
					$somme = 8;
				}
				// il faut rajouter à la somme quelquechose s'il y a une mise a prix sur la victime
				$result = mysql_query("SELECT * FROM miseprix WHERE tete = '$habbo' AND zigouiller = 'non'") or die ();
				if(mysql_num_rows($result) > 0 ){
					while($killer = mysql_fetch_array($result)){
						$somme = $somme + $killer['prix'];
					}
				}
				
				$manqueUneRessource = false;
				$BDDcouteaux = 'non';
				if(!isset($_POST['couteau'])){
					if($balles < 1){
						$manqueUneRessource = 'balle';
					}
				}
				else{
					$BDDcouteaux = 'oui';
					$motkill = '*Couic*';
					if($couteau < 1){
						$manqueUneRessource = 'couteau';
					}
				}
				
				if($manqueUneRessource !== false){
					echo 'Il te manque un(e) '.$manqueUneRessource.' ! Direction boutique !';
				}
				else{
					$name_file = sha1(microtime()+mt_rand(100, 1000000));
					
					if($_POST['fichier_dropdrag'] !== ''){  
						$parts = explode(',', $_POST['fichier_dropdrag']);  
						$data = $parts[1];  
						$databin = base64_decode($data);  
						$nom = 'screens/'.$name_file.'.png';
						 file_put_contents($nom, $databin);  
						 if( preg_match( '![^a-zA-Z0-9/+=]!', $data )){
							 $erreur = 'Ton navigateur n\'arrive pas à gérer correctement l\'envois de la screen par drop-drag.';
						 }
					}
					else{
						$name_fichier=$_FILES['fichier']['name'];
						$extension_upload=substr(strrchr($_FILES['fichier']['name'], '.')  ,1);
						if(in_array($extension_upload,$extensions_autorisees)){
							$nom=getName('screens/',$name_file,$extension_upload);
							move_uploaded_file($_FILES['fichier']['tmp_name'],$nom);
						}
						else{
							echo 'L\'extension du screen n\'est pas autorisé';
						}
					}
					if($erreur){
						echo $erreur;
					}
					else{
						$screen = mysql_real_escape_string(htmlspecialchars($nom));
						if(!isset($_POST['couteau'])){
							mysql_query("UPDATE membres SET balles=balles - 1 WHERE id='$id'") or die(mysql_error());
						}
						else{
							mysql_query("UPDATE membres SET couteau=couteau - 1 WHERE id='$id'") or die(mysql_error());
						}
						if( $pv['ip'] === $ip AND !( $grade === 'Admin' OR $pv['statu'] === 'Admin')){
							echo 'T\'essayes de tuer ton clone ! T\'es ban 2 semaines.';
							mysql_query("INSERT INTO ban VALUES('$pseudo', '".(time()+(3600*24*14))."', 'Essaye de tuer son clone.', '$ip')")or die(mysql_error());
							mysql_query("UPDATE membres SET pv = 3 WHERE pseudo='$habbo'") or die(mysql_error());
						}
						else{
							mysql_query("UPDATE membres SET pv = 1 WHERE pseudo='$habbo'") or die(mysql_error());
							$screen = mysql_real_escape_string(htmlspecialchars($nom));
							mysql_query("INSERT INTO screen_post VALUES('$nom', '".time()."')")or die(mysql_error());
							$duel = 'non';
							if ($_POST['cagoule'] == "on"){
								$somme=$somme+1;
								mysql_query("INSERT INTO screen VALUES('', '$screen', '$pseudo', '$habbo', '".time()."', 'none', '$BDDcouteaux', '$somme', 'oui', '$duel', '$motkill' ,'', '$parebalenclancher', '".$pv['esquive']."', 'a:2:{s:6:\"pseudo\";a:0:{}s:2:\"ip\";a:0:{}}', 'a:2:{s:6:\"pseudo\";a:0:{}s:2:\"ip\";a:0:{}} ')")or die(mysql_error());
							}
							else{
								mysql_query("INSERT INTO screen VALUES('', '$screen', '$pseudo', '$habbo', '".time()."', 'none', '$BDDcouteaux', '$somme', 'non' , '$duel', '$motkill' ,'', '$parebalenclancher', '".$pv['esquive']."', 'a:2:{s:6:\"pseudo\";a:0:{}s:2:\"ip\";a:0:{}} ', 'a:2:{s:6:\"pseudo\";a:0:{}s:2:\"ip\";a:0:{}} ')")or die(mysql_error());
							}
							$idkill = mysql_insert_id();
							mysql_query("UPDATE miseprix SET zigouiller = '".$idkill."' WHERE tete='".$habbo."' AND zigouiller='non'") or die(mysql_error());
							echo 'Fait !<br/><a href="index.php?pg=voirkill&pagekillid='.$idkill.'">Voir le kill</a>';
						}
					}
						
				}
			}
		}
		else{
			echo "Tu ne peux pas tuer car t'es mort...";
		}
	}
	else{
		echo "La screen est trop grosse pour être téléchargée.";
	}
}
elseif($_GET['pg'] === 'assassinat')
{

tableau_kill('*', '*', 25);

$retour = mysql_query("SELECT COUNT(*) AS nbre_entrees FROM screen");
$donnees = mysql_fetch_array($retour);
$retour2 = mysql_query("SELECT COUNT(*) AS nbre_entrees FROM screen WHERE valid = 'valide'");
$donnees2 = mysql_fetch_array($retour2);
?>
<br/><br/>
<center><br/>
Nous avons eu <?php echo $donnees['nbre_entrees']; ?> tentatives de kills, <?php echo $donnees2['nbre_entrees']; ?> d'entre-elles sont valides.<br/><br/>
<a href ="index.php?pg=voirkill&obtion=hasard"> Un kill au hasard ? </a>
</center>
<br/>
<table style="text-align: left; width: 450px;" border="0"
 cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 215px;" align="undefined"
 valign="undefined">Les meilleurs killeurs :
 <ol>
 <?php
 $result = mysql_query("SELECT * FROM membres ORDER BY meutre DESC LIMIT 0,9");
while($donnees = mysql_fetch_array($result))
{
?>
 <li><a href = "index.php?pg=fiches&habbo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a> avec <?php echo $donnees['meutre']; ?> kills</li>
<?php }
 ?>
</ol>
 </td>
      <td style="width: 215px;" align="undefined"
 valign="undefined">Cette partie :
 <ol>
 <?php
 $result = mysql_query("SELECT * FROM membres ORDER BY meutreparty DESC LIMIT 0,9");
while($donnees = mysql_fetch_array($result))
{
?>
 <li><a href = "index.php?pg=fiches&habbo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a> avec <?php echo $donnees['meutreparty']; ?> kills</li>
<?php }
 ?>
</ol></td>
    </tr>
      <td style="width: 215px;" align="undefined"
 valign="undefined">Les plus riches :
 <ol>
 <?php
 $result = mysql_query("SELECT * FROM membres ORDER BY cash DESC LIMIT 0,9");
while($donnees = mysql_fetch_array($result))
{
?>
 <li><a href = "index.php?pg=fiches&habbo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a> avec <?php echo $donnees['cash']; ?> pièces</li>
<?php }
 ?>
</ol></td><td>
Les meilleurs arbitres :<ol>
 <?php

$array = array();
$result = mysql_query("SELECT * FROM membres WHERE statu = 'Arbitre' OR statu = 'Admin'");
while($donnees = mysql_fetch_array($result))
{
$retour = mysql_query("SELECT COUNT(*) AS nbr_screen FROM screen WHERE valideur = '".$donnees['pseudo']."'")or die(mysql_error());
$pseudooo = $donnees['pseudo'];
$donnees = mysql_fetch_array($retour);
$array[$pseudooo] = $donnees['nbr_screen'];
}
arsort($array);
foreach ($array as $key => $val) {
    echo '<li><a href ="index.php?pg=fiches&habbo='.$key.'">'.$key.'</a> avec '.$val.' kills validé</li>';
}
?>
</ol>
<?php

?>
</td>
<tr><td>Les meilleurs kills :
<ol>
<?php
 $result = mysql_query("SELECT * FROM screen WHERE valid = 'valide' ORDER BY gain DESC LIMIT 0,9");
while($donnees = mysql_fetch_array($result))
{
?>
 <li><a href = "index.php?pg=voirkill&pagekillid=<?php echo $donnees['ID']; ?>"><?php echo $donnees['killeur']; ?> VS <?php echo $donnees['victime']; ?> avec <?php echo $donnees['gain']; ?> pièces gagnées.</a></li>
<?php } ?>
</ol>
</td></tr>
  </tbody>
</table>
<?php
$result = mysql_query("SELECT *,  SUM(prix) as SOMPRIX FROM miseprix WHERE acheteur = '$pseudo' GROUP BY zigouiller, tete ORDER BY time DESC LIMIT 15")or die(mysql_error());
if(mysql_num_rows($result) > 0)
{
	echo 'Rapport des mises à prix :';
	echo "<ul>";
	while($donnees = mysql_fetch_array($result)){
		echo '<li>La mise à prix de '.$donnees['SOMPRIX'].' pièces pour '.$donnees['tete'];
		if($donnees['zigouiller'] !== 'non')
		{
			$result = mysql_query("SELECT * FROM screen WHERE ID = '".$donnees['zigouiller']."' LIMIT 1")or die(mysql_error());
			$data =mysql_fetch_array($result);
			if($data['valid'] === 'valide'){
				echo ', il est mort.';
			}
			else{
				echo ', il a été attaqué, le kill est en attente de validation.';
			}
		}
		else
		{
			echo ', il n\'est pas encore mort.';
		}
	}
	echo "</ul>";
}




}
elseif($_GET['pg'] === 'voirkill' )
{
if(!isset($_GET['valid']))
{
$pagekillid = mysql_real_escape_string(htmlspecialchars($_GET['pagekillid']));
if($_GET['obtion'] === 'hasard')
{

$result = mysql_query('SELECT * FROM screen ORDER BY RAND() LIMIT 1');
$donnees = mysql_fetch_array($result);
}
else
{
$result = mysql_query('SELECT * FROM screen WHERE ID = \''.$pagekillid.'\'');
$donnees = mysql_fetch_array($result);
}
$pagekillid = $donnees['ID'];
$valide = $donnees['valid'];
$info_killer = mysql_query("SELECT * FROM membres WHERE pseudo = '".$donnees['killeur']."' ");
$info_killer = mysql_fetch_array($info_killer);
$info_victime = mysql_query("SELECT * FROM membres WHERE pseudo = '".$donnees['victime']."' ");
$info_victime = mysql_fetch_array($info_victime);

$nomdukiller = $donnees['killeur'];
$nomdelavictime = $donnees['victime'];
?>
 <div style="text-align: right; font-style: italic;"><?php echo 'Le '.date('d/m/Y', $donnees['time']).' à '.date('H:i', $donnees['time']); ?></div><br/>
<table style="width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td align="undefined" valign="undefined"><div style="text-align: left;"><img border="0" src="http://www.habbo.fr/habbo-imaging/avatarimage?user=<?php echo $donnees['killeur'];
?>&action=std&frame=3&direction=2&head_direction=2&gesture=agr&size=b&img_format=gif"/><br/><a href ="index.php?pg=fiches&habbo=<?php echo $donnees['killeur']; ?>"><?php echo $donnees['killeur']; ?></a><br/><?php if( $grade === 'Admin' OR $grade === 'Arbitre'){  echo 'IP: '.$info_killer['ip']; } ?>
<br/><br/>
<?php
if($valide === 'none' && !($grade === 'Arbitre' OR $grade == 'Admin') && $pseudo != $nomdukiller AND $pseudo != $nomdelavictime AND $meurtre >= 5){
	echo '<a href = "index.php?pg=voirkill&amp;valid=oui&amp;pagekillid='.$pagekillid.'&amp;option=vote">VOTER POUR</a><br/>';
}
?>
Ont voté <u>pour</u> :
<?php

$votes_pour = unserialize($donnees['votes_pour']);
if(count($votes_pour['pseudo']) > 0)
{
	echo '<ol>';
	foreach($votes_pour['pseudo'] as $case => $valeur)
	{
		echo '<li>'.$case.'</li>';
	}
	echo '</ol>';
}
else
{
	echo '<br/>Personne.';
}
?>
</div>
  
	  </td>
      <td align="undefined" valign="undefined"><center> A TIR&Eacute; SUR <br/><br/>
</center>
<br/><?php if($donnees['couteau'] == "oui"){?><small>Meurtre avec couteau ! <br/>
Le killeur doit avoir murmuré *Couic* </small><br/><br/><?php }
if($donnees['cagoule'] == "oui"){?><small>Bonus cagoule.<br/>Le tueur doit porter une cagoule.</small><br/><br/><?php
}
echo '<center>Le kill ';
if($donnees['valid'] == "valide")
{
echo 'a été validé par ';
if($donnees['valideur'] != '')
{
echo $donnees['valideur'].'.';
}
else
{echo 'un arbitre.';}
}
elseif($donnees['valid'] == "none")
{
echo 'est en attente de validation.';
}
elseif($donnees['valid'] == "pas valide")
{
echo "a été refusé par ";

if($donnees['valideur'] != '')
{
echo $donnees['valideur'].'.';
}
else
{echo 'un arbitre.';}
}
echo '<br/><br/>Mot kill : ';
if($donnees['motkill']!= '')
{
echo $donnees['motkill'].'.';
}else{
echo 'Inconnu.';
}
if($donnees['giletparballe'] === 'Oui')
{
echo '<br/><br/>La victime porte un gilet pare-balles.';
}
echo '<br/><br/>Les deux KeyTimes valides sont : <br/><u>'.getKeyTime($info_killer['id'], $donnees['time']).'</u> ou <u>'.getKeyTime($info_killer['id'], $donnees['time']-3600).'</u>';
?>

</center><br/></td>
      <td align="undefined" valign="undefined"><div style="text-align: right;"><img border="0" src="http://www.habbo.fr/habbo-imaging/avatarimage?user=<?php echo $donnees['victime']; ?>&frame=3&direction=4&head_direction=4&gesture=agr&size=b&img_format=gif"/><br/><a href ="index.php?pg=fiches&habbo=<?php echo $donnees['victime']; ?>"><?php echo $donnees['victime']; $nomdemlavictimepourplusbas =  $donnees['victime']; ?></a><br/>

<?php if( $grade === 'Admin' OR $grade === 'Arbitre'){ echo 'IP: '.$info_victime['ip'];} ?>
<br/><br/>
<?php
if($valide === 'none' && !($grade === 'Arbitre' OR $grade == 'Admin') && $pseudo != $nomdukiller AND $pseudo != $nomdelavictime AND $meurtre >= 5){
	echo '<a href = "index.php?pg=voirkill&amp;valid=non&amp;pagekillid='.$pagekillid.'&amp;option=vote">VOTER CONTRE</a><br/>';
}
?>
Ont voté <u>contre</u> :<br/>
<?php

$votes_contre = unserialize($donnees['votes_contre']);
if(count($votes_contre['pseudo']) > 0)
{
	echo '<ol>';
	foreach($votes_contre['pseudo'] as $case => $valeur)
	{
		echo '<li>'.$case.'</li>';
	}
	echo '</ol>';
}
else
{
	echo 'Personne.';
}
?>
	  </div></td>
    </tr>
  </tbody>
</table>
<table style="width: 100%;" border="0"
 cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td align="undefined" valign="undefined"><div style="text-align: left;">
	  <a href="<?php echo $donnees['adresse']; ?>" target="_blank"><img src="<?php echo $donnees['adresse']; ?>" border= "0" style="max-width: 560px;"/></a></center><br/>

	  </div>
 
	  </td>
 	  </div>
    </tr>
  </tbody>
</table>

<center><br/>Contestation(s) :<br/>
<?php
$result = mysql_query("SELECT * FROM contest WHERE ID_reponce = '$pagekillid' ORDER BY id");
while($donnees = mysql_fetch_array($result))
{
?>
Par : <?php echo stripslashes($donnees['auteur']); ?><br/>
<em>
<?php echo bbcode(nl2br(stripslashes($donnees['msg']))); ?><br/>
</em>
_____________________________<br/>
<?php
}
?>
<form method="post" action="index.php?pg=contest&amp;id=<?php echo $pagekillid; ?>">
   <p>
<br />
       <textarea name="msg" cols="40">Si tu as une contestation, tu la taperas ici.</textarea><br/>
<input type="submit" value="Envoyer" />

</center>
</form>

<?php
if($grade === 'Arbitre' OR $grade == 'Admin'){
	if($valide == 'none'){
		echo '<br/><br/><br/><center>Arbitre :<br/><h2><a href="index.php?pg=voirkill&amp;valid=oui&amp;pagekillid='.$pagekillid.'">VALIDE</a> - <a href="index.php?pg=voirkill&amp;valid=non&amp;pagekillid='.$pagekillid.'">INVALIDE</a></center>';
	}
	else
	{
		echo '<br/><br/><br/><center><a href="index.php?pg=voirkill&amp;valid=annule&amp;pagekillid='.$pagekillid.'">Inverse le jugement !</a></center>';
	}
}
elseif( $pseudo == $nomdukiller AND $meurtre >= 5 AND $valide == 'none'){
	echo '<br/><br/><br/><center><a href="index.php?pg=voirkill&amp;valid=non&amp;pagekillid='.$pagekillid.'">Je change d\'avis : le kill est n\'est pas valide.</a></center>';
}
elseif($pseudo == $nomdelavictime AND $meurtre >= 5 AND $valide == 'none' ){
	echo '<br/><br/><br/><center><a href="index.php?pg=voirkill&amp;valid=oui&amp;pagekillid='.$pagekillid.'">J\'accepte de mourir : le kill est valide.</a></center>';
}
}
elseif($_GET['valid'] !== '' AND is_numeric($_GET['pagekillid']) AND $_GET['pg'] === 'voirkill' AND $_GET['option'] === 'vote')
{
/* Donc le mec vote, à ce moment, on met à jour : votes_pour ou votes_contre, s'il y a + de 5 voix, on accepte ou on refuse le kill.
 * Faut pas oublier de mettre à jour les votes de la table membre et la table des kills
 * */
	
	/* On regarde si son kill existe*/
	
	//mysql_real_escape_string(htmlspecialchars())
	$info = mysql_query('SELECT * FROM screen WHERE ID = \''.mysql_real_escape_string(htmlspecialchars($_GET['pagekillid'])).'\' LIMIT 1')or die(mysql_error());
	if( mysql_num_rows($info))
	{
	$donnees = mysql_fetch_array($info);
	
	/* Donc c'est valide,  le modéle de l'array des votes pour / contre :
	 * 					 $votes_pour['pseudo']['dededede4'] = 'oui';
	 */
	
	$votes_pour = unserialize($donnees['votes_pour']);
	$votes_contre = unserialize($donnees['votes_contre']);
	
	/* On regarde s'il a déjà voté */
	

	
	if(!isset($votes_pour['pseudo'][$pseudo])
	AND !isset($votes_contre['pseudo'][$pseudo])
	AND !isset($votes_pour['ip'][$ip])
	AND !isset($votes_contre['ip'][$ip]))
	{
		
		/* Pour voter, il lui faut 5 kills ou plus. */
		
		if( $meurtre >= 5 )
		{
			

				/* Tout à l'air bon, on rajoute aux compteurs son vote : */
			
				if($_GET['valid'] === 'oui' )
				{
					$votes_pour['pseudo'][$pseudo] = 'oui';
					$votes_pour['ip'][$ip] = 'oui';
					/*
					echo '<pre>';
					print_r($votes_pour);
					echo '</pre>'; */
					
					$votes_pour = serialize($votes_pour);
					
					mysql_query('UPDATE screen SET votes_pour = \''.$votes_pour.'\' WHERE ID=\''.mysql_real_escape_string(htmlspecialchars($_GET['pagekillid'])).'\'')or die(mysql_error());
					
					$vote = unserialize($votes);
					$vote[mysql_real_escape_string(htmlspecialchars($_GET['pagekillid']))] = 'oui';
					$votes = serialize($votes);

					mysql_query('UPDATE membres SET votes = \''.$votes.'\' WHERE id=\''.$id.'\'')or die(mysql_error());
					
						$votes_pour = unserialize($votes_pour);			
					
										
				}
				elseif($_GET['valid'] === 'non' )
				{
					$votes_contre['pseudo'][$pseudo] = 'oui';
					$votes_contre['ip'][$ip] = 'oui';
					
					$votes_contre = serialize($votes_contre);
					
					mysql_query('UPDATE screen SET votes_contre = \''.$votes_contre.'\' WHERE ID=\''.mysql_real_escape_string(htmlspecialchars($_GET['pagekillid'])).'\'')or die(mysql_error());
					
					$vote = unserialize($votes);
					$vote[mysql_real_escape_string(htmlspecialchars($_GET['pagekillid']))] = 'oui';
					$votes = serialize($votes);

					mysql_query('UPDATE membres SET votes = \''.$votes.'\' WHERE id=\''.$id.'\'')or die(mysql_error());
					
					$votes_contre = unserialize($votes_contre);	
						
				}
				else
				{
					echo 'URL non reconnue.';
				}
				
				/* Si le vote est fini ( 4 votes pour ou contre )*/
				
			if(count($votes_pour['pseudo']) === 4)
			{
				
				/* Le kill est valide */
				
			$retour = arbitre_kill($_GET['pagekillid'] , 'oui', 'La communauté'); /* jujement doit être égale à oui ou non ou annuler. retourn1 = Okais. 2 = Kill déjà validé 0 = Erreur critique.*/
					
			
			}
			elseif(count($votes_contre['pseudo']) === 4)
			{
				$retour = arbitre_kill($_GET['pagekillid'] , 'non', 'La communauté');
			}
			
			echo 'Merci d\'avoir voté !';
		}
		else
		{
			echo 'Il faut que tu aies au moins 5 kills à ton actif, pour voter.';
		}		
		
		/* On utilise $retour pour dire ce que sont vote est devenu */
		
		if($retour == 1)
		{
			echo 'Merci d\'avoir voté !';
		}
		elseif($retour == 2)
		{
			echo 'Pendant que tu votais, le kill a été validé (ou refusé).';
		}
	}
	else
	{
		echo 'T\'as déjà voté !';
	} 
	
	
	}
	else
	{
	echo 'Le kill n\'existe pas.';
	}
	
	
	
	

}
elseif($_GET['valid'] == "oui" AND $_GET['pagekillid'] != "" AND $_GET['pg'] == "voirkill"){
	$pagekillid = mysql_real_escape_string(htmlspecialchars($_GET['pagekillid']));
	$result = mysql_query('SELECT * FROM screen WHERE ID = \''.$pagekillid.'\' limit 1');
	$donnees = mysql_fetch_array($result);
	
	if($grade === 'Arbitre' OR $grade === 'Admin' OR ($donnees['victime'] === $pseudo AND $meurtre >= 5)){	
		if(arbitre_kill($_GET['pagekillid'] , 'oui', $pseudo) == 1)
		{
			echo 'Fait ! Merci :)<br/><a href="index.php?pg=assassinat">Retour</a>';
		}
		else
		{
			echo 'Ce kill a déja été validé !';
		}
	}
}

elseif($_GET['valid'] == "non" AND $_GET['pagekillid'] != "" AND $_GET['pg'] == "voirkill")
{
	$pagekillid = mysql_real_escape_string(htmlspecialchars($_GET['pagekillid']));
	$result = mysql_query('SELECT * FROM screen WHERE ID = \''.$pagekillid.'\' limit 1');
	$donnees = mysql_fetch_array($result);
	
	if($grade === 'Arbitre' OR $grade === 'Admin' OR ($donnees['killeur'] === $pseudo AND $meurtre >= 5))
	{
	
		if(arbitre_kill($_GET['pagekillid'] , 'non', $pseudo) == 1)
		{
			echo 'Fait ! Merci :)<br/><a href="index.php?pg=assassinat">Retour</a>';
		}
		else
		{
			echo 'Ce kill a déja été validé !';
		}
	}
}
  
elseif($_GET['valid'] == "annule" AND $_GET['pagekillid'] != "" AND $_GET['pg'] == "voirkill")
{
	if($grade === 'Arbitre' OR $grade === 'Admin')
	{
	
		if(arbitre_kill($_GET['pagekillid'] , 'annuler', $pseudo) == 1)
		{
			echo 'Fait ! Merci :)<br/><a href="index.php?pg=assassinat">Retour</a>';
		}
		else
		{
			echo 'Ce kill a déja été validé !';
		}
	}
}


}
elseif ($_GET['pg'] == "statumod")
{
	if($grade == "Admin" OR $grade == "Arbitre")
	{
		$habbo = $_GET['habbo'];
		if ( $_POST['action'] != "" AND $habbo != "" AND $_POST['reson'] != "")
		{
			$action = $_POST['action'];
			$habbo = mysql_real_escape_string($habbo);
			$reson = mysql_real_escape_string(htmlspecialchars($_POST['reson']));
			$result = mysql_query("SELECT * FROM membres WHERE pseudo = '".$habbo."' ");
			$donnees = mysql_fetch_array($result);
			switch ($action) 
			{ // on indique sur quelle variable on travaille
			
			case "ban1": // dans le cas où $note vaut 5
			$time = time() + (3600*24*7);
			mysql_query("INSERT INTO ban VALUES('$habbo', '$time', '$reson', '".$donnees['ip']."')") or die(mysql_error());
			mysql_query("UPDATE membres SET pv = '3' WHERE ip ='".$donnees['ip']."'") or die(mysql_error());
			echo "0n l'a banni 1 semaine";
			break;
			
			case "ban2": // dans le cas où $note vaut 7
			$time = time() + (3600*24*31);
			mysql_query("INSERT INTO ban VALUES('$habbo', '$time', '$reson', '".$donnees['ip']."')") or die(mysql_error());
			mysql_query("UPDATE membres SET pv = '3' WHERE ip ='".$donnees['ip']."'") or die(mysql_error());
			echo "On l'a banni 1 mois";
			break;
			
			case "ban3": // etc etc
			
			$time = time() + (3600*24*31*3);
			mysql_query("INSERT INTO ban VALUES('$habbo', '$time', '$reson', '".$donnees['ip']."')") or die(mysql_error());
			mysql_query("UPDATE membres SET pv = '3' WHERE ip ='".$donnees['ip']."'") or die(mysql_error());
			echo "On l'a banni 3 mois";
			break;
			
			case "ban4":
			$time = "deff";
			mysql_query("INSERT INTO ban VALUES('$habbo', '$time', '$reson', '".$donnees['ip']."')") or die(mysql_error());
			mysql_query("UPDATE membres SET pv = '3' WHERE ip ='".$donnees['ip']."'") or die(mysql_error());
			echo "On l'a banni definitivement";
			break;
			
			case "sup":
			mysql_query("DELETE FROM membres WHERE pseudo='$habbo'") or die(mysql_error());
			echo "On a suprimé son compte";
			break;
			
			case "modo":
			mysql_query("UPDATE membres SET statu='Arbitre' WHERE pseudo='$habbo'") or die(mysql_error());
			echo "C'est un modo, <br/>envois lui un message ;p";
			break;
			
			case "admin":
			mysql_query("UPDATE membres SET statu='Admin' WHERE pseudo='$habbo'") or die(mysql_error());
			echo "C'est un admin, <br/>envois lui un message ;p";
			break;
			
			case "delgrade":
			mysql_query("UPDATE membres SET statu='Membre' WHERE pseudo='$habbo'") or die(mysql_error());
			echo "C'est plus qu'un membre normal, <br/> Tiens-le au courant de la modification.";
			break;
			
			default:
			echo "";
			}
		}
		else
		{
		echo "Hien ? <br/> Il faux choisir une action !";
		}
	}
}
elseif($_GET['pg'] === 'Admin')
{
	if($grade == 'Admin' OR $grade == 'Arbitre')
	{
		if(!isset($_POST['msg']) AND !isset($_POST['msg_new']))
		{
			?>
			<br/>
			<form method="post" action="index.php?pg=Admin">
			<p>
			<label for="ameliorer">Message :</label><br />
			<textarea name="msg" id="ameliorer" style="width : 200px; height : 150px;"><?php echo $messagedelanonce; ?></textarea>
			<input type="submit" value="Envoyer" />
			</p>
			</form><br/>
			<?php
			
			if($motkill == '')
			{
				?>
				<!-- <a href ="index.php?pg=reztlm">Ressuscite tout le monde et remetre les meutre à 0</a><br/><br/> -->
				<?php
			}
			
			?>			
			
			<!-- <a href ="index.php?pg=banfocompte">Bannir les gens qui on trop plein de comptes.</a> --->
			
			<?php
			if($grade === 'Admin')
			{
				echo '<form method="post" action="index.php?pg=Admin">
				<p>
				Titre : <input type="text" name="titre" style="width : 99%"/><br />
				<textarea name="msg_new" style="width : 99%; height : 300px;"></textarea>
				<input type="submit" value="Envoyer" />
				</p>
				</form>';
			}
		}
		elseif(isset($_POST['titre']) AND isset($_POST['msg_new']) AND $grade === 'Admin')
		{
			mysql_query('INSERT INTO news VALUES(\'\',
			\''.mysql_real_escape_string(htmlspecialchars($_POST['titre'])).'\',
			\''.mysql_real_escape_string($_POST['msg_new']).'\',
			\''.time().'\')')or die(mysql_error());
	
			echo 'Fait !';
		}
		else
		{
			mysql_query("UPDATE msg SET message = '".mysql_real_escape_string($_POST['msg'])."' , motkill = '' WHERE id='1'") or die(mysql_error());
			mysql_query('INSERT INTO msg_log VALUES(\'\',
			\''.$pseudo.'\',
			'.time().',
			\''.$_SERVER['REMOTE_ADDR'].'\',
			\''.mysql_real_escape_string($_POST['msg']).'\')') or die(mysql_error());
			
			echo 'Fait !<br/><a href="index.php">Retour</a>';
		}
	}
}
elseif($_GET['pg'] == "reztlm")
{
if($grade == "Admin" OR $grade == "Arbitre")
{
mysql_query("UPDATE membres SET pv = '2' , meutreparty = '0' WHERE pv != '3'") or die(mysql_error());
mysql_query("UPDATE membres SET meutreparty = '0' WHERE pv = '3'") or die(mysql_error());
echo 'Fait ! <br/><a href="index.php?pg=admin">Retour</a>';
}
}
elseif($_GET['pg'] == "forum")
{
include("forum.php");
}
elseif($_GET['pg'] == "test")
{
echo time();
?>
<br/><br/>
<?php
}
elseif($_GET['pg'] == "contest" AND $_GET['id'] != "")
{
if($_POST['msg'] != "")
{
$reson = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
mysql_query("INSERT INTO contest VALUES('', '$id', '$reson', '$pseudo')") or die(mysql_error());
echo 'Fait !<br/><a href="index.php?pg=assassinat">Retour</a>';
}
else
{
echo "Tu ne peux pas envoyer une contestation vide !";
}
}
elseif($_GET['pg'] === 'doncash' AND is_numeric($_GET['somme']))
{
$somme = mysql_real_escape_string(htmlspecialchars($_GET['somme']));
$msg = mysql_real_escape_string(htmlspecialchars($_GET['msg']));
$idaqui = mysql_real_escape_string(htmlspecialchars($_GET['id']));
if($pieces >= $somme AND 0 < $somme)
{
$result = mysql_query('SELECT pseudo FROM membres WHERE id=\''.$idaqui.'\' LIMIT 1') or die(mysql_error());
$donnees = mysql_fetch_array($result);
mysql_query('INSERT INTO trafic VALUES(\'\',
\'non\',
\'non\',
\''.$msg.'\',
\''.$id.'\',
\''.$idaqui.'\',
\''.$pseudo.'\',
\''.$donnees['pseudo'].'\',
\''.$somme.'\',
'.time().')') or die(mysql_error());
mysql_query('UPDATE membres SET cash=cash+'.$somme.' WHERE id=\''.$idaqui.'\'');
mysql_query('UPDATE membres SET cash=cash-'.$somme.' WHERE id=\''.$id.'\'');
echo 'Le transfert est réussi.';
}
else
{
echo 'Une erreur c\'est produite, as-tu assez de pièces ?';
}
}
elseif($_GET['pg'] == 'banfocompte')
{
	if($grade == 'Admin' OR $grade == 'Arbitre')
		{
		$reponse = mysql_query("SELECT pseudo, ip, id
		FROM membres
		WHERE ip
		IN (

		SELECT ip
		FROM membres
		GROUP BY ip
		HAVING count( ip ) >2
		)
		ORDER BY `membres`.`ip` ASC");
		while ($donnees = mysql_fetch_array($reponse) )
		{
		if($donnees['ip'] != '0.0.0.0')
		{
		$pseudo = $donnees['pseudo'];
		$time = time()+(3600*24*7);
		$ip = $donnees['ip'];

		$infoClones = mysql_query('SELECT * FROM membres WHERE ip = \''.$donnees['ip'].'\'')or die(mysql_error());
		$noms = '';
		while($donneesClones = mysql_fetch_array($infoClones))
		{

			$noms = $donneesClones['pseudo'].' - '.$noms;
		}


		mysql_query("INSERT INTO ban VALUES('$pseudo', '$time', 'Trop de clones ( limite 2 )<br/>Tes clones : $noms', '$ip')")or die(mysql_error());
		mysql_query('UPDATE membres SET ip=\'0.0.0.0\', pv = 3 WHERE id= \''.$donnees['id'].'\'')or die(mysql_error());
		}
		}
		$info = mysql_query('SELECT * FROM membres')or die(mysql_error());
		while($donnees = mysql_fetch_array($info))
		{
			$inf2 = mysql_query('SELECT * FROM screen WHERE killeur = \''.$donnees['pseudo'].'\' AND valid = \'valide\' ')or die(mysql_error());
			mysql_query('UPDATE membres SET meutre = '.mysql_num_rows($inf2).' WHERE pseudo=\''.$donnees['pseudo'].'\'')or die(mysql_error());
		}
		echo 'Fait !';
	}
}
elseif($_GET['pg'] == "propos_pay")
{
?>
Après avoir fait ces instructions, ton compte sera crédité de 100 pièces instantanément. <br/>N'oublie pas que ce n'est pas obligatoire, tu as des pièces gratuitement en tuant des habbos.<br/>
<table border="0" width="436" height="411" style="border: 1px solid #E5E3FF;" cellpadding="0" cellspacing="0">
 <tr>
  <td colspan="2" width="436">
   <table width="436" border="0" cellpadding="0" cellspacing="0">
    <tr height="27">
     <td width="127" align="left" bgcolor="#D0D0FD">
      <a href="http://www.allopass.com/" target="_blank"><img src="http://payment.allopass.com/imgweb/common/access/logo.gif" width="127" height="27" border="0" alt="Allopass"></a>
     </td>
         <td width="309" align="right" bgcolor="#D0D0FD">
      <font style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000084; font-style : none; font-weight: bold; text-decoration: none;">
       Solution de micro paiement sécurisé<br>Secure micro payment solution
      </font>
     </td>
    </tr>
    <tr height="30">
     <td colspan="2" width="436" align="center" valign="middle" bgcolor="#F1F0FF">
      <font style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000084; font-style : none; font-weight: bold; text-decoration: none;">
       Pour acheter ce contenu, insérez le code obtenu en cliquant sur le drapeau de votre pays      </font>
      <br>
      <font style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #5E5E90; font-style : none; font-weight: bold; text-decoration: none;">
       To buy this content, insert your access code obtained by clicking on your country flag
      </font>
     </td>
    </tr>
        <tr height="2"><td colspan="2" width="436" bgcolor="#E5E3FF"></td></tr>
   </table>
  </td>
 </tr>
 <tr height="347">
  <td width="284">
   <iframe name="APsleft"  width="284" height="347" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="http://payment.allopass.com/acte/scripts/iframe/left.apu?ids=171092&idd=470281&lang=fr"></iframe>
  </td>
  <td width="152">
   <iframe name="APsright" width="152" height="347" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="http://payment.allopass.com/acte/scripts/iframe/right.apu?ids=171092&idd=470281&lang=fr"></iframe>
  </td>
 </tr>
 <tr height="5"><td colspan="2" bgcolor="#D0D0FD" width="436"></td></tr>
</table>

<?
}
elseif($_GET['pg'] == "oktuapayer")
{
echo 'Toute l\'équipe te remercie de ton achat.<br/><br/>T\'es pièces on été livrée.';
}
elseif($_GET['pg'] == "aide")
{
?>
1. Est-ce que l'on peut tuer une personne avec l'effet FX invisible ou la tuer avec l'effet ?<br>
- Non, vous ne pouvez pas tuer une personne avec un effet invisible ou vous même le porter.<br>
<br>
<br>
2. Est-ce qu'on est obliger de poster nos kills en format .png ?<br>
- Oui, si vous poster un kill dans un autre format, il sera rejeter. Le kill ne sera donc pas poster.<br>
<br>
3. Et comment mettre en format .png ?<br>
- Vous prenez votre screen puis vous l'enregistrer en sélectionnant "Type : PNG (*.PNG)"<br>
<br>
<br>
4. Une partie commence et fini quand ?<br>
- Normalement, une partie commence le Mercredi à 13H00 et fini le Dimanche soir. Le lundi et Mardi, il n'y a pas de partie, donc pas de kill.<br>
<br>
5. A quoi sert les objets ? (ex. Couteau, gilet par balle)<br>
- Les objets sont détaillés dans la boutique. Vous retrouverez leurs prix, et leurs descriptions. La boutique se situe à gauche de votre écran, en dessous de votre pseudo.<br>
<br>
<br>
6. Y-a t-il d'autre règles sur HabboShoot ?<br>
- Non, il n'y a pour l'instant aucune autre règles. SI il y en a, vous serez prévenu et la liste des règles sur l'accueil sera actualiser.<br>
<br>
7. Je veux être arbitre ! Comment faire ?<br>
- Pour être arbitre, il vous faut patiente.. Et bien sur se faire démarquer des autres ! Si vous avez ceci, dededede4 vous contactera pour en discuter et passer un test ! Alors patiente !!<br>
<br>
<br>
8. Pourquoi j'ai été supprimé d'HabboShoot ?<br>
- Si vous êtes supprimé de habboshoot, il n'y a aucune autre raison que : Vous avez plus de 2 comptes par connection sur habboshoot. Nous ne pouvons rien faire. Donc faites attention !<br>
<br>
P.S : Si vous avez déjà 2 comptes et vous vous connectez sur le compte d'un ami, sa vous en fera 3 !!<br>
<br>
9. C'est quand que mon kill va être accepter ?<br>
- Il te faudra de la patiente car les arbitres ne pourront pas être là à toutes heure ou vous killez.<br>
<br>
10. Pourquoi des gens ont mon pseudo dans leur mission ?<br>
- C'est un code qui peux être utiliser sur le forum d'HabboShoot ou dans les descriptions qui affiche le pseudo du connecter. Exemple : Si je marque *TOI*, vous verrez votre pseudo, moi le mien. Il y a aussi un deuxième code *KILL* qui affiche le mot kill de la partie.<br>
<br>
11. J'ai payer celui qui ma tuer pour qu'il envoie pas le screen mais il l'a envoyé !<br>
- Tampis pour toi ! Les règles ne l'interdisent pas ! Sois prudent !<br>
<br>
12. Vous pouvez supprimer mon compte?<br>
- Non ! On ne supprime aucun compte sauf les clones !<br>
<br>
13. J'ai kill quelqu'un mais j'ai pas cliqué sur lui et y'a son pseudo en haut, c'est valide?<br>
- Si il y a le chooser et que vous êtes seul oui, sinon ce n'est pas valide !<br>
<br>
14. Je peux killer sur la habbo beta?<br>
- Oui, bien sur ! Tant que tu respectes les règles.<br>
<br>
15. J'ai plus de pièce, j'ai plus de balle et mon clone aussi. Comment faire?<br>
- Dit le à un arbitre avec tes deux comptes, il verifira les ips et t'enverra 5 pièces.<br>
<br>
16. Je suis le killeur et j'ai kill le habbo sur une case où il PEUT y avoir plusieurs personnes. Valide?<br>
- Non. Les règles le disent parfaitement :"La victime et le killeur doivent être visibles."<br>
<br>
17. J'ai oublié de cliquer sur "bonus cagoule" alors que j'ai une cagoule ! Comment faire?<br>
- On n'y peut rien ! Tu n'auras pas le bonus de 3 pièces.<br>
<br>Par M1nhminh
<?php
}
elseif($_GET['pg'] == "tresor")
{
?>
Le trésor public te permet d'&eacute;changer des magos contre des pièces, et surtout d'échanger tes pièces contre des magos que tu pourras utiliser sur habbo.<br/><br/>
<div style="text-align: center;"><span
 style="color: rgb(255, 0, 0);">Si t'échanges 1 MAGO t'auras 5 PIECES.<br/>Si t'échanges 8 PIECES t'auras 1 MAGO.</span></div><br/>
<?php
$info = mysql_query('SELECT * FROM tresort LIMIT 1')or die(mysql_error());
$donnees = mysql_fetch_array($info);
if($donnees['somme'] > 0)
{
if(isset($_POST['sous']))
{
if($donnees['somme'] >= $_POST['sous'])
{
if($_POST['sous']*8 <= $pieces AND $_POST['sous'] > 0)
{
$pices = $_POST['sous']*8;
mysql_query('UPDATE tresort SET somme = somme-'.mysql_real_escape_string(htmlspecialchars($_POST['sous'])).'')or die(mysql_error());
mysql_query('UPDATE membres SET cash = cash-'.mysql_real_escape_string(htmlspecialchars($pices)).' WHERE pseudo=\''.$pseudo.'\'')or die(mysql_error());
mysql_query('INSERT INTO tresort_vente VALUES(\'\',
\''.mysql_real_escape_string(htmlspecialchars($_POST['sous'])).'\',
\''.$pseudo.'\',
'.time().')') or die(mysql_error());
$pieces = $pieces-$_POST['sous']*8;
$donnees['somme'] = $donnees['somme'] - $_POST['sous'];
echo '<div style="text-align: center;"><span
 style="color: rgb(255, 0, 0);">Fait !</span></div><br/>';
}
else
{
echo '<div style="text-align: center;"><span
 style="color: rgb(255, 0, 0);">Trop pauvre</span></div><br/>
';
}
}
}
echo '
<h3>Échanger des pièces contre des magos jouables sur habbo :</h3>Le trésor public dispose de '.$donnees['somme'].' magos, et il les vend.<br/>
<br/><center>
<form method="post"><select name="sous">';
$somme =0;
$mago =0;
while($donnees['somme'] > $mago)
{
$somme = $somme +8;
$mago = $mago + 1;
echo '<option value="'.$mago.'">Achete '.$mago.' mago pour '.$somme.' pièces.</option>';
}
echo '</select><br/><input type="submit" value="Ok !" /></form></center>';
}
else
{
echo 'Le trésor public n\'a plus de mago !<br/>Il faut attendre quelques jours que le compte se recharge.';
}
?>
<h3>Échanger des euros contre des magos jouables sur habbo </h3>
C'est environs 1.80€ pour 20 magos (3€ si tu utilises un SMS), moins cher que dans l'hotel. <a href="http://sacs.dededede4.fr" target="_blank">Clique</a>
<?php
/*
<center><img src="img/sousousachatmago.png" style="border:1px solid #000000;"/><br/><small><i>Super, M1nhminh recevra 5 pièces sur habboshoot !</i></small></center>
*/
?><h3>Échanger des magos contre des pièces jouables sur habboshoot </h3>
<g>Il suffit d'envoyer tes magos en cadeau à « DealAndShoot », et dans le petit message de marquer ton pseudo.<br/>
Tu recevras 5 fois en pièces ce que tu as envoyé en mago.<br/>[2magos = 10pièces; 20magos = 100pièces;...].<br/><br/><?php
}
elseif($_GET['pg'] === 'roomdead')
{
echo 'Voici les roomdeads officiel :';
$info = mysql_query('SELECT * FROM roomdead')or die(mysql_error());
if( mysql_num_rows($info))
{
echo '<ul>';
while($donnees = mysql_fetch_array($info))
{
echo '<li>'.$donnees['titre'].'</li>';
}
echo '</ul>
<div style="text-align: right;"><i>Officialise ta roomdead !</i></div>
';
}
else
{
echo '<br/>Y\'en a pas !';
}
}
elseif($_GET['pg'] === 'pgclan' AND is_numeric($_GET['id']))
{


	$info = mysql_query('SELECT * FROM clans WHERE id = \''.mysql_real_escape_string(htmlspecialchars($_GET['id'])).'\' LIMIT 1')or die(mysql_error());
if( mysql_num_rows($info))
{
$donnees = mysql_fetch_array($info);
if($pseudo === $donnees['chef'])
{
if(isset($_POST['msg']))
{
mysql_query('UPDATE clans SET description=\''.mysql_real_escape_string(htmlspecialchars($_POST['msg'])).'\' WHERE id='.$donnees['id'].'')or die(mysql_error());
$info = mysql_query('SELECT * FROM clans WHERE id = \''.mysql_real_escape_string(htmlspecialchars($_GET['id'])).'\' LIMIT 1')or die(mysql_error());
$donnees = mysql_fetch_array($info);
}
if($_POST['oui'] === 'on')
{
mysql_query('UPDATE clans SET accepte_inscription = \'oui\' WHERE id='.$donnees['id'].'')or die(mysql_error());
}
else
{
mysql_query('UPDATE clans SET accepte_inscription = \'non\' WHERE id='.$donnees['id'].'')or die(mysql_error());
}
}
echo '<img style="float:right; max-width: 250px; max-height:250px; border:0px;" src="';
if($donnees['url_badge'] === '')
{
	echo '/img/base_clans.png';
}
else
{
	echo $donnees['url_badge'];
}
echo '" />';
echo '<h1>'.$donnees['nom'].'</h1>';
echo '<ul>';
echo '<li>Chef : <a href="index.php?pg=fiches&habbo='.$donnees['chef'].'">'.$donnees['chef'].'</a></li>';
echo '<li>Clan crée le : '.date('d/m/Y', $donnees['datecra']).'</li>';
echo '<li>Nombre de kill depuis sa création : '.$donnees['nbr_kill'].'</li>';
echo '<li>Nombre de kill cette partie : '.$donnees['nbr_kill_partie'].'</li>';
echo '</ul>';
echo 'Description :<br/><br/>'.nl2br(bbcode($donnees['description'])).'<br/><br/>';
echo 'Liste des membres ['.$donnees['nbr_membres'].'/'.$donnees['Nmb_max_killeur'].'] : ';
$info2 = mysql_query('SELECT * FROM membres WHERE id_clan = \''.$donnees['id'].'\' ORDER BY meutreparty DESC')or die(mysql_error());
echo '<ol>';
while($donnees2 = mysql_fetch_array($info2))
{
	echo '<li><a href="index.php?pg=fiches&habbo='.$donnees2['pseudo'].'">'.$donnees2['pseudo'].'</a> avec '.$donnees2['meutreparty'].' kills.</li>';
}
echo '</ol>';
echo '<hr WIDTH="100%">';
if($iddesonclan === $donnees['id'] )
{
echo 'T\'es membre.<br/>
<br/>';
if($pseudo === $donnees['chef'])
{
	echo '<br/><h3>Administration :</h3>';
	
?>
	<form method="post" action="#"> 
Description du groupe : <br/>
<textarea name="msg" rows="10" cols="50"><?php echo $donnees['description'] ?></textarea><br/>
Accepter les inscriptions : <input name="oui" type="checkbox" <?php if($donnees['accepte_inscription'] === 'oui'){ echo 'checked="checked"'; } ?>><br/>
<div style="text-align: right;"><input type="submit" value="Ok !" /></div>
</form>
Dissoudre le clan : <a href="index.php" onclick="return confirm('Es-tu sur de vouloir supprimer ce clan ?\nIl sera irrécupérable.');">Clique</a>';
<?php echo '<br/><br/>Virer :';
}
}
else
{
	echo 'Inscription : Impossible pour l\'instant :D';
}
}
else
{
echo 'Le clan n\'existe pas..';
}
}
elseif($_GET['pg'] === 'info')
{
	$info = mysql_query('SELECT * FROM membres WHERE pseudo = \''.mysql_real_escape_string(htmlspecialchars($_GET['pseudo'])).'\' LIMIT 1')or die(mysql_error());
	
	if(mysql_num_rows($info))
	{
		$donnees = mysql_fetch_array($info);
		
		if($_GET['voir'] === 'meurtres')
		{
			echo 'Les meurtres de '.$donnees['pseudo'].' : <br/>';

			tableau_kill($donnees['pseudo'], '*', 25);

		}
		elseif($_GET['voir'] === 'victime')
		{
			echo 'Les meurtres où '.$donnees['pseudo'].' est la victime : <br/>';
			
			tableau_kill('*', $donnees['pseudo'], 25);
		}
		elseif($_GET['voir'] === 'parrain')
		{
			echo 'Les killeurs qui sont parrainés par '.$donnees['pseudo'].' :';
			
			$info = mysql_query('SELECT * FROM membres WHERE parrin = \''.$donnees['pseudo'].'\' ORDER BY datein DESC')or die(mysql_error());
			
			if( mysql_num_rows($info))
			{
				echo '<ul>';
				
				while($donneesparrain = mysql_fetch_array($info))
				{
					echo '<li><a href="index.php?pg=fiches&habbo='.$donneesparrain['pseudo'].'">'.$donneesparrain['pseudo'].'</a></li>';
				}
				
				echo '</ul>';
			}
			else
			{
				echo '<br/><br/>Personne.';
			}
		}
		elseif($_GET['voir'] === 'traffic')
		{
			echo 'Trafic de pièces de '.$donnees['pseudo'].' : ';
			
			
			$info = mysql_query('SELECT * FROM trafic WHERE id_donneur = \''.$donnees['id'].'\' OR id_reseveur = \''.$donnees['id'].'\' ORDER BY time DESC')or die(mysql_error());
			if( mysql_num_rows($info))
			{
				
				echo '<ul>';
			
				while($donneestrafic = mysql_fetch_array($info))
				{
					echo '<li>';
					
					if( $donneestrafic['id_donneur'] === $donnees['id'] AND $donneestrafic['arbitre'] !== 'oui')
					{
						echo ' -'.$donneestrafic['somme'].'p, don à '.$donneestrafic['pseudo_reseveur'].' le '.date('d/m/Y à H:i', $donneestrafic['time']).'. Message : « '.stripslashes($donneestrafic['motif']).' »';
					}
					elseif( $donneestrafic['id_reseveur'] === $donnees['id'] AND $donneestrafic['arbitre'] !== 'oui' )
					{
						
						if($donneestrafic['achat_magasin'] === 'oui')
						{
							echo $donneestrafic['somme'].'p, achat «'.stripslashes($donneestrafic['motif']).'» le '.date('d/m/Y à H:i', $donneestrafic['time']).'.';
						}
						else
						{
							echo ' +'.$donneestrafic['somme'].'p, par '.$donneestrafic['pseudo_donneur'].' le '.date('d/m/Y à H:i', $donneestrafic['time']).'. Message : « '.stripslashes($donneestrafic['motif']).' »';
						}
					}
					elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_donneur'] !== $donnees['id'])
					{
						if($donneestrafic['somme'] > 0 )
						{
							echo ' +';
						}
						echo $donneestrafic['somme'].'p, par l\'arbitre : '.$donneestrafic['pseudo_donneur'].' le '.date('d/m/Y à H:i', $donneestrafic['time']).'. Motif : « '.stripslashes($donneestrafic['motif']).' »';
					}
					elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_reseveur'] !== $donnees['id'])
					{
						echo 'Transfert d\'arbitre à '.$donneestrafic['pseudo_reseveur'].' de '.$donneestrafic['somme'].' pièces le '.date('d/m/Y à H:i', $donneestrafic['time']).'. Motif : « '.stripslashes($donneestrafic['motif']).' »';
					}
					elseif( $donneestrafic['arbitre'] === 'oui' AND $donneestrafic['id_reseveur'] === $donnees['id'])
					{
						echo 'Il s\'est créé '.$donneestrafic['somme'].'p le '.date('d/m/Y à H:i', $donneestrafic['time']).'. Motif : « '.stripslashes($donneestrafic['motif']).' »';
					}
					else
					{
						echo 'Erreur transfert ID '.$donneestrafic['id'].' Resev : '.$donneestrafic['id_reseveur'].' Donn : '.$donneestrafic['id_donneur'].' Acc : '.$donnees['id'];
					}
					echo '</li>';
					
					$i++;
					if($i === 5)
					{
						$i = 0;
						
						echo '</ul>';
						echo '<ul>';
					}
				}
				echo '</ul>';
			}
			else
			{
				echo '<br/>Aucun !';
			}
		}
		else
		{
			echo 'Je ne connais pas cette adresse, moi !';
		}
	}
	else
	{
		echo 'Ce pseudo n\'existe pas sur habboshoot !';
	}
}

/************************************/
/*		On veut voir une news   	*/
/************************************/
elseif($_GET['pg'] === 'news' AND is_numeric($_GET['id']))
{

// On fait une requête pour lister les infos de la news
$info = mysql_query('SELECT * FROM news WHERE id = \''.mysql_real_escape_string($_GET['id']).'\' LIMIT 1')or die(mysql_error());

// Si la news existe
if( mysql_num_rows($info))
{

	// Si on vaut supprimer un coms et s'il est admin ou arbitre
	if(isset($_GET['suppr']) && is_numeric($_GET['suppr']) && ($grade == 'Admin' OR $grade == 'Arbitre'))
	{
		$requete_suppr = mysql_query('DELETE FROM commentaires WHERE id = "'.mysql_real_escape_string($_GET['suppr']).'"') OR die('<font color="red">Le commentaire n\'existe pas !</font>');
		if($requete_suppr) echo '<font color="green">Commentaire supprimé !</font>';
	}
	
	/* On affiche la news vite fait bien fait */
	$donnees = mysql_fetch_array($info);
	echo '<center><h1>« '.stripslashes($donnees['titre']).' »</h1></center>';
	echo nl2br(stripslashes(bbcode($donnees['blabla'])));
	
	
    /************************************/
	/*		On passe aux commentaires 	*/
	/************************************/
	
	echo '<center><h2>Commentaire(s)</h2></center>';

	// On fait une requête pour lister les commentaires de cette news	
	$info_coms = mysql_query('SELECT * FROM commentaires WHERE id_news = \''.$donnees['id'].'\' ORDER BY time')or die(mysql_error());
	
	// S'il y a des commentaires
	if( mysql_num_rows($info_coms))
	{
		// On fait une boucle pour lister les commentaires
		while($donnees_coms = mysql_fetch_array($info_coms))
		{
			$i++;
			
				if ($i%2 == 1)
				{
				   // $i = impair
				   $position = 'right';
				   
				   /* On affiche l'image de suite, pour qu'elle soit à gauche du texte.
				    * Si c'est pair, on le fait plus bas pour qu'elle soit à droite du texte?
				    */

				}
				else
				{
					// $i = pair
					$position = 'left';
				}
				
				$margin = $position == 'right' ? 'margin-right: 10px;' : '';
				$direction = $position == 'right' ? '4' : '2';

			
		
			// On affiche l'avatar
			echo '<img height="94px" width="68px" src ="img/avatarimage.gif"
			style = "float:'.$position.'; clear: both; display:inline margin-top: -10px; background-image:url(http://www.habbo.fr/habbo-imaging/avatarimage?user='.$donnees_coms['pseudo'].'&action=null&direction='.$direction.'&head_direction='.$direction.'&gesture=sml&size=b&img_format=gif); background-repeat: no-repeat; background-position: 0px -15px; margin-top: -6px;" />';
			
			// On crée le div et on affiche le message
			echo '<div style = "padding: 2px; background-color: rgb(65, 65, 65); width: 480px; min-height: 80px; margin-'.$position.':80px; margin-top: 10px; '.$margin.'">';
			echo nl2br(stripslashes(bbcode($donnees_coms['message'])));
			
			// Infos sur l'auteur et la date
			echo '<br /><br /><br /><br /><em>Posté par <a href="?pg=fiches&habbo='.stripslashes($donnees_coms['pseudo']).'">'.stripslashes($donnees_coms['pseudo']).'</a> le '.date('d/m/Y à H:i', $donnees_coms['time']).'</em>';
			
			// S'il est admin, on lui propose de supprimer le commentaire
			if($grade == 'Admin' OR $grade == 'Arbitre')
			echo ' - <a href="?pg=news&id='.$donnees['id'].'&suppr='.$donnees_coms['id'].'" onClick="return confirmSubmit(\'Es-tu sur de vouloir supprimer le commentaire?\')" style="color:gray">Supprimer</a>';
			
			// On ferme le div
			echo '</div>';
			
			
		}
	}
	
	// Ici, le formulaire pour poster un message
	echo '<center style = "clear:both">'."\n"
	. '<form method="post" action="index.php?pg=commenter&amp;id='.$donnees['id'].'">'."\n"
	. '<p>'."\n" 
	. '<textarea name="commantaire" style = "width:480px; height:80px;"></textarea>'."\n"
	. '<br />'."\n"
	. '<input type="submit" value="Commenter !" />'."\n"
	. '</form></center>'."\n";
}

// Sinon c'est que la news n'existe pas
else
{
	echo 'Elle n\'existe pas, cette news !';
}

}

/************************************/
/*	On veut poster un commentaire 	*/
/************************************/
elseif($_GET['pg'] === 'commenter' AND is_numeric($_GET['id']) AND $_POST['commantaire'] !== '')
{
	// S'l y a plus d'une minute entre son ancien commentaire et celui qu'il veut poster
	if($_SESSION['antiflood'] <= time())
	{
		
		// On fait une requête pour ajouter le commentaire
		mysql_query('INSERT INTO commentaires VALUES(\'\',
		\''.$pseudo.'\',
		\''.mysql_real_escape_string(htmlspecialchars($_POST['commantaire'])).'\',
		'.time().',
		\''.mysql_real_escape_string(htmlspecialchars($_GET['id'])).'\'
		)') or die(mysql_error());
		
		// On met à jour l'anti-flood
		$_SESSION['antiflood']  = time()+60;
		
		// On affiche un message
		echo 'Fait !<br /><a href="?pg=news&id='.$_GET['id'].'" title="Retourner à la news">Retour</a>';
		
	}
	
	// S'il y a moins d'une minute entre son ancien commentaire et celui qu'il veut poster
	else
	{
		// On lui affiche un message d'erreur
		echo 'Il faut attendre 1 minute entre chaque commentaire !';
	}

	
}
elseif($_GET['pg'] === 'badgeanif')
{
	$info = mysql_query('SELECT * FROM badges WHERE pseudo = \''.$pseudo.'\' AND url = \'/img/annif.png\' LIMIT 1')or die(mysql_error());

	// Si la news existe
	if(!mysql_num_rows($info))
	{
		mysql_query('INSERT INTO badges VALUES(\'\',
			\''.$pseudo.'\',
			\'/img/annif.png\',
			\'Habboshoot, 1 an !\'
			)') or die(mysql_error());
			
			echo 'Fait !';
	}
	else
	{
		echo 'T\'as déjà ton badge !';
	}
}
/**************************************/
/* La page qu'il demande n'existe pas */
/**************************************/
else 
{
	// On lui affiche un message d'erreur
	echo 'On ne sais pas où tu veux aller...';
}

}
?>
</p>
</div>

<div style="width : 579px;
height : 4px;
background-image: url(img/mbbm1.png);"></div>
</div>

<div style="float:left; height : 100%;">

<div style = "background-image: url(img/mmd.png); height: 4px;"></div>

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
<div style="margin-left:22px;
width : 193px;
height : 43px;
background-image: url(img/chb.png);
"></div><br/>
<center><form action="#" onSumbmit="Chat.envoyer(); return false;">
<?php
$envoiok = $ban !== 'oui' ? 'value=""' : 'disabled="disabled" value="Tu es banni, tu ne peux pas poster de messages !"';
?>
<input name="message_du_chat" style = "width:90%;" id="message_du_chat" onKeyPress="if (event.keyCode == 13){Chat.envoyer();return false;};" <?php echo $envoiok; ?> /> <br/>
</form>
<!-- <span><br /><a href="#" id="lien_chat_etendu" onClick="Chat.ouvrir(); return false;">Ouvrir le chat dans une fenêtre</a></span> -->
</center>
</div>

<div style = "background-image: url(img/mmd.png); height: 10px;"></div>
<div style="min-height : 100%;
width : 215px;
background-image: url(img/mmd.png);
	font-family:Verdana, Geneva, sans-serif;

	font-size:10px;
	font-style:inherit;
	font-weight: bold;
">
<div style="width : 215px;
height : 19px;
background-image: url(img/mndb1.png);
"></div>
<center>News :</center>

<?php
$info = mysql_query('SELECT * FROM news ORDER BY time DESC LIMIT 5')or die(mysql_error());
if( mysql_num_rows($info))
{
	while($donnees = mysql_fetch_array($info))
	{
		echo ' - <a href = "index.php?pg=news&id='.$donnees['id'].'">'.stripslashes($donnees['titre']).'</a><br/>';
	}
}
else
{
echo 'Aucune, pour l\'instant.';
}
?>

<div id="chat_etendu" style="background-image:url(img/bg_trans.png); width:100%; height:100%; position:absolute; z-index:10; top:0px; left:0px; position:fixed; display: none; text-align:center">
<p><font color="white">Patiente...</font></p>
</div>
<!--
<div style="width : 215px;
height : 25px;
background-image: url(img/mhd.png);"></div>

<center>Pub :<br/><br/>
	 
   </center></div> -->
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
