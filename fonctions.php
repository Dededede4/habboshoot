<?php

/*
 * Fonction qui valide un kill, le refuse, ou l'annule.
 * 
 * La partie $jujement doit être égale à "oui" , "non" ou "annuler"
 * Retourne 1 = Okais. 2 = Kill déjà validé 0 = Erreur critique
 */

function arbitre_kill($id_du_kill , $jujement, $pseudo){
	$pagekillid = mysql_real_escape_string(htmlspecialchars($id_du_kill));
	$result = mysql_query("SELECT * FROM screen WHERE ID = '$pagekillid'");
	$donnees = mysql_fetch_array($result);
	if($donnees['valid'] === 'none' OR $jujement === 'annuler')
	{
		if($jujement === 'oui')
		{
			$touche = false;
			$pagekillid = $id_du_kill;
			if(is_numeric($donnees['esquive']) && $donnees['esquive'] > 0){
				if($donnees['esquive'] >= mt_rand(0, 100)){
					mysql_query("UPDATE screen SET esquive='esquivé' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
					mysql_query("UPDATE membres SET pv = 2, esquive=0 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
					$touche = true;
				}
				else{
					mysql_query("UPDATE screen SET esquive='touché' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
					mysql_query("UPDATE membres SET esquive=0 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
				}
			}
			if(!$touche){
				mysql_query("UPDATE membres SET pv = 0 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
				mysql_query("UPDATE membres SET cash = cash + ".$donnees['gain']." , meutre = meutre +1 , meutreparty = meutreparty + 1 WHERE pseudo='".$donnees['killeur']."'") or die(mysql_error());
			}
			mysql_query("UPDATE screen SET valid = 'valide', valideur = '$pseudo' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
			$return = 1;
		}
		elseif($jujement === 'non')
		{
			mysql_query("UPDATE membres SET pv = 2 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
			mysql_query("UPDATE screen SET valid = 'pas valide', valideur = '$pseudo' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
			mysql_query("UPDATE miseprix SET zigouiller = 'non' WHERE zigouiller='".$donnees['ID']."'") or die(mysql_error());
			$return = 1;
		}
		elseif($jujement === 'annuler')
		{
			$pagekillid = mysql_real_escape_string(htmlspecialchars($id_du_kill));
			$result = mysql_query("SELECT * FROM screen WHERE ID = '$pagekillid'");
			$donnees = mysql_fetch_array($result);
			if($donnees['valid'] == "valide"){
				if($donnees['esquive'] != '0' AND $donnees['esquive'] != ''){
					mysql_query("UPDATE membres SET esquive=33 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
					mysql_query("UPDATE screen SET esquive='33' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
				}
				
				mysql_query("UPDATE membres SET pv = 2 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
				mysql_query("UPDATE screen SET valid = 'pas valide', valideur = '$pseudo' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
				mysql_query("UPDATE membres SET cash = cash - ".$donnees['gain']." , meutre = meutre - 1 , meutreparty = meutreparty - 1 WHERE pseudo='".$donnees['killeur']."'") or die(mysql_error());
				mysql_query("UPDATE miseprix SET zigouiller = 'non' WHERE zigouiller = '".$donnees['ID']."'") or die(mysql_error());
			
				
				$return = 1;
			}
			elseif($donnees['valid'] == "pas valide"){
				
				$touche = false;
				$pagekillid = $id_du_kill;
				if(is_numeric($donnees['esquive']) && $donnees['esquive'] > 0){
					if($donnees['esquive'] >= mt_rand(0, 100)){
						mysql_query("UPDATE screen SET esquive='esquivé' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
						mysql_query("UPDATE membres SET pv = 2 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
						$touche = true;
					}
					else{
						mysql_query("UPDATE screen SET esquive='touché' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
					}
					mysql_query("UPDATE membres SET esquive=0 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
				}
				if(!$touche){
					mysql_query("UPDATE membres SET pv = 0 WHERE pseudo='".$donnees['victime']."'") or die(mysql_error());
					mysql_query("UPDATE membres SET cash = cash + ".$donnees['gain']." , meutre = meutre +1 , meutreparty = meutreparty + 1 WHERE pseudo='".$donnees['killeur']."'") or die(mysql_error());
					mysql_query("UPDATE miseprix SET zigouiller = '".$donnees['ID']."' WHERE tete='".$donnees['victime']."' AND time <= ".$donnees['time']." AND zigouiller = 'non' ") or die(mysql_error());
				}
				mysql_query("UPDATE screen SET valid = 'valide', valideur = '$pseudo' WHERE ID='".$donnees['ID']."'") or die(mysql_error());
				
				$return = 1;
			}
		}
		else
		{
			echo 'ERREUR : La 2eme valeur de la fonction arbitre_kill() doit être égale à « oui » ou « non » ou « annuler »';
			$return = 0;
		}
	}
	else
	{
		$return = 2;
	}

return $return; 
}

/*
 *Fonction qui retourne le niveau à partir d'un nombre de kill. 
 * Serial killer 51 - 100
 * Terminator 101 - 201
 * Massacreur 201 - 350
 * Demon 351 - 499
 * Diable 500
 */
 
 function getlvl($meutresniveau){
	 if ( $meutresniveau <= 2 AND $meutresniveau >= 0 ){
		$lvl = 1;
	}
	elseif ( $meutresniveau >= 3 AND $meutresniveau <= 7 ){
		$lvl = 2;
	}
	elseif ( $meutresniveau >= 8 AND $meutresniveau <= 15 ){
		$lvl = 3;
	}
	elseif ( $meutresniveau >= 16 AND $meutresniveau <= 25 ){
		$lvl = 4;
	}
	elseif ( $meutresniveau >= 26 AND $meutresniveau <= 50 ){
		$lvl = 5;
	}
	elseif ( $meutresniveau >= 51 AND $meutresniveau <= 100 ){
		$lvl = 6;
	}
	elseif ( $meutresniveau >= 101 AND $meutresniveau <= 201 ){
		$lvl = 7;
	}
	elseif ( $meutresniveau >= 201 AND $meutresniveau <= 350 ){
		$lvl = 8;
	}
	elseif ( $meutresniveau >= 351 AND $meutresniveau <= 499 ){
		$lvl = 9;
	}
	elseif ($meutresniveau >= 500 AND $meutresniveau <= 999){
		$lvl = 10;
	}
	elseif ($meutresniveau >= 1000 ){
		$lvl = 11;
	}
	 return $lvl;
 }
 
function gradelvl($meutresniveau)
{
	$lvl = getlvl($meutresniveau);
	if ($lvl === 1){
		$return = 'Apprenti';
	}
	elseif ($lvl === 2 ){
		$return = 'Casseur';
	}
	elseif ($lvl === 3  ){
		$return = 'Délinquant';
	}
	elseif ($lvl === 4){
		$return = 'Méchant';
	}
	elseif ($lvl === 5 ){
		$return = 'Terroriste';
	}
	elseif ($lvl === 6  ){
		$return = 'Serial killer';
	}
	elseif ($lvl === 7  ){
		$return = 'Terminator';
	}
	elseif ($lvl === 8 ){
		$return = 'Dictateur';
	}
	elseif ( $lvl === 9 ){
		$return = 'Démon';
	}
	elseif ($lvl === 10){
		$return = 'Diable';
	}
	elseif ($lvl === 11 ){
		$return = 'Chuck Norris';
	}

	return $return;
}


function bbcode($text)
{
    //$text = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $text);
	$text = preg_replace('`\[url=([http://].+?)](.+?)\[/url]`si','<a href="$1" target="_blank" title="$1">$2</a>',$text); 
	$text = preg_replace('`\[url=(.+?)](.+?)\[/url]`si','<a href="$1" target="_blank"  title="$1">$2</a>',$text); 
	$text = preg_replace('`\[url]([http://].+?)\[/url]`si','<a href="$1" target="_blank" title="$1">$1</a>',$text); 
	$text = preg_replace('`\[url](.+?)\[/url]`si','<a href="$1" target="_blank" title="$1">$1</a>',$text); 
	
	$text = preg_replace("/\[h1\](.+?)\[\/h1\]/", "<h1 style=\"display: inline\">$1</h1>", $text);
	$text = preg_replace("/\[h2\](.+?)\[\/h2\]/", "<h2 style=\"display: inline\">$1</h2>", $text);
	$text = preg_replace("/\[h3\](.+?)\[\/h3\]/", "<h3 style=\"display: inline\">$1</h3>", $text);
	$text = preg_replace("/\[i\](.+?)\[\/i\]/", "<i>$1</i>", $text);
    $text = preg_replace("/\[u\](.+?)\[\/u\]/", "<u>$1</u>", $text);
	$text = preg_replace("/\[img\](.+?)\[\/img\]/", "<img src=\"$1\" style=\"border:0; max-width: 410px;\">", $text);
    $text = preg_replace("/\[color=(#[0-9A-F]{6}|red|green|blue|yellow|purple|olive)\](.+?)\[\/color\]/", "<font color=$1>$2</font>", $text);
	//$text = preg_replace("#([.^\S]{40})#U", "$1<br/>", $text);
	$text = preg_replace("/\[center\](.+?)\[\/center\]/", "<center>$1</center>", $text);
	$text = preg_replace("/\[droite\](.+?)\[\/droite\]/", "<div style=\"text-align: right;\">$1</div>", $text);
	 
    $text = str_replace(' :)', ' <img src ="img/smiley/sourire.gif" border="0" />', $text);
	$text = str_replace(' ;)', ' <img src ="img/smiley/clin.gif" border="0" />', $text);
	$text = str_replace(' ^^', ' <img src ="img/smiley/frime.gif" border="0" />', $text);
	$text = str_replace(' U_u', ' <img src ="img/smiley/U_u.gif" border="0" />', $text);
	$text = str_replace(' &gt;:(', ' <img src ="img/smiley/flechepoinfleche.gif" border="0" />', $text);
	$text = str_replace(' :/', ' <img src ="img/smiley/2slach.gif" border="0" />', $text);
	$text = str_replace(' O.O', ' <img src ="img/smiley/2O.gif" border="0" />', $text);
	$text = str_replace(' :D', ' <img src ="img/smiley/2D.gif" border="0" />', $text);
	$text = str_replace(' :(', ' <img src ="img/smiley/2(.gif" border="0" />', $text);
	
	global $motkill;
	global $pseudo;
	
	$text = str_replace("*KILL*", '<span title="Il a utilisé *KILL*, remplacé par le mot kill du moment" style="text-decoration: underline;">'.trim($motkill).'</span>', $text);
	
	$text = str_replace("*TOI*", '<span title="Il a utilisé *TOI*, remplacé par le pseudo du lecteur" style="text-decoration: underline;">'.$pseudo.'</span>', $text);
	$text = str_replace("*DATE*", '<span title="Il a utilisé *DATE*, remplacé par la date" style="text-decoration: underline;">'.date('d/m/Y à H:i', time()).'</span>', $text);
	
    return $text;
}

/*
 * Fonction qui fait les parties
 */

function partie()
{
	include('motkill.php'); // Contien la variable $motkillall avec tout les mots kill.

	$array_motkill = explode("\n", $motkillall);
	
	/* Si $motkill es  vide, la partie est considérée comme en pause. Sinon, y'a le motkill sous forme : *MOTKILL* */

	if(date('w') == 5 OR date('w') == 1) // Les pauses
	{
		$motkill = '';
	}
	else
	{
		// Comme il y 2 partie par semaines, j'ai fait ça pour éviter que les deux parties aient le même mot kill.
		if( date('w') == 2 OR date('w') == 3 OR date('w') == 4) 
		{
			$motkill = $array_motkill[intval(date('W')) +1];
		}
		else
		{
			$nombre = intval(date('W'))*2;
			$motkill = $array_motkill[$nombre];
		}
	}
	
	return $motkill;
}

/*
 * Affiche le tableau des meurtres.
 * $fonction_killeur, $fonction_victime peuvent être un jocker. ( * )
 * exemple : tableau_kill('Dededede4', '*', 100); Affichera tout les kill de dededede4 et chaque page comprendra un liste de 100 meurtres.
 * */
function tableau_kill($killeur, $victime, $nombreDeNewsParPage)
{		
	if(isset($_GET['page']))
	{
		$page = intval($_GET['page']);
	}
	else
	{
		$page = 1;
	}
	
	$premiereNewsAafficher = ($page - 1) * $nombreDeNewsParPage;
	
	// $fonction_killeur, $fonction_victime peuvent être un jocker. ( * )
	if( $killeur === '*' AND $victime !== '*')
	{
		$result = mysql_query('SELECT * FROM screen WHERE victime = \''.$victime.'\' ORDER BY time DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'');
	}
	elseif( $killeur !== '*' AND $victime === '*')
	{
		$result = mysql_query('SELECT * FROM screen WHERE killeur = \''.$killeur.'\' ORDER BY time DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'');
	}
	elseif( $killeur !== '*' AND $victime !== '*')
	{
		$result = mysql_query('SELECT * FROM screen WHERE killeur = \''.$killeur.'\' AND victime = \''.$victime.'\' ORDER BY time DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'');
	}
	else
	{
		$result = mysql_query('SELECT * FROM screen ORDER BY time DESC LIMIT '.$premiereNewsAafficher.', '.$nombreDeNewsParPage.'');
	}
	
	$totalDesNews = mysql_num_rows($result);
	$nombreDePages  = ceil($totalDesNews / $nombreDeNewsParPage);
	
	if($_GET['page'] > 1)
	{
		$numeroP = $_GET['page'] -1;
			
		echo '<a href="index.php?pg='.$_GET['pg'].'&amp;voir='.$_GET['voir'].'&amp;pseudo='.$_GET['pseudo'].'&amp;page='.$numeroP.'">Page précédente</a>';
	}
	else
	{
		$_GET['page'] = 1;
	}
			
	$numeroS = $_GET['page'] +1;
	if($totalDesNews)
	{
		echo '<div style ="float:right"><a href="index.php?pg='.$_GET['pg'].'&amp;voir='.$_GET['voir'].'&amp;pseudo='.$_GET['pseudo'].'&amp;page='.$numeroS.'">Page suivante</a></div>';
	}
	
	echo '<center><h2>PAGE '.htmlspecialchars($_GET['page']).'</h2></center>';
	
	/*
	 * On cherches l'ID qui pourra être voté.
	 * Ça doit être le plus vieux des kills posté en attente de validation.
	 */
		
	if(mysql_num_rows($result) > 0)
	{
		?>
		<div style="background-color: rgb(65, 65, 65); width : 131px; height : 15px; display:inline; margin-right:5px; float:left; padding:2px; text-align: center;">Killeur</div>
		<div style="background-color: rgb(65, 65, 65); width : 131px; height : 15px; display:inline; margin-right:5px; float:left; padding:2px; text-align: center;">Victime</div>
		<div style="background-color: rgb(65, 65, 65); width : 131px; height : 15px; display:inline; margin-right:5px; float:left; padding:2px; text-align: center;">Arbitre</div>
		<div style="background-color: rgb(65, 65, 65); width : 140px; height : 15px; display:inline; margin-right:5px; float:left; padding:2px; text-align: center;">Infos</div>

		<?php

		while($donnees = mysql_fetch_array($result))
		{ 
			echo '<div style="background-color: rgb(65, 65, 65); width : 560px; height : 15px; display:inline; margin-top:5px; margin-right:5px; float:left; padding:2px; font-style: italic;">';
	
			if(time() - 60 < $donnees['time'])
			{
				$time = time() - $donnees['time'];
				echo 'Il y a '.date('s', $time).' secondes';
			}
			elseif(time() - 3600 < $donnees['time'])
			{
				$time = time() - $donnees['time'];
				echo 'Il y a '.date('i', $time).' minutes';
			}
			elseif( date('d-m-Y') === date('d-m-Y',$donnees['time']))
			{
				echo 'À '.date('H:i', $donnees['time']);
			}
			else
			{
				echo 'Le '.date('d/m/Y à H:i', $donnees['time']);
			}
			
			echo ', '.$donnees['gain'].' pièces en jeu. <div style="float:right;"><a href="index.php?pg=voirkill&pagekillid='.$donnees['ID'].'">Clique pour plus d\'info.</a></div></div>';
			
			?>
	
			<div style="background-color: rgb(65, 65, 65); width : 131px; height : 30px; display:inline; margin-right:5px; margin-top:1px; float:left; padding:2px; text-align: center;">
			<?php echo '<a href="index.php?pg=fiches&habbo='.$donnees['killeur'].'">'.$donnees['killeur'].'</a>'; ?></div>
			<div style="background-color: rgb(65, 65, 65); width : 131px; height : 30px; display:inline; margin-right:5px; margin-top:1px; float:left; padding:2px; text-align: center;">
			<?php echo '<a href="index.php?pg=fiches&habbo='.$donnees['victime'].'">'.$donnees['victime'].'</a>'; ?></div>
			<div style="background-color: rgb(65, 65, 65); width : 131px; height : 30px; display:inline; margin-right:5px; margin-top:1px; float:left; padding:2px; text-align: center;">
			<?php
			
			if( $donnees['valideur'] === 'La communauté')
			{
				echo 'La communauté';
			}
			elseif( $donnees['valideur'] === '')
			{
				echo 'Aucun, pour l\'instant';
			}
			else
			{
				echo '<a href="index.php?pg=fiches&habbo='.$donnees['valideur'].'">'.$donnees['valideur'].'</a>'; 
			}
			
			?></div>
			
			<div style="background-color: rgb(65, 65, 65); width : 140px; height : 30px; display:inline; margin-right:5px; margin-top:1px; float:left; padding:2px; text-align: center;">
			
			<?php
	
			if($donnees['cagoule'] == 'oui')
			{
				echo ' [Cagoule]';
			}
	
			if($donnees['giletparballe'] == 'Oui')
			{
				echo ' [Gilet]';
			}
	
			if($donnees['couteau'] == 'oui')
			{
				echo ' [Couteau]';
			}
			if($donnees['esquive'] == 'esquivé')
			{
				echo ' [Esquivé]';
			}

			if($donnees['valid'] == 'valide')
			{
				echo ' [Valide]';
			}
			elseif($donnees['valid'] == 'pas valide')
			{
				echo ' [Non valide]';
			}
			elseif($donnees['valid'] == 'none')
			{
				echo ' <div style="color: rgb(155, 0, 0);">[Attente]</div>';
			}
			
			echo '</div>';

		}
		
	echo '<div style="display:block; clear:left"></div>';
	
	}
}

function genererMDP ($longueur = 8){
    // initialiser la variable $mdp
    $mdp = "";
 
    // Définir tout les caractères possibles dans le mot de passe,
    // Il est possible de rajouter des voyelles ou bien des caractères spéciaux
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
 
    // obtenir le nombre de caractères dans la chaîne précédente
    // cette valeur sera utilisé plus tard
    $longueurMax = strlen($possible);
 
    if ($longueur > $longueurMax) {
        $longueur = $longueurMax;
    }
 
    // initialiser le compteur
    $i = 0;
 
    // ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
    while ($i < $longueur) {
        // prendre un caractère aléatoire
        $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
 
        // vérifier si le caractère est déjà utilisé dans $mdp
        if (!strstr($mdp, $caractere)) {
            // Si non, ajouter le caractère à $mdp et augmenter le compteur
            $mdp .= $caractere;
            $i++;
        }
    }
 
    // retourner le résultat final
    return $mdp;
}
function getKeyTime($id, $time = false){
	include('pass.php');
	if(!$time){
		$time = time();
	}
	$chaine = $id.'|'.(floor($time/3600)).'|'.$clefTime;
	//echo $chaine.'<br/>';
	return substr(sha1($chaine), 0, 9);	
}
?>
