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
</style>
<script type="text/javascript" language="javascript" src="https://www.google.com/jsapi?key=ABQIAAAAP2C8NaUv3oHPRuvhj9fm8RSV8UjxlZ-jYbPbOXJSmuNbwJqV7BQpU1u5G_UNLRVj_8Sz0sfqnAaEIw"></script>
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

var speedMax = 10;
var maxRadius = 50;
var miniRatio = 0.001;

var fps = 0, now, lastUpdate = (new Date)*1 - 1;
var fpsFilter = 50;

//
// LIBRERIAS
google.load("jquery", "1.7.0");
google.setOnLoadCallback(ready);
// INICIO
function ready(){
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
	
	// Set up the balls
	for(var i=0;i<initial;i++){
		ball.push({ 
			x: WIDTH*Math.random(),
			y: HEIGHT*Math.random(),
			dx:randDir(),
			dy:randDir(),
			color:'#'+randColor(),
			radius:randRad()
		});
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
	
	function mouseDown(e){
		// Nueva bola
		mp.x = e.pageX - canvasMinX;
		mp.y = e.pageY - canvasMinY;
		
		ball.push({
			x: mp.x,
			y: mp.y,
			dx:randDir(),
			dy:randDir(),
			color:'#'+randColor(),
			radius:randRad()
		});
	}
	
	function randDir(){
		return ((Math.random()-0.3)*speedMax);
	}
	function randRad(){	
		return (Math.random()*maxRadius);
	}
	
	function draw(e) {
		clear()
		// Refresh position:
		for(var i=0;i<ball.length;i++){
			// Slowly diminish balls
			ball[i].radius -= ball[i].radius*miniRatio;
			if(ball[i].radius < 0.1){
				ball.splice(i,1);
				continue;
			}
			// Bounce off walls
			if( (ball[i].x-ball[i].radius)<0 || (ball[i].x+ball[i].radius)>WIDTH) ball[i].dx=-ball[i].dx;
			if( (ball[i].y-ball[i].radius)<0 || (ball[i].y+ball[i].radius)>HEIGHT) ball[i].dy=-ball[i].dy;
			// Bounce on each other
			for(var a=0;a<ball.length;a++){ if(a!==i){
				// Detectamos la colision, y modificamos el rumbo de la mas pesada
				if(ball[a] && ball[i]){
					if(distance(ball[a],ball[i]) <= ball[a].radius + ball[i].radius){
						// Han chocado
						// La mayor engulle a la pequeña
						if(ball[i].radius > ball[a].radius){
							ball[i].radius += ball[a].radius/3;
							ball.splice(a,1);
						}else{
							ball[a].radius += ball[i].radius/3;
							ball.splice(i,1);
						}
					}
				}
			}}
			if(ball[i]){
				ball[i].x += ball[i].dx;
				ball[i].y += ball[i].dy;
				circle(ball[i].x,ball[i].y,ball[i].radius,ball[i].color);
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
	setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 500); 
	
	// Actualizadores
	$(window).resize(resizeCanvas);
	$(document).mousemove(mouseMove);
	$(document).mousedown(mouseDown);
	drawInterval = setInterval(frame, 1);
	resizeCanvas();
}
-->
</script>

<div id="fps">FPS: 0</div>

<canvas width="100%" height="100%" id="bg"></canvas>