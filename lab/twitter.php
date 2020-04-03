<style type="text/css">
#web{
	position:absolute;
	top:0;
	left:0;
	width:100%;	
}
#fps{
	font-family:"Courier New", Courier, monospace;
	font-size:13px;
	position:absolute;
	top:3px;
	right:3px;	
}
.tweet{
	position:absolute;
	border-radius:20px;
	width:300px;	
	color:#000;
	padding:20px;
	box-shadow:#333 0 0 10px;
}
#searching{
	position:absolute;
	top:0;
	left:50%;
	text-align:center;
	padding:5px;
	background:#000;
	background:rgba(0,0,0,0.5);
	-webkit-border-bottom-right-radius: 10px;
	-webkit-border-bottom-left-radius: 10px;
	-moz-border-radius-bottomright: 10px;
	-moz-border-radius-bottomleft: 10px;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
	border-left:#ccc solid 1px;	
	border-right:#ccc solid 1px;
	border-bottom:#ccc solid 1px;	
}
#pause{
	background:#666;
	background:rgba(0,0,0,0.7);
	color:#FFF;
	position:absolute;
	top:0;
	left:0;
	z-index:98;
	width:100%;
	height:100%;
}
#pause p#pauseText{
	text-align:center;
	margin-top:20%;
}
#pause div{
	text-align:center;
	width:600px;
	margin:200px auto;
	border:#666 1px solid;
	border-radius:40px;
	padding:40px;
}
#pause div a{
	text-decoration:none;
	color:#09F;
}
#tweets a{
	text-decoration:none;
	color:inherit;
	font-weight:bold;	
}
#tweets a:hover{
	text-decoration:underline;	
}
</style>
<script type="text/javascript" language="javascript">
<!--
// VARIABLES
var ctx;
var WIDTH;
var HEIGHT;

var canvasMinX;
var canvasMaxX;

var canvasMinY;
var canvasMaxY;

var ms = {x:0, y:0}; // Mouse speed
var mp = {x:0, y:0}; // Mouse position

var initial = 5;
var ball = new Array();

var speedMax = 2;
var minRadius = 5;
var maxRadius = 50;
var initRadius = 40;
var miniRatio = 0.006;

// Percentage bar
var percentageHeight = 10;

// Realmente es como un !pause. cuando hasFocus es false, estamos en pausa

var hasStarted = false; // Pausa inicial para mostrar la ayuda
var hasFocus = false; 	// Focus de la ventana, para detener la aplicacion si lo pierde
var updateInterval;		// No tocar, obviamente
var queue = 0;			// Number of balls waiting to be displayed

var fps = 0, now, lastUpdate = (new Date)*1 - 1;
var fpsFilter = 50;

// --------- TOPICS OBJECT ------------
	var topics = new Array();
	function addTopic(name,color){
		topics.push({
				name : name,
				color : color,
				since_id : 0,
				tweets : 0
			});
	}
	addTopic('nuostudio','#fff');
	addTopic(':)','#09C');
	addTopic(':(','#eb0000');
// ------------------------------------

