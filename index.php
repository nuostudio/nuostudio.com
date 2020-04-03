<?php

//comprobamos formulario enviado
if(isset($_POST['name'])){
	//antispam
	if($_POST['url']==''){
		//todos los campos
		if(isset($_POST['name']) && $_POST['name']!='' && isset($_POST['email']) && $_POST['email']!='' && isset($_POST['msg']) && $_POST['msg']!=''){
			//email válido
			if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
				$email=$_POST['email'];
				$name=ucwords(strtolower(strip_tags($_POST["name"])));
				$msg=strip_tags($_POST['msg']);   
				$dest='jaime@nuostudio.com, alejandro@nuostudio.com';
				
				//formamos cosas del correo
				$headers = 'MIME-Version: 1.0'."\r\n".'Content-type: text/plain; charset=UTF-8'."\r\n".'From:'.$name.'<'.$email.'>'."\r\n";
				$asunto='Email sent from nuostudio.com';
				$msj=$msg.'
----------------
Name:'.$name.'
Email:'.$email.'
User-agent: '.$_SERVER['HTTP_USER_AGENT'].'
IP: '.$_SERVER['REMOTE_ADDR'];
				
				if(mail($dest,$asunto, $msj, $headers)) $done='Success! We will give you an answer ASAP.';
				else $error='Oops! An error was found while sending the email. Please try again later.';
				
			}else $error='Not valid email.';
		}else $error='You must fill all the blanks.';
	}else $error='You are doing weird things man...';
}

?><!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta name="author" content="Jaime Caballero Menendez" />
    <meta name="keywords" content="nuostudio, web, develop, simple, responsive, security, html5, css3" />
    <meta name="description" content="We are a small web development company, and we like it that way. We care for our projects and work hard to get them right." />
    
    <?php if(ereg('iPhone',$_SERVER['HTTP_USER_AGENT']) || ereg('iPod',$_SERVER['HTTP_USER_AGENT']) || ereg('iPad',$_SERVER['HTTP_USER_AGENT'])){ ?>
    <title>nuostudio</title>
    <?php }else{ ?>
    <title>nuostudio - Keep it minimal</title>
    <?php } ?>
    
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <script type="text/javascript" src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <link rel="shortcut icon" href="/img/favicon.ico">
    
    <!--Style-->
    <link rel="stylesheet" type="text/css" href="/layout.css"/>  
    <link rel="stylesheet" href="/lib/font-awesome.css">
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,200,600' type='text/css' rel="stylesheet" >
    
    <?php if(ereg('iPhone',$_SERVER['HTTP_USER_AGENT']) || ereg('iPod',$_SERVER['HTTP_USER_AGENT'])){ ?>
    <link rel="apple-touch-startup-image"  href="img/splash-iphone.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" />
    <!--Web app-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <?php }else if(ereg('iPad',$_SERVER['HTTP_USER_AGENT'])){ ?>
    <link media="(device-width:: 768px) and (orientation: portrait)" href="img/splash-ipad-portait.png" rel="apple-touch-startup-image"/>
    <link media="(device-width: 768px) and (orientation: landscape)" href="img/splash-ipad-landscape.png" rel="apple-touch-startup-image"/>
    <!--Web app-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <?php } ?>
    
    <link rel="stylesheet" href="/lib/homescreen/add2home.css">

</head>

<body>
<?php if(isset($done)) echo '<script>alert("'.$done.'");</script>'; ?>
<?php if(isset($error)) echo '<script>alert("'.$error.'");</script>'; ?>

<header><div class="content">
	<img id="logo" src="/img/logo.gif" alt="logo">
    <nav>
    	<a href="#projects" title="Discover our projects">Projects<i class="icon-chevron-right mobile"></i></a>
    	<a href="#portfolio" title="Take a close look">Portfolio<i class="icon-chevron-right mobile"></i></a>
        <a href="#about" title="Our history by ourselves">About us<i class="icon-chevron-right mobile"></i></a>
        <a href="#contact" title="Let's talk about it">Contact<i class="icon-chevron-right mobile"></i></a>
    </nav>
</div></header>

