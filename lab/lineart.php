<style type="text/css">
#fps{
	font-family:"Courier New", Courier, monospace;
	font-size:13px;
	position:absolute;
	top:3px;
	right:3px;	
}
#config{
	position:absolute;
	bottom:0;
	left:40%;
	background:#FFF;
	color:#000;
	padding:5px;
	border-top-right-radius:10px;
	border-top-left-radius:10px;
}
#chrome{
	position:absolute;
	bottom:10px;
	left:10px;	
}
</style>
<script type="text/javascript">
$(document).ready(function(e) {
// VARIABLES
	var ctx;
	var WIDTH;
	var HEIGHT;
	
	var canvasMinX;
	var canvasMaxX;
	
	var canvasMinY;
	var canvasMaxY;
	
	var rotation = 0; // Angulo de giro
	
	var ms = {x:0, y:0}; // Mouse speed
	var mp = {x:0, y:0}; // Mouse position
	
	var fps = 0, now, lastUpdate = (new Date)*1 - 1;
	var fpsFilter = 100;
	
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
	
	// Funciones importantes:
	function clear() {
		ctx.clearRect(0, 0-HEIGHT/2, WIDTH, HEIGHT);
	}
	
	function circle(x,y,rad,color){
		// Circulo
		ctx.fillStyle = color;
		ctx.beginPath();
		ctx.arc(x,y,rad,0,Math.PI*2,true);
		ctx.closePath();
		ctx.fill();
	}
	
	function fade() {
		ctx.fillStyle="rgba(0,0,0,0.01)";
		ctx.fillRect(0, 0, WIDTH, HEIGHT);
	}
	
	function resizeCanvas(e) {
		WIDTH = window.innerWidth;
		HEIGHT = window.innerHeight;
		
		$("#bg").attr('width',WIDTH);
		$("#bg").attr('height',HEIGHT);
		
		ctx.translate(0,HEIGHT/2);
	}
	
	function mouseMove(e) {
		ms.x = Math.max( Math.min( e.pageX - mp.x, 40 ), -40 );
		ms.y = Math.max( Math.min( e.pageY - mp.y, 40 ), -40 );
		
		mp.x = e.pageX - canvasMinX;
		mp.y = e.pageY - canvasMinY;
	}
	var points = 100;
	var t = 0;
	var dp = 0.01;
	var outline = true;
	// Move 0,0 to the center of the screen
	function draw(e) {
		t = t+0.005;
		var ystep = HEIGHT/points;
		var xstep = WIDTH/points;
		// Styles
		ctx.lineWidth = 1;
		ctx.strokeStyle = '#fff';		
		// Sine method
		// Axis
		ctx.strokeStyle = 'rgba(255,0,0,0.2)';
		ctx.beginPath();
		ctx.moveTo(0,0);
		ctx.lineTo(WIDTH,0);
		ctx.moveTo(0,HEIGHT/2);
		ctx.lineTo(0,-HEIGHT/2);
		//ctx.lineTo(0,0);
		ctx.stroke();
		
		if(outline){
			// Draw sine wave
			ctx.strokeStyle = '#09F';
			for(var p = 0; p < points;p++){
				// Draw the line
				ctx.beginPath();
				var y = (HEIGHT/2-10)*Math.sin(2*Math.PI*(1/WIDTH)*p*xstep+t);
				ctx.moveTo(p*xstep,y);
				ctx.lineTo((p+1)*xstep,(HEIGHT/2-10)*Math.sin(2*Math.PI*(1/WIDTH)*(p+1)*xstep+t));
				//ctx.lineTo(0,0);
				ctx.stroke();
			}
		}
		
		// Draw grid
		ctx.strokeStyle = '#111';
		for(var p = 0; p < points;p++){
			// Draw the line
			ctx.beginPath();
			ctx.moveTo(p*xstep,HEIGHT/2);
			ctx.lineTo(p*xstep,-HEIGHT/2);
			//ctx.lineTo(0,0);
			ctx.stroke();
			// Axis circles
			circle(p*xstep,0,3,'rgba(255,0,0,0.2)');
		}
		
		for(var p = 0; p < points;p++){
			// Change color
			ctx.strokeStyle = 'rgba(255,255,255,'+(0.5*Math.sin(p*3*Math.PI/(2*points))+0.5)+')';
			// Draw the line
			ctx.beginPath();
			var y1 = (HEIGHT/2-10)*Math.sin(2*Math.PI*(1/WIDTH)*p*xstep+t);
			var y2 = (HEIGHT/2-10)*Math.sin(2*Math.PI*(1/WIDTH)*(WIDTH/2+p*xstep)+t);
			ctx.moveTo(p*xstep,y1);
			ctx.lineTo(WIDTH/2+p*xstep,y2);
			ctx.stroke();
			circle(p*xstep,y1,3,'#09F');
			circle(WIDTH/2+p*xstep,y2,3,'#09F');
		}
	}
	
	function frame(){
		clear();
		draw();
		// Now calculate FPS
		var thisFrameFPS = 1000 / ((now=new Date) - lastUpdate);
		fps += (thisFrameFPS - fps) / fpsFilter;
		lastUpdate = now * 1 - 1;
	}
	
	// Display fps
	setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 500); 
	
	// Actualizadores
	resizeCanvas();
	$(window).resize(resizeCanvas);
	$(document).mousemove(mouseMove);
	drawInterval = setInterval( frame, 1 );
	draw();
	$('#config input').change(function(){
		switch($(this).attr('id')){
			case 'pointsNum':
				points = $(this).val();
				break;
			case 'outline':
				outline = $(this).attr('checked');
				break;
		}
		return true;
	});
});
</script>
<div id="config">
	<input type="range" min="1" max="200" step="1" value="100" name="pointsNum" id="pointsNum" title="Number of points" />
	<label><input type="checkbox" name="outline" id="outline" checked /> Outline</label>
</div>
<div id="fps">FPS: 0</div>
<div id="chrome">
	<a href="http://www.chromeexperiments.com/detail/line-art" title="Line Art is a Chrome Experiment"><img src="/img/chrome-trans.png" width="107" height="56" alt="This is a Chrome Experiment" /></a>
</div>
<canvas width="100%" height="100%" id="bg" style="color:#09F"></canvas>