// INICIO
$(document).ready(function(){
	ctx = $('#bg')[0].getContext("2d");
	// Adaptamos a la ventana:
	ctx.canvas.width  = window.innerWidth;
  	ctx.canvas.height = window.innerHeight;
	// Actualizamos las variables:
	WIDTH = $("#bg").width();
  	HEIGHT = $("#bg").height();
	
	canvasMinX = $("#bg").offset().left;
  	canvasMaxX = canvasMinX + WIDTH;
	
	canvasMinY = $("#bg").offset().top;
  	canvasMaxY = canvasMinY + HEIGHT;
	
	// Twitter balls
	function addBall(id,user,text,color){
		ball.push({ 
			x: WIDTH*Math.random(),
			y: (HEIGHT-percentageHeight-initRadius)*Math.random(),
			dx:randDir(),
			dy:randDir(),
			show:true,
			color:color,
			radius:initRadius,
			id:id,
			user:user,
			text:text,
			type:'new'
		});
	}
	
	function updateAllTopics(){
		for(var i=0;i<topics.length;i++){
			updateBalls(i);
		}
	}
	
	function displayTopics(){
		$('#topics').html('');
		for(var i=0;i<topics.length-1;i++){
			$('#topics').append('<span style="color:'+topics[i].color+'">'+topics[i].name+'</span> &bull;');
		}
		$('#topics').append('<span style="color:'+topics[i].color+'">'+topics[i].name+'</span>');
	}

	function updateBalls(topic){
		// topic must be numerical index of topic
		if(!hasFocus) return true;
		// Wait if there are too many on queue
		if(queue > 30) return true;
		//console.log('Updating: '+topic+'='+topics[topic].name);
		if(topic > topics.length-1){
			console.log('Fatal error: Updating non existing topic');
			return true;
		}
		console.log(topics[topic].name);
		$.get("http://search.twitter.com/search.json", { since_id:topics[topic].since_id, q:topics[topic].name, count:2 },
				function(data){
					console.log('Inside callback: topic='+topic);
					for(var i=0; i<data.results.length; i++){
						if(data.results[i].id <= topics[topic].since_id) continue; // Manually avoid dupes
						topics[topic].since_id = data.results[i].id; // Update since_id
						console.log(topics[topic].since_id)
						topics[topic].tweets++;
						addBall(data.results[i].id,data.results[i].from_user,data.results[i].text,topics[topic].color);
					}
			 }, "jsonp");
	}
	
	// Funciones importantes:
	function clear() {
		ctx.clearRect(0, 0, WIDTH, HEIGHT);
	}
	
	function circle(x,y,rad,color){
		// Circulo
		ctx.fillStyle = color;
		ctx.beginPath();
		ctx.arc(x,y,rad,0,Math.PI*2,true);
		ctx.closePath();
		ctx.fill();
	}
	
	function resizeCanvas(e) {
		WIDTH = window.innerWidth;
		HEIGHT = window.innerHeight;
		
		$("#bg").attr('width',WIDTH);
		$("#bg").attr('height',HEIGHT);
		
		$("#pause").attr('width',WIDTH);
		$("#pause").attr('height',HEIGHT);
	}
	
	function mouseMove(e) {
		ms.x = Math.max( Math.min( e.pageX - mp.x, 40 ), -40 );
		ms.y = Math.max( Math.min( e.pageY - mp.y, 40 ), -40 );
		
		mp.x = e.pageX - canvasMinX;
		mp.y = e.pageY - canvasMinY;
		
	}
	
	function randColor(){
		return Math.round(0xffffff * Math.random()).toString(16);
	}
	
	var curTweet=0;
	
	function mouseDown(e){
		// Posicion de raton
		mp.x = e.pageX - canvasMinX;
		mp.y = e.pageY - canvasMinY;
		// Detectar click sobre bola
		for(var i=0;i<ball.length;i++){
			if(mp.x	> ball[i].x-ball[i].radius && mp.x	< ball[i].x+ball[i].radius && mp.y	> ball[i].y-ball[i].radius && mp.y	< ball[i].y+ball[i].radius){
				// Click sobre bola hecho
				ball[i].dx = 0;
				ball[i].dy = 0;
				ball[i].show = false;
				if(curTweet!==0) curTweet.remove();
				curTweet = $('#tweets').append('<div id="'+ball[i].id+'"><a href="https://twitter.com/'+ball[i].user+'" target="_blank">@'+ball[i].user+'</a>:<br />'+ball[i].text+'</div>');
				curTweet = $('#'+ball[i].id);
				curTweet.addClass('tweet').css({
					top:(ball[i].y-ball[i].radius-10)+'px',
					left:(ball[i].x-ball[i].radius-10)+'px',
					background:ball[i].color,
					minHeight:(ball[i].radius+10)+'px'
				});
				break;
			}else{
				if(curTweet!==0) curTweet.remove();
			}
		}
		return true;
	}
	
	function randDir(){
		return ((Math.random()-0.3)*speedMax);
	}
	function randRad(){	
		return (Math.random()*maxRadius);
	}
	function percent(){
		// Draws the percentages for each topic
		var total = 0;
		
		for(var i=0;i<topics.length;i++) total += topics[i].tweets;
		if(total<1) return true;
		
		// Now that we have the max, calculate each percentage
		var lastX = 0;
		for(var i=0;i<topics.length;i++){
			var perc = 	(topics[i].tweets*100/total)/100;
			var width = WIDTH*perc;
			if(i==topics.length-1) width = WIDTH - lastX;
			console.log(topics[i].name+': '+perc)
			ctx.fillStyle = topics[i].color;
     		ctx.fillRect(lastX,HEIGHT-percentageHeight,width,percentageHeight);
			lastX = WIDTH*perc;
		}
	}
	
	function draw(e) {
		if(!hasFocus) return true;
		clear()
		percent()
		// Refresh position:
		for(var i=0;i<ball.length;i++){
			// Slowly diminish balls
			if(!ball[i].show) continue;
			ball[i].radius -= ball[i].radius*miniRatio;
			// Delete small balls
			if(ball[i].radius < minRadius){
				ball.splice(i,1);
				continue;
			}
			// Bounce off walls
			if( (ball[i].x-ball[i].radius)<0 || (ball[i].x+ball[i].radius)>WIDTH) ball[i].dx=-ball[i].dx;
			if( (ball[i].y-ball[i].radius)<0 || (ball[i].y+ball[i].radius)>HEIGHT-percentageHeight) ball[i].dy=-ball[i].dy;
			if(ball[i]){
				ball[i].x += ball[i].dx;
				ball[i].y += ball[i].dy;
				circle(ball[i].x,ball[i].y,ball[i].radius,ball[i].color);
			}
			// Only one new ball per frame
			if(ball[i].type == 'new'){
				// Queue is number of balls - i
				queue = ball.length-i;
				ball[i].type = 0;
				break;	
			}
		}
	}
	
	function distance(p1,p2){
		var dx = p2.x-p1.x;
		var dy = p2.y-p1.y;
		return Math.sqrt(dx*dx + dy*dy);
	}
	
	function frame(){
		draw();
		// Now calculate FPS
		var thisFrameFPS = 1000 / ((now=new Date) - lastUpdate);
		fps += (thisFrameFPS - fps) / fpsFilter;
		lastUpdate = now * 1 - 1;
	}
	
	// Display fps
	setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 1000/60); 
	
	setInterval(updateAllTopics,250);
	
	// Actualizadores
	$(window).resize(resizeCanvas);
	$(window).blur(function(){
		hasFocus=false;
		$('#pause').fadeIn();
		$('#editTopics').hide();
		$('#explanation').hide();
		$('#pauseText').show();
	});
	$(window).focus(function(){
		if(!hasStarted) return true;
		hasFocus=true;
		$('#pause').fadeOut();
		//createIntervals()
	});
	$("a[href='#start']").click(function(event){
		hasStarted = true;
		hasFocus = true;
		$('#pause').fadeOut('slow',function(){
			$('#pauseText').show();
			$('#explanation').hide();
			$('#editTopics').hide();
		});
	});
	$('#config input').click(function(e){
		e.preventDefault();
		// Go into topic edit mode
		$('#pause').fadeIn();
		hasFocus=false;
		$('#editTopics').show();
		$('#pauseText').hide();
	});
	$("a[href='#addTopic']").click(function(event){
		event.preventDefault();
		var newTopic = prompt("New topic?");
		if(newTopic.length < 1) return true;
		addTopic(newTopic,'#'+randColor());
		displayTopics();
	});
	$("a[href='#clearAll']").click(function(event){
		event.preventDefault();
		topics = [];
		displayTopics();
	});
	$(document).mousemove(mouseMove);
	$(document).mousedown(mouseDown);
	drawInterval = setInterval(frame, 1000/60);
	resizeCanvas();
	displayTopics();
});
-->
</script>
<div id="fps">FPS: 0</div>
<div id="searching">
	<span id="topics"></span> 
	<span id="config"><input type="button" name="edit" value="Edit" /></span>