<section id="projects"><div class="black_bg">
	<div class="content">
    	<h1><i class="icon-briefcase icon-large"></i> Discover our projects.</h1>
    	<p>We are currently working on some projects you may know about, mostly related to social newtworks. Feel free to check them out as many times as you want. </p>
	
    <article class="right">
    	<div class="shot"><img src="img/quepiensas.png" alt="Que Piensas"></div>
        <div class="brief">
        	<h2>QuePiensas</h2>
            <h3><i class="icon-tag"></i> Social Network</h3>
            <p>Spanish social network fully integrated with Facebook and Twitter. It alows users to express their opinion on their friends. It was build from scratch by us. It uses jQuery, lots of AJAX and a PHP backend. Currently on closed private beta. If you want an invitation to take a look contact us.</p>
            <a href="http://quepiensas.es">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    
    <article class="left">
    	<div class="shot"><img src="img/cloudclient.png" alt="Cloudclient"></div>
        <div class="brief">
        	<h2>Cloudclient</h2>
            <h3><i class="icon-tag"></i> Dropbox client</h3>
            <p>An open source client for cloud services like Dropbox. It is still under development, although it already has plenty of features. It works nicely on desktop and tablets. The full source code for it can be found on its <a href="https://github.com/aurbano/Cloudclient">Github page</a>, if you want to help out fork it! It's already online, if you want to try it.</p>
            <a href="http://cloudclient.es">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    
    <article class="right">
    	<div class="shot"><img src="img/chevismo.png" alt="Chevismo"></div>
        <div class="brief">
        	<h2>Chevismo</h2>
            <h3><i class="icon-tag"></i> Social Network</h3>
            <p>Entertainment site featuring many popular tools and services. Also a chat, forum, and many other sections that users discover over time. This was mostly done to experiment with some new things, but it got quite popular in Spain so we kept on working on it.</p>
            <a href="http://chevismo.com">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    
    <article class="left">
    	<div class="shot"><img src="img/djsmusic.png" alt="DJs Music"></div>
        <div class="brief">
        	<h2>DJs Music</h2>
            <h3><i class="icon-tag"></i> Social network</h3>
            <p>Music sharing website where independent DJs upload their mixes and original songs, packed with features developed over the years. The site is now translated in 5 languages, and is popular in many different countries. The design is growing a bit old, and we already have some ideas for new things there, but we still need to get some free time!</p>
            <a href="http://djs-music.com">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>

</div></div></section>

<section id="portfolio">
<div class="content" id="intro">
    <h1><i class="icon-folder-open icon-large"></i> Take a closer look.</h1>
    <p>Some people asked us to build their websites. They were cool, so we did it.</p>
</div>
<div class="content" id="works">
    <article>
    	<div class="shot"><img src="/img/works/euphoriafestival.png" alt="Euphoria"/></div><div class="info">
        	<h2>Euphoria Festival</h2>
            <p>Techno music festival stablished on Gijon, Spain.</p>
            <a href="http://euphoria-festival.com">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    <article>
    	<div class="shot"><img src="/img/works/isabelparedes.png" alt="Isabel Paredes"/></div><div class="info">
        	<h2>Isabel Paredes</h2>
            <p>Dressmaker from Oviedo, Spain.</p>
            <a href="http://isabelparedes.com">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    <article>
    	<div class="shot"><img src="/img/works/miguelparte.png" alt="Isabel Paredes"/></div><div class="info">
        	<h2>Miguel Parte</h2>
            <p>Professional portfolio site to the photographer Miguel Parte.</p>
            <a href="http://miguelparte.com">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article>
    <article>
    	<div class="shot"><img src="/img/works/cochesusadosasturias.png" alt="Euphoria"/></div><div class="info">
        	<h2>Coches Usados Asturias</h2>
            <p>Second-hand cars buying and selling site for the people of Asturias.</p>
            <a href="http://cochesusadosasturias.es">View site <i class="icon-circle-arrow-right"></i></a>
        </div>
    </article> 
</div></section>

<section id="about">
<div class="black_bg"><div class="content">
    <h1><i class="icon-book icon-large"></i> Our story by ourselves.</h1>
    <p>We are a small company, and we like it that way. We care for our projects and work hard to get them right. This is nuostudio's team, this is us. And after us, is our story.</p>
    <div class="medio">
    	<img src="img/jaime.jpg" alt="Jaime Caballero" />   
        <h2>Jaime Caballero</h2> 
        <h3>Front-end developer</h3>
    	<p>Responsive design enthusiast and CSS ninja. Plays a lot with jQuery and HTML5 while listening music. Might be mad.</p>
        <ul class="iconed">
        	<li><i class="icon-twitter icon-large"></i> <a href="http://twitter.com/jaicab_">@jaicab_</a></li>
            <li><i class="icon-envelope-alt icon-large"></i> <a href="mailto:jaime@nuostudio.com">jaime@nuostudio.com</a></li>
        </ul>    
    </div><div class="medio segundo">
    	<img src="img/chevi.jpg" alt="Alejandro U. Alvarez" />
        <h2>Alejandro U. Alvarez</h2>
        <h3>Back-end developer</h3>
    	<p>Engineering student, interested in security, networks and systems. Enjoys programming a little too much.</p>
        <ul class="iconed">
        	<li><i class="icon-twitter icon-large"></i> <a href="http://twitter.com/chevi_">@Chevi_</a></li>
            <li><i class="icon-envelope-alt icon-large"></i> <a href="mailto:alejandro@nuostudio.com">alejandro@nuostudio.com</a></li>
        </ul>   
    </div>
    
    <p>We met during the first year of engineering school, and started working on some projects together. A couple years and some projects later we decided to start something together. So here we are! We are a small company but we love it that way. We care for our projects and spend a lot of time getting every detail right.</p>
    <p>We develop light, powerful websites using the last technologies like HTML5, CSS3 and jQuery. We care about design and adaptability as much as security and efficiency.
