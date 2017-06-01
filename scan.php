<?php
include('pass.php');
// Ce fichier, une fois executé, permets de bannir les doubles comptes et de récessiter tout le monde. À faire à chaque inter-partie avec cron 

if($_POST['motdepasse'] === $mdpScanInterPartie){
    mysql_query("UPDATE membres SET pv = '2' , meutreparty = '0' WHERE pv != '3'") or die(mysql_error());
    mysql_query("UPDATE membres SET meutreparty = '0' WHERE pv = '3'") or die(mysql_error());
    
    $membres = mysql_query('SELECT * FROM membres WHERE  dateco > '.(time()-( 3600*24*14)).'')or die(mysql_error());
    while($membre = mysql_fetch_array($membres)){
        $req = mysql_query('SELECT COUNT(*) as nbrKill FROM screen WHERE killeur=\''.$membre['pseudo'].'\' AND valid=\'valide\' AND esquive=\'\'')or die(mysql_error());
        $donnees = mysql_fetch_array($req);
        $nbrKill = $donnees['nbrKill'];
        $req = mysql_query('SELECT COUNT(*) as nbrMort FROM screen WHERE victime=\''.$membre['pseudo'].'\' AND valid=\'valide\' AND esquive=\'\'')or die(mysql_error());
        $donnees = mysql_fetch_array($req);
        $nbrMort = $donnees['nbrMort'];
        
        $nbrMort++;
        $ratio = $nbrKill/$nbrMort;
        //echo $membre['pseudo'].' : '.$nbrKill.'/'.$nbrMort.' = '.$ratio.' \n';
        mysql_query('UPDATE membres SET ratio =\''.$ratio.'\' WHERE pseudo =\''.$membre['pseudo'].'\'')or die(mysql_error());
        
    }
    
    return;
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


    mysql_query("INSERT INTO ban VALUES('$pseudo', '$time', 'T\'as trop de clones. La limite imposé est de 2.<br/>Les clones détectés sont : $noms', '$ip')")or die(mysql_error());
    mysql_query('UPDATE membres SET ip=\'0.0.0.0\', pv = 3 WHERE id= \''.$donnees['id'].'\'')or die(mysql_error());
    }
    }
    $info = mysql_query('SELECT * FROM membres')or die(mysql_error());
    while($donnees = mysql_fetch_array($info))
    {
        $inf2 = mysql_query('SELECT * FROM screen WHERE killeur = \''.$donnees['pseudo'].'\' AND valid = \'valide\' ')or die(mysql_error());
        mysql_query('UPDATE membres SET meutre = '.mysql_num_rows($inf2).' WHERE pseudo=\''.$donnees['pseudo'].'\'')or die(mysql_error());
    }
}