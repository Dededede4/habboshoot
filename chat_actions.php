<?php
ob_start();
session_start();

include('pass.php');

/*
 * $_GET[] contiens normalement les données retransmises par le forumulaire ajax.
 */

	// Si on veut lister les messages
	if($_GET['action'] === 'rafraichir')
	{
		if(is_numeric($_GET['lastID'])){
			$canDel = ($_SESSION['grade'] == 'Arbitre' OR $_SESSION['grade'] == 'Admin');
			session_write_close();
			while(1){
				$requete = mysql_query('SELECT * FROM chat WHERE id > \''.mysql_escape_string($_GET['lastID']).'\' ORDER BY time DESC LIMIT 10')or die(mysql_error()); // On fait une requête pour voir la liste de tous les messages
				if(mysql_num_rows($requete)){
					$tableau = array();
					while($donnees = mysql_fetch_array($requete)){
						$donnees['canDel'] = $canDel;
						$tableau[] = $donnees;
					}
					echo json_encode($tableau);
					mysql_close();
					break;
					return;
					exit(1);
				}
				else{
					sleep(3);
					#mysql_close($linkDB);
					#time_nanosleep(0, 500000000);
					#$linkDB = mysql_connect('localhost', 'habboshoot', 'UDVceeExq5yuq3aM')or die(mysql_error());
					#mysql_select_db('habboshoot')or die(mysql_error());
					//usleep(250000); // 0,25 secondes
				}
			}
		}
		
		return;

		// On fait une boucle qui va lister tous les messages
		while($donnees = mysql_fetch_array($requete))
		{
			if(!empty($_SESSION['last_id'])){
				
			}
			// On cacule la date //
			$time_differance = time() - $donnees['time'];
			
			if(time() - 60 < $donnees['time'])
			{
				$heure_lisible = date('s', $time_differance).' sec';
			}
			elseif(time() - 3600 < $donnees['time'])
			{
				$heure_lisible = date('i', $time_differance).' min';
			}
			else
			{
				$heure_lisible = date('H', $time_differance).' heures';
			}
		
		
			echo '<img src="img/avatarimage.gif" style="
			background-image: url(http://www.habbo.fr/habbo-imaging/avatarimage?user='.stripslashes($donnees['pseudo']).'&action=null&direction=2&head_direction=2&gesture=sml&size=s&img_format=gif);
			background-repeat: no-repeat;
			background-position:0px -8px;
			height:25px;
			margin-left: -6px;
			margin-bottom:-3px;" />
		
			<b><a href="?pg=fiches&habbo='.urlencode($donnees['pseudo']).'" style="color:black;" title="Clique pour voir son profil">'.$donnees['pseudo'].'</a> :</b><br/>
		
			<small>Y\'a '.$heure_lisible.'</small>
			<br />'.stripcslashes($donnees['message']);
			
			// S'il est admin ou arbitre, on met un lien pour supprimer le message
			if($_SESSION['grade'] == 'Admin' OR $_SESSION['grade'] == 'Arbitre')
			{
				echo ' <img src="img/close.gif" onclick="Chat.effacer(\''.$donnees['id'].'\');" alt="Effacer" title="Effacer" />';
			}
			
			echo '<br/><br/>';
		}
	
	exit(1); /* Tout c'est bien passé et y'a pas besoin de lire plus bas. */
	
	}
	
	elseif($_GET['action'] === 'ouvrir')
	{
		$requete = mysql_query('SELECT * FROM chat ORDER BY time DESC LIMIT 10')or die(mysql_error()); // On fait une requête pour voir la liste de tous les messages

		// On fait une boucle qui va lister tous les messages
		while($donnees = mysql_fetch_array($requete))
		{
			
			// On cacule la date //
			$time_differance = time() - $donnees['time'];
			
			if(time() - 60 < $donnees['time'])
			{
				$heure_lisible = date('s', $time_differance).' sec';
			}
			elseif(time() - 3600 < $donnees['time'])
			{
				$heure_lisible = date('i', $time_differance).' min';
			}
			else
			{
				$heure_lisible = date('H', $time_differance).' heures';
			}
			
		}
		
		echo '<img src="img/fermer_chat.gif" style="float:right; margin-right: 30px;" onClick="Chat.fermer()" /><font color="white">Patience.......<br />Faut que j\'te parle pour cette page</font>';
	exit(1); /* Tout c'est bien passé et y'a pas besoin de lire plus bas. */
	}

	// Si on veut effacer un message
	elseif($_GET['action'] === 'effacer' AND is_numeric($_GET['id']) AND ($_SESSION['grade'] == 'Arbitre' OR $_SESSION['grade'] == 'Admin'))
	{	
		mysql_query('DELETE FROM chat WHERE id = \''.mysql_real_escape_string($_GET['id']).'\'')or die(mysql_error());;
	}
	
	// Si on veut envoyer un message
	elseif($_POST['message'] !== '' AND $_SESSION['pseudo'] !== '')
	{

		$Requete = mysql_query('SELECT * FROM chat_chut WHERE pseudo = \''.$_SESSION['pseudo'].'\' AND time > "'.time().'"')or die(mysql_error()); // Requête qui vérifie s'il a la parole
		
		// S'il on lui donne la parole
		if(!mysql_num_rows($Requete))
		{
			
			// On veut réinitialiser le chat?
			if($_POST['message'] === ':clear' && ($_SESSION['grade'] == 'Arbitre' OR $_SESSION['grade'] == 'Admin'))
			{
				mysql_query('DELETE FROM chat')or die(mysql_error()); // Truncate et non Delete car sinon au bout d'un certain temps, les id des messages feront 6 chiffres :°
			}

			// On veut faire taire quelqu'un?
			elseif(preg_match('#:mute (.+)$#', $_POST['message']) AND ($_SESSION['grade'] == 'Arbitre' OR $_SESSION['grade'] == 'Admin'))
			{
				$pseudochut = preg_replace('#:mute (.+)#', '$1', $_POST['message']);
				$dans5min = time() + 5*60;
				mysql_query('INSERT INTO chat_chut VALUES (\'\', \''.mysql_escape_string(htmlspecialchars($pseudochut)).'\', '.$dans5min.')')or die(mysql_error());
			}
			
			// Il n'y a aucune commande spéciale : on envoie le message
			else
			{
				if(isset($_SESSION['pseudo'])){
					mysql_query('INSERT INTO chat VALUES(\'\', \''.$_SESSION['pseudo'].'\', \''.mysql_escape_string(htmlspecialchars($_POST['message'])).'\', "'.time().'")')or die(mysql_error());
				}
				else{
					echo 'deco';
				}
			}
		
		}
		
		// Si on lui a coupé la parole
		else
		{
			echo 'chut'; /* On dit à javascript d'afficher une alerte */
		}
	}
	
	// Il n'y a aucune action
	else
	{
		exit(0); // On quitte la page
	}

?>
