<?php 
session_start();
include('lib/config.php');
$video=mysql_query('SELECT COUNT(id) as num FROM nuo_tv');
$total=mysql_fetch_array($video);

$video=mysql_query('SELECT * FROM nuo_tv ORDER BY RAND() LIMIT 1');
$data=mysql_fetch_array($video);

$video=mysql_query('SELECT * FROM nuo_tv WHERE url!="'.$data['url'].'" ORDER BY RAND() LIMIT 1');
$data_new=mysql_fetch_array($video);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../img/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>nuoTV - Pure YouTube</title>
<style media="all">
body{
	background:#000;
	color:#fff;
	margin:0;
	padding:0;
}
#bar{
	background:url(../img/tube_bg.gif) top left repeat-x;
	margin:0;
	padding:0;
	height:50px;
	width:100%;
	display:block;
}
#yt_container{
	min-width:960px;
	margin:0 auto;
	padding:0;
}
#menu{
	max-height:50px;
	width:960px;
	margin:0 auto;
	padding:0 0;
	text-align:center;
}
#menu #logo{
	float:left;
	padding-top:5px;
	padding-right:20px;
	cursor:pointer;
}
#menu a{
	line-height: 50px;
	font-size: 18px;
	color: white;
	text-decoration: none;
	text-shadow:2px 1px 0 #222;
	margin: 10px;
	padding: 5px 15px;
	font-family:Arial, Helvetica, sans-serif;
	border-radius:10px;
}
#menu a img{
	position:relative;
	top:3px;
}
#menu #like{
	margin-right:0;
	border-radius:10px 0 0 10px;
}
#menu #dislike{
	margin-left:0;
	border-radius:0 10px 10px 0;
	position: relative;
	left: -3px;
}
#menu a:hover{
	background-color:#666;
}
	
</style>
<script type="text/javascript" src="lib/swfobject2.2.js"></script> 
<script type="text/javascript" language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script type="text/javascript">
<!--
//########################
var mode = 1;
//VIDEO ACTUAL
var id = '<?php echo $data['url']; ?>';
var likes = parseInt(<?php echo $data['likes']; ?>);
var dislikes = parseInt(<?php echo $data['dislikes']; ?>);
//VIDEO ANTERIOR
var old = '<?php echo $data['url']; ?>';
var likes_old = parseInt(<?php echo $data['likes']; ?>);
var dislikes_old = parseInt(<?php echo $data['dislikes']; ?>);
//VIDEO NUEVO
var new_id = '<?php echo $data_new['url']; ?>';
var likes_new = parseInt(<?php echo $data_new['likes']; ?>);
var dislikes_new = parseInt(<?php echo $data_new['dislikes']; ?>);
//########################

function info(){
	player.pauseVideo();
	alert('Bienvenido a nuoTV v1.0\nUn experimento de nuostudio.com\n\nEn este experimento intentamos captar la esencia de la televisión basándonos en vídeos de YouTube. Entretenimiento continuo y aleatorio, eso es lo que buscamos. De modo que si sabes de algún video que pueda contribuir a la causa, no dudes en añadirlo.  ;)\n\nActualmente tenemos <?php echo $total['num']; ?> vídeos en la base de datos.\n\nNormas:\nAñade vídeos con movimiento. Evita los vídeos con imágenes estáticas. \n- No se borrarán videos de forma particular, en caso de que el video resulte ofensivo, debe reclamarse a YouTube en cualquier caso. Solo se borrarán basándose en los votos negativos frente a los positivos.\n- Cuando ves un video, se puede recuperar pulsando Anterior. Despues de eso tendrás que esperar a que vuelva a salir (si es que lo hace).\n\n¡Esperamos que os guste!\nFdo. Jaime Caballero');
	player.playVideo();
}
function setLike(){
	if(mode==0){
		document.getElementById("like_num").innerHTML=likes_old;
		document.getElementById("dislike_num").innerHTML=dislikes_old;	
	}if(mode==1){
		document.getElementById("like_num").innerHTML=likes;
		document.getElementById("dislike_num").innerHTML=dislikes;	
	}
}
function likeVideo(){
	$.post("tubefunction.php", { likes: id },function(){
		if(mode==1)likes++;
		else likes_old++;
		setLike()
	});
}
function dislikeVideo(){
	$.post("tubefunction.php", { dislikes: id },function(){
		if(mode==1) dislikes++;
		else dislike_old++;
		setLike()
	});
}

