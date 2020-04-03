<style type="text/css">
canvas{
	margin:0 auto;
	background:#FFF;
}
#fps{
	font-family:"Courier New", Courier, monospace;
	font-size:13px;
	position:absolute;
	top:3px;
	right:3px;	
}
article{
	padding-left:15%;
}

</style>
<script type="text/javascript" language="javascript" src="https://www.google.com/jsapi?key=ABQIAAAAP2C8NaUv3oHPRuvhj9fm8RSV8UjxlZ-jYbPbOXJSmuNbwJqV7BQpU1u5G_UNLRVj_8Sz0sfqnAaEIw"></script>
<script type="text/javascript" language="javascript">
<!--
google.load("jquery", "1.7.0");
google.setOnLoadCallback(canvasWave);
//---------//
//	Chevi  //
//---------//
function canvasWave(){
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
	
	var particles = new Array();
	
	ctx = $('#game')[0].getContext("2d");
	// Actualizamos las variables:
	WIDTH = $("#game").width();
  	HEIGHT = $("#game").height();
	
	canvasMinX = $("#game").offset().left;
  	canvasMaxX = canvasMinX + WIDTH;
	
	canvasMinY = $("#game").offset().top;
  	canvasMaxY = canvasMinY + HEIGHT;
	
	// Configuracion de la bola
	var heli = {
		speed:0,		// Velocidad inicial
		maxSpeed:2,		// Limitacion de velocidad
		force:0.1,		// NO TOCAR
		forceDown:0.08,	// Gravedad
		forceUp:0.08,	// Fuerza de subida
		h:HEIGHT/2,		// Posicion inicial
		rad:10			// Radio
	};
	
	var terrainTop = new Array();
	var terrainBottom = new Array();
	
	var blockWidth = 4;				// Ancho de bloques
	var maxDif = 5;					// Diferencia de altura entre bloques
	var maxEndDif = 20;				// Diferencia maxima modo dificil
	var minSpace = 150;				// Espacio minimo entre bloques
	var minSpaceEnd = 30;			// Espacio minimo final
	var maxSpace = 400;				// Espacio maximo
	var maxSpaceEnd = 70;			// Espacio maximo final
	var maxHeight = HEIGHT-1;		// Maxima altura de los bloques
	var moveSpeed = 1; 				// Velocidad de desplazamiento
	var maxSpeed = 15;				// Velocidad maxima
	var incrementSpeed = 0.0007;	// Incremento de velocidad por iteracion
	var varIncrement = 0.001;		// Incrementos para variables
	
	// Variables no configurables
	var terrainBlocks = Math.ceil(WIDTH/blockWidth)+1;
	var moved = 0;
	
	var started = false;
	var drawInterval;
	
	function genObstacleTop(i){
		// Genera una nueva barra, segun la anterior
		//
		if(terrainTop.length==0){
			// Aun no hay ninguno, al azar:
			var height1 = Math.random()*maxHeight-Math.random()*20;
			terrainTop.push({
				x:0,
				y:0,
				height:height1
			});
		}else{
			// Creamos un nuevo bloque, en funcion del anterior
			var prevHeight = terrainTop[i-1].height; // El anterior de arriba
			var height1 = Math.random()*maxHeight-Math.random()*20;
			while(Math.abs(height1-prevHeight)>maxDif){
				if(height1>prevHeight) height1-=2; else height1+=2;
			}
			terrainTop.push({
				x:terrainTop[i-1].x+blockWidth,
				y:0,
				height:height1
			});
		}
	}
	
	// Generacion del terreno
	function genObstacleBottom(i){
		// Genera una nueva barra, segun la anterior
		//
		var height1 = terrainTop[i].height;
		
		if(terrainBottom.length==0){
			// Calculo la altura del segundo al azar
			var height1 = terrainTop[i].height;
			var height2 = (HEIGHT-height1)*Math.random();
			while(HEIGHT-height1-height2<minSpace) height2 -= 2;
			while(HEIGHT-height1-height2>maxSpace) height2 += 2;
			terrainBottom.push({
				x:0,
				y:HEIGHT-height2,
				height:height2
			});
		}else{
			var prevHeight = terrainBottom[i-1].height; // El anterior de abajo
			
			var height2 = (HEIGHT-height1)*Math.random();
			
			while(Math.abs(height2-prevHeight)>maxDif){
				if(height2>prevHeight) height2-=2; else height2+=2;
			}
			
			while(HEIGHT-height1-height2<minSpace) height2 -= 2;
			while(HEIGHT-height1-height2>maxSpace) height2 += 2;
			
			terrainBottom.push({
				x:terrainBottom[i-1].x+blockWidth,
				y:HEIGHT-height2,
				height:height2
			});
		}
	}
	
	// Generar obstaculos
	for(var i=0;i<terrainBlocks;i++){
		genObstacleTop(i);
		genObstacleBottom(i);	
	}
	// Ahora ajusto la altura del heli:
	while(heli.h-heli.rad <= terrainTop[Math.floor(terrainBlocks/2)].height+maxDif+heli.rad) heli.h += 1;
	
	// Funciones importantes:
	function clear() {
		ctx.clearRect(0, 0, WIDTH, HEIGHT);
	}
	
	function mouseDown(e) {
		heli.force = -heli.forceUp;
	}
	
	function onKeyDown(e){
		if(!started){
			drawInterval = setInterval( frame, 1 );
			started = true;
		}else if(e.keyCode == 38) heli.force = -heli.forceUp;
	}
	
	function onKeyUp(e){
		heli.force = heli.forceDown;
	}
	
	function mouseUp(e) {
		heli.force = heli.forceDown;
	}
	
	function draw(e) {
		ctx.fillStyle = 'black';
		var total = terrainTop.length;
		for(var i=0; i<total;i++){
			ctx.fillRect(terrainTop[i].x,terrainTop[i].y,blockWidth,terrainTop[i].height);
			ctx.fillRect(terrainBottom[i].x,terrainBottom[i].y,blockWidth,terrainBottom[i].height);
		}
	}
	
	function drawScore(){
		ctx.fillStyle = 'white';
		ctx.font = "14px Courier";
		ctx.fillText('Recorrido: '+Math.round(moved), 20, 20);	
	}
	
	function drawHeli(){
		// Draw the helicopter
		ctx.fillStyle = 'red';
		ctx.beginPath();
		ctx.arc(WIDTH/2,heli.h,heli.rad,0,Math.PI*2,true);
		ctx.closePath();
		ctx.fill();
	}
	
	function frame(){
		clear();
		drawHeli();
		draw();
		// Now move all blocks, then add a new one and remove the first one
		var total = terrainTop.length;
		
		// Colision:
		if(heli.h-heli.rad <= terrainTop[Math.floor(total/2)].height || heli.h+heli.rad >= HEIGHT-terrainBottom[Math.floor(total/2)].height){
			clearInterval(drawInterval)
			ctx.fillStyle = 'red';
			ctx.font = "60px Courier";
			ctx.fillText('GAME OVER', WIDTH/2-180, HEIGHT/2+20);	
		}
		if(heli.speed > heli.maxSpeed) heli.speed = heli.maxSpeed;
		
		// Make the heli fall:
		heli.speed += heli.force;
		heli.h += heli.speed;
		
		if(heli.h+heli.rad>HEIGHT){
			heli.h = HEIGHT-heli.rad;
			heli.speed = 0;
		}
		
		// Update forces
		//heli.forceDown = moveSpeed/10.6;
		//heli.forceUp = heli.forceDown-0.01;
		
		// Move all x
		for(var i=0;i<total;i++){
			terrainTop[i].x -= moveSpeed;
			terrainBottom[i].x -= moveSpeed;
		}
		moved += moveSpeed;
		if(terrainTop[0].x+blockWidth<0){
			genObstacleTop(total);
			genObstacleBottom(total);
			terrainTop.splice(0,1);
			terrainBottom.splice(0,1);
		}
		
		drawScore();
		if(moveSpeed<maxSpeed) moveSpeed += incrementSpeed;
		if(maxDif<maxEndDif) maxDif += varIncrement;
		if(minSpace>minSpaceEnd) minSpace -= varIncrement;
		if(maxSpace>maxSpaceEnd) maxSpace -= varIncrement;
	}
	
	// Actualizadores
	$(document).mousedown(mouseDown);
	$(document).mouseup(mouseUp);
	$(document).keydown(onKeyDown);
	$(document).keyup(onKeyUp);
	frame();
	$('canvas').click(function(event){
		event.preventDefault();
		if(!started){
			drawInterval = setInterval( frame, 1 );
			started = true;
		}
	});
}
-->
</script>
<div id="content">
<h1>Juego de la caverna:</h1>
<p>Mantén pulsado el ratón para que la bola suba, suelta para que caiga. Según pase el tiempo avanzará más rápido. Desarrollado completamente con HTML5 Canvas</p>
<p>También puedes jugar con la Flecha superior, incluso para iniciar el juego.</p>
<canvas id="game" width="500" height="300"></canvas>
</div>