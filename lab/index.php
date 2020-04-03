<?php

//por defecto random
$any=true;
$type = $_GET['type'];
$types = array('minis','nuotv','twitterballs','wave','bounce','balls','cave','clock','lineart','treegenerator','nebulosa');
if(in_array($type,$types)){
	$id = array_search($type,$types);
}else{
	$id = false;	
}

//importamos labs
$i=0;
$lab[$i]['title'] = 'Minis';
$lab[$i]['url'] = 'minis.php';

$i++;
$lab[$i]['title'] = 'nuoTV';
$lab[$i]['url'] = 'tv.php';

$i++;
$lab[$i]['title'] = 'Twitter Balls';
$lab[$i]['url'] = 'twitter.php';

$i++;
$lab[$i]['title'] = 'Wave';
$lab[$i]['url'] = 'wave.php';

$i++;
$lab[$i]['title'] = 'Bounce';
$lab[$i]['url'] = 'bounce.php';

$i++;
$lab[$i]['title'] = 'Balls';
$lab[$i]['url'] = 'balls.php';

$i++;
$lab[$i]['title'] = 'Cave';
$lab[$i]['url'] = 'cave.php';

$i++;
$lab[$i]['title'] = 'Clock';
$lab[$i]['url'] = 'clock.php';

$i++;
$lab[$i]['title'] = 'Line Art';
$lab[$i]['url'] = 'lineart.php';

$i++;
$lab[$i]['title'] = 'Tree generator';
$lab[$i]['url'] = 'tree.php';

$i++;
$lab[$i]['title'] = 'Nebulosa';
$lab[$i]['url'] = 'nebulosa.php';


//si no hay id, aleatorio
if(!isset($id) || !is_numeric($id) || $id=='') $any=true;
else{	
	if($id>=0 && $id<sizeof($lab)) $any=false;
	else $any=true;
}


?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>

<link rel="shortcut icon" href="/img/favicon.ico">
<!-- HTML5 things -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<script type="text/javascript" src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<title><?php if($any==false) echo $lab[$id]['title'].' - '; ?>nuostudio's lab</title>

<link rel="stylesheet" href="http://nuostudio.com/lib/font-awesome.css">
<link href='http://fonts.googleapis.com/css?family=Raleway:400' type='text/css' rel="stylesheet" >

<style media="all">
body{
	margin:0;
	padding:0;
	position:relative;
	background:#1a1a1a url(/img/stressed_linen.png);
	color:#e6e6e6;
	font-family:"Raleway", Helvetica, Arial, sans-serif;
	font-size:100%;
	overflow:hidden;
}
header{
	width:10%;
	background:rgba(0,0,0,0.4);
	border-right:1px solid #666;
	border-bottom:1px solid #666;
	display:block;
	position:absolute;
	top:0;
	left:0;
	z-index:99;
	opacity:0.4;
}
header:hover{opacity:1;}
header img{width:80%; margin:10%;}
nav a{
	display:block;
	text-decoration:none;
	border-top:1px solid #666;
	padding:5% 10%;
	color:#e6e6e6;
}
nav a:hover{background:#333;}
#lab #lab_desc{
	position:absolute;
	bottom:50px;
	right:100px;
	font-size:120%;
	opacity:0.7;
}
#lab i{
	background:#637984;  
	border-radius:50px;
	padding:13px 19px;
	color:#1a1a1a;
	display:inline-block;
	position:absolute;
	bottom:30px;
	right:30px;
	font-size:200%;
	opacity:0.7;
}
#lab i:hover{background:#e6e6e6;}

article{
	display:block;
	width:100%;
	height:100%;
	position:relative;
	overflow:hidden;
}

</style>


<script type="text/javascript">
$(document).ready(function() {	
	//ocultamos menu
	$('nav').hide();
	
	$("header").hover(function(){	
		//$(this).stop(true,true).animate({width:'200%'},300);	
		$('nav').stop(true,true).slideDown();
	},function(){
		//$(this).stop(true,true).animate({width:'50%'},300);	
		$('nav').stop(true,true).slideUp();
	});
	
	$('#lab_desc').stop(true,true).hide();
	$("#lab").hover(function(){	
		$('#lab_desc').stop(true,true).show(200);
	},function(){
		$('#lab_desc').stop(true,true).hide(200);
	});
});
</script>
</head>

<body>
	<header>
    	<a href="http://nuostudio.com" title="Nuo Studio"><img id="logo" src="img/logo_white.gif" alt="logo"></a>
    	<nav>
        	<?php for($a=0; $a<sizeof($lab); $a++){ ?>
            	<a href="/<?php echo strtolower(str_replace(' ','',$lab[$a]['title'])); ?>" <?php if($a==$id) echo 'id="actual"'; ?>><?php echo $lab[$a]['title']; ?></a>
            <?php } ?>
    	</nav>
    </header>
    <article>
    	<?php
		if($any==false){
			include($lab[$id]['url']);
		}else{
			include($lab[array_rand($lab)]['url']); 
		} ?>
    </article>
    
    <aside id="lab">
    	<span id="lab_desc">nuostudio's lab</span>
        <i class="icon-beaker"></i>
    </aside>
    
<!--Google Analytics-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17212759-10']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>