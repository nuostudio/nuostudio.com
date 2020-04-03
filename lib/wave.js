<!--

//
google.setOnLoadCallback(canvasWave);
// INICIO
function canvasWave(){
	// VARIABLES
	var ctx;
	var WIDTH;
	var HEIGHT;
	
	var canvasMinX;
	var canvasMaxX;
	
	var canvasMinY;
	var canvasMaxY;
	
	var color1 = 'rgb(204,204,204)';
	var color2 = 'rgba(10,10,10,0)';
	var reloadColor = false;
	var imgd = false; // Cache for logo
	
	// Objetos flotantes:
	var totalBoats = 1;
	var boats = new Array(); // Cada elemento = 1 barco, con coordenada y
	var maxBoatSpeed = 1; // Velocidad maxima de los barcos
	
	var rotation = 0; // Angulo de giro
	
	var ms = {x:0, y:0}; // Mouse speed
	var mp = {x:0, y:0}; // Mouse position
	
	var particles = new Array();
	
	var fps = 0, now, lastUpdate = (new Date)*1 - 1;
	var fpsFilter = 50;
	
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
	
	/** Wave settings */
	var density = .74;
	var friction = 1.12;
	var pull = 0.06; // For mouse pull
	var pullArea = 200; // Area of effect for mouse pull
	var detail = Math.round( WIDTH/60 ); // Particles to be used
	var waterDensity = 1.08;
	var airDensity = 1.02;
	var impulseInterval = 500; // The interval between random impulses being inserted into the wave to keep it moving
	var yOffset = 420; // Distancia de arriba
	var img = new Image();
	var imgData = new Array();
	
	// Para el cambio de color
	var defaultColor = 255; // 0 para negro, 255 para blanco
	var colorTolerance = 450; // Tolerancia (Suavidad en el cambio)
	
	// Funciones importantes:
	function clear() {
		ctx.clearRect(0, 0, WIDTH, HEIGHT);
	}
	
	function resizeCanvas(e) {
		WIDTH = window.innerWidth;
		HEIGHT = window.innerHeight;
		
		$("#bg").attr('width',WIDTH);
		$("#bg").attr('height',HEIGHT);
		
		imgData.x = WIDTH/2-250;
		
		// Generate particles array
		for( var i = 0; i < detail+1; i++ ) {
			particles[i].x = WIDTH / (detail-4) * (i-2);
			particles[i].y = yOffset;
			
			particles[i].original.x = particles[i].x;
			particles[i].original.y = particles[i].y;
		}
	}
	
	function mouseMove(e) {
		ms.x = Math.max( Math.min( e.pageX - mp.x, 40 ), -40 );
		ms.y = Math.max( Math.min( e.pageY - mp.y, 40 ), -40 );
		
		mp.x = e.pageX - canvasMinX;
		mp.y = e.pageY - canvasMinY;
	}
	
	function addImpulse( positionX, forceY ) {
		var particle = particles[Math.round( positionX / WIDTH * particles.length )];
		
		if( particle ) {
			particle.force.y += forceY;
		}
	}
	
	for( var i = 0; i < detail+1; i++ ) {
		particles.push( { 
			x: WIDTH / (detail-4) * (i-2), // Pad by two particles on each side
			y: HEIGHT*yOffset,
			original: {x: 0, y: yOffset},
			velocity: {x: 0, y: Math.random()*3}, // Random for some initial movement in the wave
			force: {x: 0, y: 0},
			mass: 10
		} );
	}
	
	function draw(e) {
		var gradientFill = ctx.createLinearGradient(WIDTH*.5,HEIGHT*0.2,WIDTH*.5,HEIGHT);
		gradientFill.addColorStop(0,color1);
		gradientFill.addColorStop(1,color2);

		ctx.fillStyle = gradientFill;
		ctx.beginPath();
		ctx.moveTo(particles[0].x, particles[0].y);
		
		var len = particles.length;
		var i;
		
		var current, previous, next;
		
		for( i = 0; i < len; i++ ) {
			current = particles[i];
			previous = particles[i-1];
			next = particles[i+1];
			
			if (previous && next) {
				
				var forceY = 0;
				
				forceY += -density * ( previous.y - current.y );
				forceY += density * ( current.y - next.y );
				forceY += density/15 * ( current.y - current.original.y );
				
				current.velocity.y += - ( forceY / current.mass ) + current.force.y;
				current.velocity.y /= friction;
				current.force.y /= friction;
				current.y += current.velocity.y;
				
				var distance = distanceBetween( mp, current );
				
				if( distance < pullArea ) {
					// Mouse has entered pullArea, insert Impulse
					var distance = distanceBetween( mp, {x:current.original.x, y:current.original.y} );
					
					ms.x = ms.x * .6;
					ms.y = ms.y * .6;
					
					current.force.y += (pull * ( 1 - (distance / pullArea) )) * ms.y;
				}
				
				// cx, cy, ax, ay
				ctx.quadraticCurveTo(previous.x, previous.y, previous.x + (current.x - previous.x) / 2, previous.y + (current.y - previous.y) / 2);
			}
			
		}
		
		ctx.lineTo(particles[particles.length-1].x, particles[particles.length-1].y);
		ctx.lineTo(WIDTH, HEIGHT);
		ctx.lineTo(0, HEIGHT);
		ctx.lineTo(particles[0].x, particles[0].y);
		
		ctx.fill();
	}
	
	// Init. Boats array
	for(var i=0;i<totalBoats; i++){
		var dx = Math.random()-0.5;
		// Direccion de movimiento
		if(dx<0) dx = -1; else dx = 1;
		boats.push({
				x : Math.random()*WIDTH,
				speed : Math.random()*maxBoatSpeed,
				dx : dx
		});
	}
	
	function drawBoats(){
		// Hay que desplazarlos ligeramente,
		// determinar su posicion en el eje y
		for(var i=0;i<boats.length; i++){
			if(boats[i].x > WIDTH-50 || boats[i].x < 0) boats[i].dx *= -1; // Turn around
			boats[i].x += boats[i].dx*boats[i].speed; // Move boat
			var y = closestParticle(boats[i].x)-20;
			// Tenemos las coordenadas, dibujamos el barco
			ctx.fillStyle = 'red';
			ctx.fillRect(boats[i].x,y,50,20); // Draw boat
		}	
	}
	
	function closestParticle(x){
		var m = WIDTH;
		var r;
		var len = particles.length;
		for( i = 0; i < len; i++ ) {
			if(Math.abs(x-particles[i].x)<m){
				m = particles[i].x;
				r = particles[i].y;
			}
		}
		return r;
	}
	
	function impulse() {
		if( ms.x < 6 || ms.y < 6 ) {
			var forceRange = 5; // -value to +value
			addImpulse( Math.random() * WIDTH, (Math.random()*(forceRange*2)-forceRange ) );
		}
	}
	
	function mouseNav(e){
		// Mouse over the nav buttons
		// insert impulse above it
		var forceRange = 5; // -value to +value
		addImpulse( e.pageX, (Math.random()*(forceRange*2)-forceRange ) );	
	}
	
	function distanceBetween(p1,p2){
		// Approximation by using octagons approach
		// (1 + 1/(4-2*sqrt(2)))/2 * min((1 / sqrt(2))*(|x|+|y|), max (|x|, |y|))
		var x = p2.x-p1.x;
		var y = p2.y-p1.y;
		return 1.426776695*Math.min(0.7071067812*(Math.abs(x)+Math.abs(y)), Math.max (Math.abs(x), Math.abs(y)));	
	}
	
	function frame(){
		clear();
		website();
		draw();
		//drawBoats();
		// Now calculate FPS
		var thisFrameFPS = 1000 / ((now=new Date) - lastUpdate);
		fps += (thisFrameFPS - fps) / fpsFilter;
		lastUpdate = now * 1 - 1;
	}
	
	imgData.x = WIDTH/2-250;
	imgData.y = 100;
	img.src = 'img/wave.gif';
	
	function website(){
		// Once it's loaded draw the website on the canvas.
		ctx.drawImage(img, imgData.x, imgData.y);
		
		// Test of imageData
		try {
			if(reloadColor){
				ctx.drawImage(img, imgData.x, imgData.y);
				imgd = ctx.getImageData(imgData.x, imgData.y, 500, 301);  
				var pix = imgd.data;
				// Ahora podemos modificar la imagen!! :D
				var rgb = getColor();
				// Make sure the image is bright (Works better)
				var maxRGB = 'r';
				if(rgb['g']>rgb['r']) maxRGB = 'g';
				if(rgb['b']>rgb[maxRGB]) maxRGB = 'b';
				rgb[maxRGB] = 255;
				if(rgb['r']+rgb['g']+rgb['b']<50){
					rgb['r'] = 255; // red channel
					rgb['g'] = 255; // green channel
					rgb['b'] = 255; // blue channel
				}
				for (var i = 0; n = pix.length, i < n; i += 4) {
					if(pix[i]+pix[i+1]+pix[i+2]>=(defaultColor*3-colorTolerance)){
						pix[i] = rgb['r']; // red channel
						pix[i+1] = rgb['g']; // green channel
						pix[i+2] = rgb['b']; // blue channel
						//pix[i+3] = 255; // alpha channel
					}
				}
				reloadColor = false;
				
			}
			if(imgd) ctx.putImageData(imgd, imgData.x, imgData.y);
		}catch(e){
			console.log('Cant access: '+e);	
		}
	}
	
	function getColor(){
		var rgb = color1.substr(4).replace(')','').split(',');
		
		var r = parseInt(rgb[0]);
		var g = parseInt(rgb[1]);
		var b = parseInt(rgb[2]);
		
		return {'r':r,'g':g,'b':b};
	}
	
	/*$('#colors a').click(function(event){
		event.preventDefault();
		// Change wave color
		var original = $(this).css('backgroundColor');
		var rgb = original.substr(4).replace(')','').split(',');
		
		var r = parseInt(rgb[0]);
		var g = parseInt(rgb[1]);
		var b = parseInt(rgb[2]);
		
		color1 = original;
		color2 = 'rgba('+r+','+g+','+b+',0)';
		changed = true;
		reloadColor = true;
	});*/
	$('#color1').ColorPicker({
		onChange: function (hsb, hex, rgb) {
			color1 = 'rgb('+rgb.r+','+rgb.g+','+rgb.b+')';
			color2 = 'rgba('+rgb.r+','+rgb.g+','+rgb.b+',0)';
			reloadColor = true;
			$('#color1').css('backgroundColor',color1);
		},
		color: '#000'
	});
	
	// Display fps
	setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 500); 
	
	// Actualizadores
	$(window).resize(resizeCanvas);
	$(document).mousemove(mouseMove);
	$('header').hover(function(){
		impulse();
	});
	$('nav a').hover(function(){
		impulse();
	});
	drawInterval = setInterval( frame, 1 );
	twitchInterval = setInterval( impulse, impulseInterval );
	resizeCanvas();
}