function Ajax()
{
	var ajaxvar=null;
	if(window.XMLHttpRequest) ajaxvar=new XMLHttpRequest();
	else if(window.ActiveXObject)
	{
		try {
			ajaxvar=new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			ajaxvar=new ActiveXObject("Microsoft.XMLHTTP");}
		}else{
			alert("Pour pouvoir utiliser certaines choses telles que le chat, tu dois changer de navigateur car il n'accepte pas les object XMLHttpRequest.");
			ajaxvar=false;
		}
	return ajaxvar;
}

function escape_ajax(contents)
{
	contents = contents.replace(/\+/g, '%2B');
	contents = contents.replace(/&/g, '%26');
	
	return contents;
}


var Chat = {
	lastID : 1,
	rafraichir: function() {
		var ajaxvar = new Ajax();
		
		ajaxvar.onreadystatechange = function() {
			if(ajaxvar.readyState == 4 && ajaxvar.status == 200){
				var data = JSON.parse(ajaxvar.responseText);
				var html = '';
				Chat.lastID = data[0].id;
				for(var i in data){
					html += '<span id="message_'+data[i].id+'"><img src="img/avatarimage.gif" style=" \
					background-image: url(http://www.habbo.fr/habbo-imaging/avatarimage?user='+data[i].pseudo+'&action=null&direction=2&head_direction=2&gesture=sml&size=s&img_format=gif); \
					background-repeat: no-repeat; \
					background-position:0px -8px; \
					height:25px; \
					margin-left: -6px; \
					margin-bottom:-3px;" />\
					<b><a href="?pg=fiches&habbo='+data[i].pseudo+'" style="color:black;" title="Clique pour voir son profil">'+data[i].pseudo+'</a> :</b><br/>\
					<small name="duree" value="'+data[i].time+'"></small>\
					<br />'+data[i].message;
					if(data[i].canDel){
						html +=' <img src="img/close.gif" onclick="Chat.effacer(\''+data[i].id+'\');" alt="Effacer" title="Effacer" />';
					}
					html +='<br/><br/></span>';
				}
				document.getElementById("zonechatmillieu").innerHTML = html+document.getElementById("zonechatmillieu").innerHTML;
				Chat.duree();
				Chat.rafraichir();
			}
		};
		var stop = setInterval(function(){
			if(ajaxvar.readyState === 0){
				ajaxvar.open("GET", "chat_actions.php?action=rafraichir&lastID="+Chat.lastID, true);
				ajaxvar.send(null);
			}
			else{
				stop.clearInterval();
			}
		}, 500);
	},
	
	duree : function(interval){
		  // alert(document.getElementById('message_281').getElementsByName("duree").innerHTML);
		  var nodes = document.getElementsByName("duree");
		  if(interval){
			setInterval(Chat.duree, 1000);
		 }
		  for(var i in nodes){
			if(typeof nodes[i].getAttribute === 'undefined'){
				return;
			}
			var diff = timestamp - nodes[i].getAttribute('value');
			if(diff < 60){
				 nodes[i].innerHTML = 'Y\'a '+diff+' secondes';
			}
			else if(diff < 3600){
				nodes[i].innerHTML = 'Y\'a '+ parseInt(diff*(1/60))+' minutes';
			}
			else{
				nodes[i].innerHTML = 'Y\'a '+ parseInt(diff*(1/3600))+' heures';
			}
		}
	},
	
	envoyer: function() {
		var ajaxvar = new Ajax();
		ajaxvar.onreadystatechange = function() {
			if(ajaxvar.readyState == 4 && ajaxvar.status == 200) {
				if(ajaxvar.responseText == "chut") alert("Un admin t'as bloqué la parole pour les 5 prochaines minutes.");
				if(ajaxvar.responseText == "deco") alert("T'es déconnecté du chat.");
				//Chat.rafraichir();
			}
		};
		ajaxvar.open("POST", "chat_actions.php", true);
		ajaxvar.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-1");
		message = document.getElementById("message_du_chat").value;
		ajaxvar.send("message="+escape_ajax(message));
		document.getElementById("message_du_chat").value = "";
	},

	effacer: function(iddelete) {
		var ajaxvar = new Ajax();
		ajaxvar.onreadystatechange = function() {
			//Chat.rafraichir();
		};
		ajaxvar.open("GET", "chat_actions.php?action=effacer&id="+iddelete, true);
		ajaxvar.send(null);
	},
	
	fenetre: function() {
		var ajaxvar = new Ajax();
		ajaxvar.onreadystatechange = function() {
			if(ajaxvar.readyState == 4 && ajaxvar.status == 200) document.getElementById("chat_etendu").innerHTML = ajaxvar.responseText;
		};
		ajaxvar.open("GET", "chat_actions.php?action=ouvrir", true);
		ajaxvar.send(null);
		 window.setTimeout(Chat.fenetre, 5000);
	},
	
	ouvrir: function() {
		document.getElementById("chat_etendu").style.display = 'block';
		Chat.fenetre();
	},
	
	fermer: function() {
		document.getElementById("chat_etendu").style.display = 'none';
	}
};

Chat.rafraichir();
Chat.duree(true);
// 