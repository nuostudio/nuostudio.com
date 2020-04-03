<?php
session_start();
if($_POST['url']){
	$_SESSION['proxy']['URL'] = $_POST['url'];	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Proxy | Nuostudio.com</title>
<link rel="stylesheet" media="all" type="text/css" href="proxy.css" />
<style type="text/css">
#urlBox{
	position:absolute;
	top:10px;
	right:10px;	
}
#urlBox a{
	font-size:14px;
	margin-left:5px;
	font-weight:normal;
}
#options{
	position:absolute;
	display:none;
	border:#E6F2FF solid 3px;
	background:#ffffff;
	color:#666666;
	width:480px;
	top:70px;
	right:72px;
	padding:10px;
}
</style>
<link rel="stylesheet" media="all" href="/forms.css" />
<script type="text/javascript" language="javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#website').load(function(){ 
		// you know what? fuck the system. I can get the url being browsed ;)
		$.post("process.php", { type:'getProxyUrl', ajax:'true' },
		  function(data){
				if(data.done == 'false'){
					if(data.msg.length > 0){
						alert(data.msg)
					}else{
						alert('Ha ocurrido un error. Intentalo mas tarde')
					}
				}else{
					$('#url').val(data.url);
				}
		  }, "json");
	});
	$('#opts').click(function(event){
		event.preventDefault();
		$('#options').toggle();
	});
	$('#user-agent').change(function(){
		$.post("process.php", { type:'setUserAgent', useragent:$(this).val(), ajax:'true' },
		  function(data){
				if(data.done == 'false'){
					if(data.msg.length > 0){
						alert(data.msg)
					}else{
						alert('Ha ocurrido un error. Intentalo mas tarde')
					}
				}else{
					alert('User Agent cambiado')
					$('#options').hide();
				}
		  }, "json");
	});
});
-->
</script>
</head>

<body>
<div id="header">
<h1>Proxy</h1>
<?php if($_SESSION['proxy']['URL']){ ?>
<div id="urlBox">
<form action="/proxy" method="post">
<input name="url" id="url" type="text" value="<?php echo $_GET['url']; ?>" /><input name="open" type="submit" value="Abrir" />
<br />
<a href="#" id="opts">Opciones del proxy</a>
</form>
</div>
<?php }?>
</div>
<div id="options">
<strong>Configuraci&oacute;n:</strong><br />
Cambiar User agent: <select name="user-agent" id="user-agent">
	<option value="CheviProxy (http://chevismo.com/proxy)">Chevismo</option>
<optgroup label="Bots">
    <option value="Googlebot/2.1 ( http://www.googlebot.com/bot.html)">Google Bot</option>
    <option value="Googlebot-Image/1.0 ( http://www.googlebot.com/bot.html)">Google Image</option>
    <option value="msnbot-Products/1.0 (+http://search.msn.com/msnbot.htm)">Windows Live</option>
    <option value="msnbot/2.0b (+http://search.msn.com/msnbot.htm)">Bing</option>
    <option value="Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)">Yahoo</option>
    <option value="Mozilla/2.0 (compatible; Ask Jeeves) ">Ask</option>
</optgroup>
<optgroup label="Navegadores">
    <option value="Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) Gecko/20100401 Firefox/4.0 (.NET CLR 3.5.30729)">Firefox 4.0</option>
    <option value="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1">Firefox 2.0</option>
    <option value="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13">Google Chrome</option>
    <option value="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)">IE 7</option>
    <option value="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)">IE 6</option>
    <option value="Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US) AppleWebKit/522.11 (KHTML, like Gecko) Safari/3.0.2">Safari </option>
    <option value="Opera/9.00 (Windows NT 5.1; U; en)">Opera</option>
</optgroup>
<optgroup label="Dispositivos">
	<option value="Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_1_3 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7D11 Safari/528.16">iPhone</option>
    <option value="BlackBerry9700/5.0.0.351 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/123">Blackberry</option>
    <option value="Mozilla/5.0 (Linux; U; Android 2.1; en-us; Nexus One Build/ERD62) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17">Nexus One</option>
    <option value="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; HTC_Touch_Diamond2_T5353; Windows Phone 6.5)">HTC Touch Diamond 2</option>
    <option value="HTC_Dream Mozilla/5.0 (Linux; U; Android 1.5; en-US; Build/CUPCAKE) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1">HTC Dream</option>
    <option value="Mozilla/4.0 (compatible; Linux 2.6.22) NetFront/3.4 Kindle/2.0 (screen 600x800)">Kindle</option>
    <option value="Mozilla/5.0 (Linux; U; Android 2.1-update1; en-US; Droid Build/ESE81) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17">Motorola Droid</option>
    <option value="Mozilla/4.0 (compatible; MSIE 5.0; Series80/2.0 Nokia9500/4.51 Profile/MIDP-2.0 Configuration/CLDC-1.1)">Nokia 9500</option>
    <option value="Mozilla/5.0 (SymbianOS/9.2; U; Series60/3.1 NokiaE90-1/07.24.0.3; Profile/MIDP-2.0 Configuration/CLDC-1.1 ) AppleWebKit/413 (KHTML, like Gecko) Safari/413 UP.Link/6.2.3.18.0">Nokia E90 Mocca</option>
    <option value="PalmCentro/v0001 Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; PalmSource/Palm-D061; Blazer/4.5) 16;320x320">Palm Centro</option>
    <option value="MobileExplorer/3.00 (Mozilla/1.22; compatible; MMEF300; Amstrad; Gamma)">Windows Mobile Explorer</option>
    <option value="TELECA-/2.0 (BREW 3.1.5; U; en-US;SAMSUNG; SPH-M800; Teleca/Q05A/INT) MMP/2.0 Profile/MIDP-2.1 Configuration/CLDC-1">Samsung Instinct</option>
    <option value="SonyEricssonK800i/R8BF Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1">Sony Eriksson K800i </option>
    <option value="Mozilla/5.0 (PLAYSTATION 3; 1.00)">Playstation 3</option>
    <option value="Mozilla/4.0 (PSP (PlayStation Portable); 2.00)">PSP (Playstation portable)</option>
    <option value="WM5 PIE">Pocket Internet Explorer</option>
</optgroup>
</select>
</div>
<?php if($_SESSION['proxy']['URL']){ ?>
<iframe src="proxySRC" width="100%" height="700" frameborder="1" id="website"></iframe>
<?php }else{ echo '<div align="center" style="margin-top:200px;"><h1>Web a abrir:</h1><form action="/proxy" method="post">
<input name="url" type="text" value="http://" /><input name="open" type="submit" value="Abrir" />
</form></div>'; } ?>
<div id="footer">&copy; <a href="/">nuostudio.com</a> (Todos los derechos reservados)</div>
</body>

</html>