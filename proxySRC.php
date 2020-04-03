<?php
session_start();
/*
 *	Proxy de Chevismo.com
 *  Desarrollado integramente por Chevi para Chevismo
 *  No usar con fines malos.. ;)
 */
 
// Inicio del Proxy:
if($_SESSION['proxy']['URL']) $_POST['url'] = $_SESSION['proxy']['URL'];
// Check URL and add www where necesary:
$weburl = $_POST['url'];
if(substr($weburl,0,6)=='http://'){
	$weburl = 'http://'.$weburl;
}
if(substr($weburl,0,10)=='http://www.'){
	$weburl = 'http://www.'.$weburl;
}
$_SESSION['proxy']['URL'] = $weburl;
// Setup del User Agent:
if(!isset($_SESSION['proxy']['agent'])){ $_SESSION['proxy']['agent'] = 'Nuostudio (http://nuostudio.com/proxy)'; }
// FUNCIONES:
function genLink($link,$host,$url){
	if($url['host']){
		$ret = $link;
	}else if(substr($link,0,1)=='/'){
		$ret = 'http://'.$host['host'].$link;
	}else{
		$ret = 'http://'.$host['host'].dirname($host['path']).'/'.$link;
	}
	return $ret;
}
function parseUrl($url){
		//print_r($url);
		$link = $url[3];
		$url = @parse_url($link);
		if(!$url){
			return $link;
		}
		$host = parse_url($_GET['url']);
		$imgs = array('jpg','jpeg','gif','png','tif','ico');
		$ext = substr(strrchr($url['path'], '.'), 1);
		//echo '|EXT: '.$ext.'|';
		if(in_array($ext,$imgs)){
			//this is an image:
			//echo '|Image|';
			$ret = genLink($link,$host,$url);
			return 'src="'.$ret.'"';
		}else if($ext=='css' || $ext=='js'){
			//echo '|CSS|';
			// Now check wether it is absolute, relative, or http:
			$ret = genLink($link,$host,$url);
		}else if(substr(strtolower($link),0,6)=='mailto'){
			//echo '|Email|';
			$ret = $link;
		}else{
			//echo '|Link|';
			if(strpos($link,'embed')){
				$ret = genLink($link,$host,$url);
			}else{
				if($url['host']){
					$ret = 'http://nuostudio.com/proxySRC?url='.$link;
				}else if(substr($link,0,1)=='/'){
					$ret = 'http://nuostudio.com/proxySRC?url=http://'.$host['host'].$link;
				}else{
					$ret = 'http://nuostudio.com/proxySRC?url=http://'.$host['host'].dirname($host['path']).'/'.$link;
				}
			}
		}
		//echo 'href="'.$ret.'"<br />';
		return 'href="'.$ret.'"';
}
function parseForms($url){
	$link = $url[3];
	$url = @parse_url($link);
	if(!$url){
		return $link;
	}
	$host = parse_url($_GET['url']);
	if($url['host']){
		$ret = 'http://nuostudio.com/proxySRC?url='.$link;
	}else if(substr($link,0,1)=='/'){
		$ret = 'http://nuostudio.com/proxySRC?url=http://'.$host['host'].$link;
	}else{
		$ret = 'http://nuostudio.com/proxySRC?url=http://'.$host['host'].dirname($host['path']).'/'.$link;
	}
	return 'action="'.$ret.'"';
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_URL,$weburl);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, false);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$html = curl_exec($ch);
if (!$html) {
	echo 'No he podido abrir la URL '.$weburl.' :(';
	echo "<br />cURL error number: " .curl_errno($ch);
	echo "<br />cURL error: " . curl_error($ch);
	exit;
}
curl_close($ch);
$url = parse_url($_GET['url']);
preg_match("@[a-zA-Z0-9]{1,}?@",$html,$m);
$html = var_dump($m);
$html = str_replace(array('erotica','sex','sexy','dick','cock'),array('e','s**','s***','d***','c***'),$html);
$html = str_replace('SWFObject("/','SWFObject("http://'.$url['host'].'/',$html);
$html = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', 'parseUrl', $html);
$html = preg_replace("@(href|src)[\s]*=[\s]*('|\")([^\"']+)*('|\")@", 'href="http://nuostudio.com"', $html);
$html = preg_replace_callback("@(action)[\s]*=[\s]*('|\")([^\"']+)*('|\")@", 'parseForms', $html);
echo $html;