</div>
<div id="web">
    
</div>
<div id="pause">
	<p id="pauseText" style="display:none;">Pause</p>
	<div id="explanation">
		<h2>What's this?</h2>
		<p>You will see a social experiment, with balls in different colors. Each color is a tweet containing a certain word.</p>
		<p>Initially it will display happy tweets (Containing a :) face) in <span style="color:#09F">blue</span>, and sad tweets (Containing a :( face) in <span style="color:#eb0000">red</span>, so that we can see the level of twitter happiness or sadness in real time. The bottom bar is the percentage of each. In white appear tweets mentioning our website nuostudio.</p>
		<p>If you click on any ball you will see the tweet. Please note that this experiment is still under development</p>
		<h3>I love it!</h3>
		<p>Then feel free to tweet about it!</p>
		<p>&nbsp;</p>
		<p align="center"><a href="#start">Start!</a></p>
	</div>
	<div id="editTopics" style="display:none; text-align:left">
		<h2>Edit topics:</h2>
		<p>Here you can manage the topics that are searched for:</p>
		<p><a href="#addTopic">Add topic</a> &bull; <a href="#clearAll">Clear all topics</a></p>
		<p align="center"><a href="#start">Done</a></p>
	</div>
</div>
<div id="background">
<canvas width="100%" height="100%" id="bg"></canvas>
</div>
<div id="tweets"></div>