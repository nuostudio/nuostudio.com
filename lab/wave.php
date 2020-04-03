<style type="text/css">
#fps{
	font-family:"Courier New", Courier, monospace;
	font-size:13px;
	position:absolute;
	top:3px;
	right:3px;	
}
#colors{
	position:absolute;
	top:100px;
	right:10px;
	z-index:999;
	text-align:center;
}
#colors ul{
	list-style:none;
	margin:0;
	padding:0;	
}
#colors ul li{
	display:inline-block;
}
#colors ul li a{
	display:block;
	width:20px;
	height:20px;
	border:#ccc solid 1px;
	text-decoration:none;
	border-radius:10px;
}
#colors ul li a:hover{ border:#fff solid 1px; }
#color1{ background:#000; }
</style>
<link rel="stylesheet" href="http://nuostudio.com/lib/colorPick/css/colorpicker.css" type="text/css" />
<script type="text/javascript" language="javascript" src="https://www.google.com/jsapi?key=ABQIAAAAP2C8NaUv3oHPRuvhj9fm8RSV8UjxlZ-jYbPbOXJSmuNbwJqV7BQpU1u5G_UNLRVj_8Sz0sfqnAaEIw"></script>
<script type="text/javascript" language="javascript">
<!--
google.load("jquery", "1.7.0");
google.load("jqueryui", "1.8.16");
-->
</script>
<script type="text/javascript" src="http://nuostudio.com/lib/colorPick/js/colorpicker.js"></script>
<script type="text/javascript" src="http://nuostudio.com/lib/colorPick/js/eye.js"></script>
<script type="text/javascript" src="http://nuostudio.com/lib/colorPick/js/utils.js"></script>
<script type="text/javascript" language="javascript" src="http://nuostudio.com/lib/wave.js"></script>

<div id="fps">FPS: 0</div>
<div id="colors">Cambiar:
	<ul>
    	<li><a href="#changeColor" id="color1"></a></li>
    </ul>
</div>

<canvas width="100%" height="100%" id="bg"></canvas>