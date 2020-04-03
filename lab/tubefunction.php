<?php 
session_start();
include('../lib/config.php');


//Extraemos id de videos de YouTube
function idYoutube($url){
	if(preg_match('/http:\/\/www\.youtube\.com\//', $url) || preg_match('/https:\/\/www\.youtube\.com\//', $url)){
		if(preg_match('/v=/', $url)){
			$part=explode('com/watch?v=',$url);
		}else{
			//url de insercion
			if(preg_match('/\/embed\//', $url))	$part=explode('/embed/',$url);
			//url de insercion antigua
			else if(preg_match('/\/v\//', $url))$part=explode('/v/',$url);
		}
	}else {
		//youtube acortado
		if(preg_match('/http:\/\/youtu\.be\//', $url)){
			$part=explode('.be/',$url);
		}else{
			//en caso de que no sea una url de youtube
			return false;
		}
	}
	//quitamos parametros de url
	if(preg_match('/&/', $part[1])){
		$part=explode('&',$part[1]);
		$ret=$part[0];
	}else $ret=$part[1];
	if(preg_match('/\?version/', $ret)){
		$ret=explode('?',$ret);
		$ret=$return[0];	
	}
	return $ret;
}


//AÑADIR VIDEO
if(isset($_POST['newurl'])){
	$url=idYoutube($_POST['newurl']);
	if(!$url || $url=='') $resp='URL no válida';
	else{
		$sql=mysql_query('SELECT id FROM nuo_tv WHERE url="'.$url.'" LIMIT 1');
		$video=mysql_fetch_array($sql);
		if($video){
			$resp='Este vídeo ya está en la base de datos.';
		}else{
			mysql_query('INSERT INTO  `nuostudi_web`.`nuo_tv` (
				`id` ,
				`url` ,
				`views` ,
				`likes` ,
				`dislikes` ,
				`timestamp`
				)
				VALUES (
				NULL ,  "'.$url.'",  0,  0,  0,  '.time().'
				);');
			$resp='Vídeo añadido! ;)';
		}
	}
	echo json_encode(array('msg'=>$resp));
}

//LIKEs
if(isset($_POST['likes']))mysql_query('UPDATE nuo_tv SET likes=likes+1 WHERE url="'.$_POST['likes'].'" LIMIT 1');
//DISLIKE
if(isset($_POST['dislikes']))mysql_query('UPDATE nuo_tv SET dislikes=dislikes+1 WHERE url="'.$_POST['dislikes'].'" LIMIT 1');

//BORRAR VIDEO
if(isset($_POST['del'])){
	mysql_query('DELETE FROM nuo_tv WHERE url="'.$_POST['del'].'" LIMIT 1');
}

//PEDIR VIDEO
if(isset($_POST['url'])){
	if(!isset($_SESSION['watched'])){
		//llamamos a cualquier video por ser el primero
		$video=mysql_query('SELECT * FROM nuo_tv ORDER BY RAND() LIMIT 1');
	}else{
		//excluimos las id delos videos ya vistos
		//ponemos la primera por defecto para excluir en AND si solo hay uno
		$watched_sql=' url!="'.$_SESSION['watched'][0].'"';
		for($i=1; $i<sizeof($_SESSION['watched']); $i++){
			$watched_sql.=' AND url!="'.$_SESSION['watched'][$i].'"';
		}
		$video=mysql_query('SELECT * FROM nuo_tv WHERE '.$watched_sql.' ORDER BY RAND() LIMIT 1');
	}
	$max_query=mysql_query('SELECT MAX(id) AS max FROM nuo_tv LIMIT 1');
	$max=mysql_fetch_array($max_query);
	if(sizeof($_SESSION['watched'])>$max['max']) session_unset($_SESSION['watched']); 
	
	//registramos VISTO en DB y en SESSION
	$_SESSION['watched'][sizeof($_SESSION['watched'])+1]=$_POST['url'];
	mysql_query('UPDATE nuo_tv SET views=views+1 WHERE url="'.$_POST['url'].'" LIMIT 1');
	
	
	//PASO DE EXCEPCIONES
	$video=mysql_query('SELECT * FROM nuo_tv ORDER BY RAND() LIMIT 1');
	$data=mysql_fetch_array($video);
	
	echo json_encode(array('code'=>$data['url'],'likes'=>$data['likes'],'dislikes'=>$data['dislikes']));
}