//funciones de reproduccion
function play(url){
	player.loadVideoById(url);
	player.playVideo()
	setLike()
}
function prevPlay(){
	mode = 0;
	play(old)
	setLike()
}
	
function error(){
	if(mode==1) vid = id;
	else vid = old;
	$.post("tubefunction.php", { del: vid });
	update()
}
function update(){	
	mode = 1;	
	play(new_id)
	$.post("tubefunction.php", { url: old },function(data){
		if(data.code==null) alert('Error')
		else{
			if(data.code==id || data.code==old) update();
			else{		
				//guardamos anteriores
				old = id
				likes_old = likes
				dislikes_old = dislikes
				//guardamos actuales
				id = new_id;
				likes = likes_new;
				dislikes = dislikes_new;
				//guardamos nuevos
				new_id = data.code;
				likes_new = parseInt(data.likes);
				dislikes_new = parseInt(data.dislikes);
				
				setLike()
			}
		}
	},'json');
};
function autoUp(){
	if(player.getPlayerState()==0) update();
}
function getScreen(){	
	//height
	if (window.innerHeight) height = window.innerHeight;
	else height = document.body.clientHeight;
	//width
	if (window.innerWidth) width = window.innerWidth;
	else width = document.body.clientWidth;
	
	//solo debemos ajustar la altura
	height=height-50;
};

//funciones de botones
function addVideo(){
	if(player.getPlayerState()==2) paused = true
	else {
		paused = false 
		player.pauseVideo()
	}
	var newtube=prompt('Añadir vídeo:','Escriba aquí la URL de YouTube...');
	if(newtube)	$.post("tubefunction.php", { newurl: newtube },function(data){
		alert(data.msg);
		if(!paused) player.playVideo()
	},'json');
	else if(!paused) player.playVideo()
}
//funciones de configuracion
function setScreen(){
	getScreen()
	player.setSize(width,height);
	player.width=width;
	player.height=height;
}
getScreen();
function onYouTubePlayerReady(playerId) {
	player = document.getElementById("myytplayer");
	update()
	player.addEventListener('onStateChange', 'autoUp()');
	player.addEventListener('onError', 'error()');
	
}
$(window).resize(function(){setScreen();});
-->
</script>
</head>

<body>
<div id="ytapiplayer">
  Necesitas Flash Player 8+ y Javascript para que funcione.
</div>

<script type="text/javascript">

    var params = { allowScriptAccess: "always" };
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/v/<?php echo $data['url']; ?>?version=3&hl=es_ES&feature=player_embedded&iv_load_policy=3&modestbranding=1&controls=0&enablejsapi=1&playerapiid=ytplayer", "ytapiplayer", width, height, "8", null, null, params, atts);
</script>

<div id="bar"><div id="menu">
	<img id="logo" border="0" onclick="info()" src="img/tube_logo.png" />
    <a href="#" onclick="likeVideo()" title="Dar voto positivo" id="like"><img src="img/tube_good.png" width="20" height="20" /> <span id="like_num"><?php echo $data['likes']; ?></span></a>
    <a href="#" onclick="dislikeVideo()" title="Dar voto negativo" id="dislike"><img src="img/tube_bad.png" width="20" height="20" /> <span id="dislike_num"><?php echo $data['dislikes']; ?></span></a>
    <a href="#" onclick="prevPlay()">&laquo; Anterior</a>
	<a href="#" onclick="update()">Saltar &raquo;</a>
    <a href="#" onclick="addVideo()">A&ntilde;adir video</a>
    <a href="#" onclick="info()">Info</a>
</div></div>
</body>
</html>