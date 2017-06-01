<?php
if($oui == 'oui')
{
$id_membre = $id;

if($_GET['action'] == '')
{
?><br/><br/><br/>
<center><strong>Forum officiel d'Habboshoot</center></strong>
<br/><br/>
<?php

/*On va prendre le post ORDER BY categorie LIMIT 1.
 *  Puis on va chercher les 5 derniers topiks qui ont rapport avec ça.
 * On le rajoute dans un array.
 * 
 * On recommance ce paragraphe mais en fesant un WHERE != array.
 */
$stop = 2;
$arraychut = array();
$nombre_de_forum = 10; // Doit y avoir au moins 3 catégories, sinon...
while($stop <= $nombre_de_forum )
{
		
	if(count($arraychut) >= 1)
	{
	foreach($arraychut as $case => $valeur)
	{
		if(count($arraychut) >= 2)
		{
			$interdiction = $interdiction.' AND categorie != \''.$valeur.'\'';
		}
		else
		{
			$interdiction = $interdiction.' WHERE categorie != \''.$valeur.'\'';
		}
	
	}
	}
	

$annonce = mysql_query('SELECT * FROM forum '.$interdiction.' ORDER BY categorie LIMIT 1')or die(mysql_error());;
$donneescatego = mysql_fetch_array($annonce);
echo '<h3>'.$donneescatego['categorie'].'</h3>';

$messages = mysql_query('SELECT * FROM forum WHERE categorie = \''.$donneescatego['categorie'].'\' AND ID_reponce = 0 AND time > 1368467143 ORDER BY time_d DESC LIMIT 5')or die(mysql_error());

array_push($arraychut,$donneescatego['categorie']);
?>
<table style="text-align: left; width : 99%;" border="0"
 cellpadding="2" cellspacing="2">
<?php
while($donnees = mysql_fetch_array($messages))
{
?>
 <tr>
 <td style="width: 74%; background-color: #414141;">
 <?php
$array_lecteur = unserialize($donnees['vus']);
if($array_lecteur[$pseudo] < $donnees['time_d'] AND $datein <= $donnees['time_d'])
{
	echo '/!\\';
}
 ?>
 <a href ="index.php?pg=forum&amp;action=liremsg&id=<?php echo $donnees['ID']; ?>"><?php echo stripslashes($donnees['sujet']);  ?></a>
 </td>
 
<td style="background-color:  #414141; width: 25%"
 align="undefined" valign="undefined"><div style="text-align: right;"><a href="index.php?pg=fiches&habbo=<?php echo $donnees['auteur_d']; ?>"/><?php echo $donnees['auteur_d']; ?></a></div></td>
    </td>
	</tr>
<?php
}
?>
  </tbody>
</table>
<div style="text-align: right;"><br/><a href="index.php?pg=forum&amp;action=voirtout&amp;categorie=<?php echo $donneescatego['categorie']; ?>"><i>Voir tout</i></a></div>
<?php
$stop++;
}
?>
<br/>
<?php
// Fin du listing.
$retour = mysql_query("SELECT COUNT(*) AS nbre_entrees FROM forum WHERE time > 1368467143");
$donnees301 = mysql_fetch_array($retour);
$retour = mysql_query("SELECT COUNT(*) AS nbre_entrees FROM forum WHERE ID_reponce = 0 AND time > 1368467143");
$donnees302 = mysql_fetch_array($retour);
?>

Il y a <?php echo $donnees302['nbre_entrees']; ?> sujets avec <?php echo $donnees301['nbre_entrees']; ?> posts !<br/>
<br/><center>
<form method="post" action="index.php?pg=forum&amp;action=newmsg">
Catégorie :<br/>
<input type="radio" name="categorie" value="Divers" checked="checked" /> Divers
<input type="radio" name="categorie" value="Habboshoot"/> Habboshoot
<input type="radio" name="categorie" value="Contestation" />  Contestation d'assassinat
<input type="radio" name="categorie" value="Idees" /> Idées
<br/>
<input type="radio" name="categorie" value="Habbohotel" /> Habbohotel
<input type="radio" name="categorie" value="Fun" /> Fun
<input type="radio" name="categorie" value="Images" /> Images
<input type="radio" name="categorie" value="Musique" /> Musique 
<br/>
<input type="radio" name="categorie" value="Pub" /> Pub
<input type="radio" name="categorie" value="Partenariat" /> Partenariat 


<br/><br/>
 Sujet : <br/>

   <input type="text" name="sujet" id="sujet" size="45" maxlength="60" /><br/>
       <label for="ameliorer">Ton message :</label><br />
       <textarea name="msg" id="ameliorer" rows="10" cols="50"></textarea>
	   <br/>
   </p>
  <input type="submit" value="Envoyer" /></form></center>
<?php }
elseif($_GET['action']=="newmsg")
{
	if($_POST['sujet'] != "" AND $_POST['msg'] != "")
	{
		if($_SESSION['timepost']  <= time() )
		{
			if(strlen($_POST['sujet']) <= 60)
			{
			$postpossible = array('Divers', 'Habboshoot', 'Habbohotel', 'Groupes', 'Fun', 'Idees', 'Images', 'Musique', 'Partenariat', 'Pub', 'Contestation');
				if(in_array($_POST['categorie'] , $postpossible))
				{
					$sujet = mysql_real_escape_string(htmlspecialchars($_POST['sujet']));
					$msg = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
					mysql_query("INSERT INTO forum VALUES('', '', '$sujet', '$msg', '$pseudo', '".time()."', '".time()."', '$pseudo', '".$_POST['categorie']."', '".serialize(array($pseudo => time()))."')")or die(mysql_error());
					$_SESSION['timepost'] = time() + 30;
					echo 'C\'est fait !<br/><a href = "index.php?pg=forum" > Clique pour la liste des sujet </a>';
				}
			}
			else
			{
			echo 'Hey, le sujet est trop long !';
			}
		}
		else
		{
		echo 'Anti flood : Interdiction de poster des messages si rapidement !<br/>30 secondes...';
		}
	}
	else
	{
	echo "Tu ne peux pas laisser un champs vide !";
	}
} 
elseif($_GET['action']=="liremsg" AND $_GET['id'] != "")
{


$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
// Infos du 1er message via l'ID communiquée
$message = mysql_query("SELECT * FROM forum WHERE ID = '$id' AND ID_reponce = '0'")or die(mysql_error());
$message = mysql_fetch_array($message);
//description du mec du 1er message
$membre = mysql_query("SELECT description , meutre , statu FROM membres WHERE pseudo = '".$message['auteur']."'")or die(mysql_error());
$membre = mysql_fetch_array($membre);


//$info = mysql_query('SELECT * FROM forum WHERE id_membre = \''.$_SESSION['id'].'\' AND id_topic = \''.$message['id'].'\' LIMIT 1')or die(mysql_error());

$array_lecteur = unserialize($message['vus']);
$array_lecteur[$pseudo] = time();
mysql_query('UPDATE forum SET vus= \''.serialize($array_lecteur).'\' WHERE ID = '.$id.'')or die(mysql_error());

?>
<center><strong><?php echo stripslashes($message['sujet']); ?></strong></center>
<div style="text-align: right;"><a href = "#basblabla">Descendre</a> </div>
<center><table style="text-align: left;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 410px; background-color: rgb(40, 41, 42);"
 align="undefined" valign="undefined">
 <div style="text-align: right; font-style: italic;"><?php echo 'Le '.date('d/m/Y', $message['time']).' à '.date('H:i', $message['time']); ?></div>
 <div style="overflow: auto; width: 410px;"><br/>
 <?php echo bbcode(nl2br(stripslashes($message['msg']))); ?><br/>
 _____________________________<br/>
 <?php echo bbcode(nl2br(stripslashes($membre['description']))); ?>
 </div>
  <?php if($grade == "Admin" OR $grade == "Arbitre" OR $pseudo == $message['auteur'])
 {
echo '<br/><br/><a href ="index.php?pg=forum&amp;action=modif&amp;id='.$message['ID'].'">Editer</a>';
if($grade == "Admin")
{ ?>
<a href ="index.php?pg=forum&amp;action=del&amp;id=<?php echo $message['ID']; ?>"> - Suprimer</a>
<?php
}

 }
 ?>
 </td>
      <td style="width: 130px; background-color: rgb(40, 41, 42); vertical-align: middle;"
 align="undefined" valign="undefined"><center><img src="http://www.habbo.fr/habbo-imaging/avatarimage?user=<?php echo $message['auteur']; ?>&action=std&frame=3&direction=4&head_direction=4&gesture=agr&size=b&img_format=gif"/><br/><a href="index.php?pg=fiches&habbo=<?php echo $message['auteur']; ?>"><?php echo $message['auteur']; ?></a>
<br/>
<?php
if($membre['statu'] == "Admin")
{
	echo 'Admin';
}
else
{
	$meutresniveau = $membre['meutre'];
	
	echo gradelvl($meutresniveau);
	
	if($membre['statu'] == "Arbitre")
	{
		echo "<br/>Arbitre";
	}
}
?>
<br/>
<?php
$retour = mysql_query("SELECT COUNT(*) AS nbr_post FROM forum WHERE auteur = '".$message['auteur']."'")or die(mysql_error());
$nombremsg = mysql_fetch_array($retour);
echo $nombremsg['nbr_post'];
?> Post(s)
<br/><br/>
</center>
 </td>
</tr>
<?php
//infos des autres messages du sujet
$message = mysql_query("SELECT * FROM forum WHERE ID_reponce = '$id' ORDER BY time")or die(mysql_error());
while($donnees = mysql_fetch_array($message))
{
//description des mec des reponces
$membre = mysql_query("SELECT description , meutre , statu FROM membres WHERE pseudo = '".$donnees['auteur']."'")or die(mysql_error());
$membre = mysql_fetch_array($membre);
?>
    <tr>
      <td style="width: 410px; background-color: rgb(40, 41, 42);"
 align="undefined" valign="undefined">
 <div style="text-align: right; font-style: italic;">

 <?php echo 'Le '.date('d/m/Y', $donnees['time']).' à '.date('H:i', $donnees['time']); ?></div>
 <div style="overflow: auto; width: 410px;"><br/>
 <?php echo bbcode(nl2br(stripslashes($donnees['msg']))); ?><br/>
 _____________________________<br/>
 <?php echo bbcode(nl2br(stripslashes($membre['description']))); ?>
 </div>
 <?php if($grade == "Admin" OR $grade == "Arbitre" OR $pseudo == $donnees['auteur'])
 {
echo '<br/><br/><a href ="index.php?pg=forum&amp;action=modif&amp;id='.$donnees['ID'].'">Editer</a> ';
 }
 if($grade == "Admin")
{
echo '- <a href ="index.php?pg=forum&amp;action=del&amp;id='.$donnees['ID'].'">Suprimer</a>';
}
 ?>
 </td>
      <td style="width: 130px; background-color: rgb(40, 41, 42); vertical-align: middle; "
 align="undefined" valign="undefined"><center><img src="http://www.habbo.fr/habbo-imaging/avatarimage?user=<?php echo $donnees['auteur']; ?>&action=std&frame=3&direction=4&head_direction=4&gesture=agr&size=b&img_format=gif"/><br/><a href="index.php?pg=fiches&habbo=<?php echo $donnees['auteur']; ?>"><?php echo $donnees['auteur']; ?></a>
 <br/>
 <?php
if($membre['statu'] === 'Admin')
{
	echo 'Admin';
}
else
{
	$meutresniveau = $membre['meutre'];

	echo gradelvl($meutresniveau);

	if($membre['statu'] === 'Arbitre')
	{
		echo '<br/>Arbitre';
	}
}
?>
<br/>
<?php
$retour = mysql_query("SELECT COUNT(*) AS nbr_post FROM forum WHERE auteur = '".$donnees['auteur']."'")or die(mysql_error());
$nombremsg = mysql_fetch_array($retour);
echo $nombremsg['nbr_post'];
?> Posts<br/><br/></center>
</td>
    </tr>
<?php
}
?>
  </tbody>
</table>
</center>
<div style="text-align: right;"><a href="index.php?pg=forum">Revenir a la liste des discussions</a> ou <a href="#">remonter</a> !</div>

<center>
<form method="post" action="index.php?pg=forum&amp;action=reponce&amp;id=<?php echo $id; ?>">
 <br/>
       <label for="ameliorer">Reponse :</label><br />
       <textarea name="msg" id="ameliorer" rows="7" cols="40"></textarea>
	   <br/>
   </p>
  <input type="submit" value="Envoyer" /></center>
</form>
<?php
}
elseif($_GET['action'] == "reponce" AND $_GET['id'] != "")
{
	if($_POST['msg'] !== '')
	{
		$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
		$result = mysql_query('SELECT ID FROM forum WHERE ID = \''.$id.'\'')or die(mysql_error());
		$dejainscript = mysql_num_rows($result);
	if($dejainscript == 0)
	{
		echo 'Le sujet n\'existe pas.';
	}
	else
	{

	$msg = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
	mysql_query("INSERT INTO forum VALUES('', '$id', '', '$msg', '$pseudo', '".time()."', '', '', 'Divers', '')")or die(mysql_error());
	mysql_query("UPDATE forum SET time_d = '".time()."' , auteur_d = '".$pseudo."' WHERE ID='$id'")or die(mysql_error());
	echo "Fait !";
	}
	}
	else
	{
	echo 'Tu as oublié le contenu !';
	}
}
elseif($_GET['action'] == "del" AND $id != "")
{
	$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
	$result = mysql_query('SELECT auteur FROM forum WHERE ID = \''.$id.'\'')or die(mysql_error());
	$dejainscript = mysql_num_rows($result);
	$result = mysql_fetch_array($result);
	if($result['auteur'] === $pseudo)
	{
		mysql_query("DELETE FROM forum WHERE id='$id'");
		echo 'Fait !';
	}
	elseif($grade === 'Admin')
	{
	mysql_query('DELETE FROM forum WHERE id=\''.$id.'\'');
	echo 'Fait !';
	}
}
elseif($_GET['action'] === 'modif' AND is_numeric($id))
{
	$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
	$result = mysql_query('SELECT auteur , msg FROM forum WHERE ID = \''.$id.'\'')or die(mysql_error());
	$dejainscript = mysql_num_rows($result);
	$result = mysql_fetch_array($result);
	if($dejainscript == 0 )
	{
		echo 'ID inconnue.';
	}
	else
	{
		if(!isset($_POST['msg']))
		{
			if($result['auteur'] === $pseudo OR $grade === 'Admin' OR $grade === 'Arbitre')
			{
			?>
			<center>
			<form method="post" action="index.php?pg=forum&amp;action=modif&amp;id=<?php echo $id; ?>">
			 <br/>
				   <label for="ameliorer">Editer :</label><br />

				   <textarea name="msg" id="ameliorer" rows="7" cols="40"><?php echo stripslashes($result['msg']); ?></textarea>
				   <br/>
			   </p>
			  <input type="submit" value="Envoyer" /></center>
			</form>
			<?php
			}
		}
		else
		{
			if($result['auteur'] === $pseudo)
			{
				$msg = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
				mysql_query("UPDATE forum SET msg = '".$msg."' WHERE ID='$id'")or die(mysql_error());
				echo "Fait !";
			}
			elseif($grade === 'Admin' OR $grade === 'Arbitre')
			{
				$msg = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
				mysql_query("UPDATE forum SET msg = '".$msg."' WHERE ID='$id'")or die(mysql_error());
				echo "Fait !";
			}
		}
	}
}
elseif($_GET['action'] === 'voirtout' AND $_GET['categorie'] !== '')
{


		$nombreDeNewsParPage = 50;
		
		$retour = mysql_query('SELECT COUNT(*) AS nb_news FROM forum WHERE ID_reponce = \'0\' AND categorie = \''.mysql_real_escape_string(htmlspecialchars($_GET['categorie'])).'\'');
								
		$donneesaffichage = mysql_fetch_assoc($retour);
		
		$totalDesNews = $donneesaffichage['nb_news'];
		$nombreDePages  = ceil($totalDesNews / $nombreDeNewsParPage);
	
		echo ' Page : ';

		for ($i = 1 ; $i <= $nombreDePages ; $i++){
			echo '<a href="index.php?pg=forum&action=voirtout&categorie=Divers&amp;page=' . $i . '">' . $i . '</a> ';
		}
		
		if(isset($_GET['page']))	$page = intval($_GET['page']);
		else $page = 1;
	 
		$premiereNewsAafficher = ($page - 1) * $nombreDeNewsParPage;

		// $message = mysql_query('SELECT * FROM forum WHERE ID_reponce = \'0\' ORDER BY time_d DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'');

$info = mysql_query('SELECT * FROM forum WHERE categorie = \''.mysql_real_escape_string(htmlspecialchars($_GET['categorie'])).'\' AND ID_reponce = 0  ORDER BY time_d DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'')or die(mysql_error());
if( mysql_num_rows($info))
{
	echo '<h3>Forum «'.htmlspecialchars($_GET['categorie']).'» :</h3>';
echo '<table style="text-align: left; width : 99%;" border="0"
 cellpadding="2" cellspacing="2">';
while($donnees = mysql_fetch_array($info))
{
	?>
 <tr>
 <td style="width: 74%; background-color: #414141;">
  <?php
$array_lecteur = unserialize($donnees['vus']);
if($array_lecteur[$pseudo] < $donnees['time_d'] AND $datein <= $donnees['time_d'])
{
	echo '/!\\ ';
}
 ?><a href ="index.php?pg=forum&amp;action=liremsg&id=<?php echo $donnees['ID']; ?>"><?php echo stripslashes($donnees['sujet']);  ?></a>
 </td>
 
<td style="background-color:  #414141; width: 25%"
 align="undefined" valign="undefined"><a href="index.php?pg=fiches&habbo=<?php echo $donnees['auteur_d']; ?>"/><div style="text-align: right;"><?php echo $donnees['auteur_d']; ?></div></a></td>
    </td>
	</tr>
<?php
}
echo '  </tbody>
</table>';
}
else
{
echo 'Aucun post sur cette catégorie ! ( T\'as surement du trifouiller l\'adresse de cette page).';
}
}
}
 ?>