We also love trying some experiments at <a href="http://lab.nuostudio.com">nuostudio's lab</a>. And i can tell you, those things are great.</p>
    
</div></div></section>

<section id="contact">
<div class="content">

    <h1><i class="icon-comments-alt icon-large"></i> Let's talk about it.</h1>
    <div class="medio">
    	<p>You wanna tell us something? If you want to talk to us, you can do it througth the given links or the form above.</p>
        <ul class="iconed">
        	<li><i class="icon-twitter icon-large"></i> <a href="http://twitter.com/nuostudio">@nuostudio</a></li>
            <li><i class="icon-facebook icon-large"></i> <a href="http://facebook.com/nuostudio">facebook.com/nuostudio</a></li>
            <li><i class="icon-envelope-alt icon-large"></i> <a href="mailto:contact@nuostudio.com">contact@nuostudio.com</a></li>
        </ul>
        <p>Best way is twitter. We'll give you an answer as soon as we can.</p>
        <p>Next, you can check out our location and some social media sites to know more and more about us, and what we love.</p>
    </div><div class="medio segundo">
    	<form method="post">
            <label><span class="pre"><i class="icon-user"></i> Name</span><input type="text" name="name" placeholder="<?php echo $lang['contact_form1']; ?>" required></label>
            <label><span class="pre"><i class="icon-envelope-alt"></i> Email</span><input type="email" name="email" placeholder="<?php echo $lang['contact_form2']; ?>" required></label>
            <label id="mailText"><span class="pre"><i class="icon-comment"></i> Message</span><textarea name="msg" placeholder="<?php echo $lang['contact_form3']; ?>"></textarea></label>
            <!--antispam-->
            <input type="hidden" name="url"/>
            
            <button type="submit" class="but"><i class="icon-upload-alt"></i> Send</button>
        </form>
    </div>

</div></section>

<section id="location">
	<div id="map"></div>
    <div id="map-overlay"></div>
    <div id="map-info">
            <h3><i class="icon-globe"></i> Location</h3>
            <p>nuostudio is a tiny software development company based in Asturias, in the north of Spain.</p>
        </div>
</section>

<footer><div class="black_bg"><div class="content">
	<p id="social">
    	<a href="http://lab.nuostudio.com" title="Visit nuostudio's lab"><i class="icon-beaker"></i></a>
    	<a href="http://blog.nuostudio.com" title="Visit our blog"><i class="icon-rss"></i></a>
    	<a href="http://github.com/nuostudio" title="Visit nuostudio on Github"><i class="icon-github"></i></a>
    	<a href="http://twitter.com/nuostudio" title="Visit @nuostudio"><i class="icon-twitter"></i></a>
    </p>
	<p id="copy">
    	Copyright &copy;<?php echo date('Y'); ?> <strong>nuostudio.com</strong>
    </p>
</div></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<!-- Maps -->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
function hideURLbar(){ window.scrollTo(0,1);}
</script>

<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<script type="text/javascript" src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]> <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script> <![endif]-->


<script src="/lib/jquery.fitvids.js" type="text/javascript"></script>

<script type="text/javascript">
<!--
var addToHomeConfig = {
	touchIcon:true,
	lifespan: 10000
};

$(document).ready(function() {	
	//smooth navigation
	$("nav a").click(function(event){		
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
	});
	//fitvids
	$(".content").fitVids();
	
	//fullscreen home
	function fullHome(){
		if (window.innerHeight) alt = window.innerHeight;
   		else alt = document.body.clientHeight;
		if($('header').height()<alt){
			mas = (alt-$('header').height())/2;
			$('header').animate({
				'marginTop':mas+'px',
				'marginBottom':mas+'px'
			},1000);
		}
	}
	fullHome();
	$(window).resize(fullHome);
	
	//maps
	var map;
	function initialize() {
		var myOptions = {
			zoom: 7,
			center: new google.maps.LatLng(43.362689,-5.847531),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: true,
			scrollwheel: false,
			draggable: false
		};
		map = new google.maps.Map(document.getElementById('map'), myOptions);
		/*var beachMarker = new google.maps.Marker({
			position: new google.maps.LatLng(43.362689,-5.847531),
			map: map
		});*/
		var stylesArr = [{
				stylers:[{ saturation: -100 }]
			},{
				featureType: "road",
				elementType: "labels",
				stylers: [{ visibility: "off" }]
		}];
		map.setOptions({styles: stylesArr});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	google.maps.event.addDomListener(window, 'resize', function() {
		map.setCenter(new google.maps.LatLng(43.362689,-5.847531));
	});

});
-->
</script>

<script type="application/javascript" src="lib/homescreen/add2home.js" charset="utf-8"></script>

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
</footer>

</body>
</html>
