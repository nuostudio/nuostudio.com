<style type="text/css">
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

var escapeRadius = 50;

var initial = 1000;
var ball = new Array();

var speedMax = 10;

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
		var color = Math.round(0xffffff * Math.random()).toString(16);
		if(color.length<5) color += '00';
		else if(color.length<6) color += '0';
		return color;
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
		return Math.random()*2;
	}
	
	function draw(e) {
		clear()
		// Dibujamos un circulo blanco en el area de influencia del raton
		ctx.strokeStyle = '#f1f1f1';
		ctx.beginPath();
		ctx.arc(mp.x,mp.y,escapeRadius,0,Math.PI*2,true);
		ctx.closePath();
		ctx.stroke();
		
		// Refresh position:
		for(var i=0;i<ball.length;i++){
			// Bounce off walls
			if( (ball[i].x-ball[i].radius)<0 || (ball[i].x+ball[i].radius)>WIDTH) ball[i].dx=-ball[i].dx;
			if( (ball[i].y-ball[i].radius)<0 || (ball[i].y+ball[i].radius)>HEIGHT) ball[i].dy=-ball[i].dy;
			
			if(Math.abs(ball[i].dx) < 1) ball[i].dx *= speedMax;
			if(Math.abs(ball[i].dy) < 1) ball[i].dy *= speedMax;
			
			// Escape from mouse
			if(distance(ball[i],mp) < escapeRadius){
				if(Math.abs(ball[i].dx) > 1) ball[i].dx *= 0.05;
				if(Math.abs(ball[i].dy) > 1) ball[i].dy *= 0.05;
			}
			
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
	setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 1000); 
	
	// Actualizadores
	$(window).resize(resizeCanvas);
	$(document).mousemove(mouseMove);
	$(document).mousedown(mouseDown);
	drawInterval = setInterval(frame, 2);
	resizeCanvas();
}
-->
</script>

<div id="fps">FPS: 0</div>
<canvas width="100%" height="100%" id="bg"></canvas>