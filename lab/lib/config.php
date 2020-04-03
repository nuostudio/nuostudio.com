<?php 
$bd_host = "localhost"; 
$bd_usuario = "nuostudi_jaicab"; 
$bd_password = "J4¡m€caballero"; 
$bd_base = "nuostudi_web"; 
$con = mysql_connect($bd_host, $bd_usuario, $bd_password); 
mysql_select_db($bd_base, $con); 
date_default_timezone_set("Europe/Madrid");