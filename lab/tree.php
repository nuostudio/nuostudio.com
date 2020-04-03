<style type="text/css">
#config{
	position:absolute;
	top:0;
	left:40%;
	background:#FFF;
	color:#000;
	padding:10px;
	border-bottom-right-radius:10px;
	border-bottom-left-radius:10px;
}
</style>
<script type="text/javascript" src="http://dat-gui.googlecode.com/git/build/dat.gui.min.js"></script>
<script type="text/javascript">
var TreeGen = function(){
	// CONFIGURATION
	this.loss = 0.03;		// Width loss per cycle
	this.minSleep = 10;		// Min sleep time (For the animation)
	this.branchLoss = 0.8;	// % width maintained for branches
	this.mainLoss = 0.8;	// % width maintained after branching
	this.speed = 0.3;		// Movement speed
	this.newBranch = 0.8;	// Chance of not starting a new branch 
	this.colorful = false;	// Use colors for new trees
	this.fastMode = true;	// Fast growth mode
	this.fadeOut = true;	// Fade slowly to black
	this.fadeAmount = 0.05;	// How much per iteration
	this.autoSpawn = true;	// Automatically create trees
	this.spawnInterval = 250;// Spawn interval in ms
	this.initialWidth = 10;	// Initial branch width
	
// VARS
	this.canvas = {
		ctx : $('#bg')[0].getContext("2d"),
		WIDTH : $("#bg").width(),
		HEIGHT : $("#bg").height(),
		canvasMinX : $("#bg").offset().left,
		canvasMaxX : this.canvasMinX + this.WIDTH,
		canvasMinY : $("#bg").offset().top,
		canvasMaxY : this.canvasMinY + this.HEIGHT
	};
	this.mouse = {
		s : {x:0, y:0},	// Mouse speed
		p : {x:0, y:0}	// Mouse position
	}
	var fps = 0, now, lastUpdate = (new Date)*1 - 1,
		fpsFilter = 100;
	
	this.fade = function() {
		if(!this.fadeOut) return true;
		this.canvas.ctx.fillStyle="rgba(0,0,0,"+this.fadeAmount+")";
		this.canvas.ctx.fillRect(0, 0, this.canvas.WIDTH, this.canvas.HEIGHT);
	}
	
	this.resizeCanvas = function() {
		this.canvas.WIDTH = window.innerWidth;
		this.canvas.HEIGHT = window.innerHeight;
		
		$("#bg").attr('width',this.canvas.WIDTH);
		$("#bg").attr('height',this.canvas.HEIGHT);
	}
		
	this.newColor = function(){
		if(!this.colorful) return '#fff';
		return '#'+Math.round(0xffffff * Math.random()).toString(16);
	}
	
	this.mouseMove = function(e) {
		this.mouse.s.x = Math.max( Math.min( e.pageX - this.mouse.p.x, 40 ), -40 );
		this.mouse.s.y = Math.max( Math.min( e.pageY - this.mouse.p.y, 40 ), -40 );
		
		this.mouse.p.x = e.pageX - this.canvas.canvasMinX;
		this.mouse.p.y = e.pageY - this.canvas.canvasMinY;
	}
	
	// Starts a new branch from x,y. w is initial w
	// lifetime is the number of computed cycles
	this.branch = function(x,y,dx,dy,w,growthRate,lifetime,branchColor,treeObj){
		this.canvas.ctx.lineWidth = w-lifetime*this.loss;
		this.canvas.ctx.beginPath();
		this.canvas.ctx.moveTo(x,y);
		if(this.fastMode) growthRate *= 0.5;
		// Calculate new coords
		x = x+dx;
		y = y+dy;
		// Change dir
		dx = dx+Math.sin(Math.random()+lifetime)*this.speed;
		dy = dy+Math.cos(Math.random()+lifetime)*this.speed;
		// Check if branches are getting too low
		if(w<6 && y > this.canvas.HEIGHT-Math.random()*(0.3*this.canvas.HEIGHT)) w = w*0.8;
		//
		this.canvas.ctx.strokeStyle = branchColor;
		this.canvas.ctx.lineTo(x,y);
		this.canvas.ctx.stroke();
		// Generate new branches
		// they should spawn after a certain lifetime has been met, although depending on the width
		if(lifetime > 5*w+Math.random()*100 && Math.random()>this.newBranch){
			setTimeout(function(){
				treeObj.branch(x,y,2*Math.sin(Math.random()+lifetime),2*Math.cos(Math.random()+lifetime),(w-lifetime*treeObj.loss)*treeObj.branchLoss,growthRate+100*Math.random(),0,branchColor, treeObj);
				// When it branches, it looses a bit of width
				w *= this.mainLoss;
			},2*growthRate*Math.random()+this.minSleep);
		}
		// Continua la rama
		if(w-lifetime*this.loss>=1) setTimeout(function(){ treeObj.branch(x,y,dx,dy,w,growthRate,++lifetime,branchColor, treeObj); },growthRate);
	}
	
	this.resizeCanvas();
}
	
$(document).ready(function(e) {
	
	var tree = new TreeGen();
	var gui = new dat.GUI();
	var f1 = gui.addFolder('Tree growth');
	f1.add(tree, 'loss', 0, 0.1);
	f1.add(tree, 'newBranch',0,1);
	f1.add(tree, 'branchLoss',0,1);
	f1.add(tree, 'mainLoss',0,1);
	f1.add(tree, 'initialWidth',1,50);
	var autoMode = f1.add(tree, 'autoSpawn');
	var autoInterval = f1.add(tree,'spawnInterval',0,1000);
	var f2 = gui.addFolder('Visuals');
	f2.add(tree, 'minSleep',0,50);
	f2.add(tree, 'colorful');
	f2.add(tree, 'speed',0,1);
	f2.add(tree, 'fastMode');
	var f3 = gui.addFolder('Fading');
	f3.add(tree, 'fadeOut');
	f3.add(tree, 'fadeAmount',0,0.3);
	
	// Stop spawn interval
	autoMode.onChange(function(val){
		clearInterval(spawnInterval);
		if(val) spawn();
	});
	
	autoInterval.onFinishChange(function(){
		clearInterval(spawnInterval);
		spawn();
	});
	
	var spawnInterval;
	
	function spawn(){
		// Start up
		if(!tree.autoSpawn) return true;
		spawnInterval = setInterval(function(){
			// branch(x,y,dx,dy,w,growthRate,lifetime,branchColor,treeObj)
			tree.branch(Math.random()*tree.canvas.WIDTH,tree.canvas.HEIGHT,0,-Math.random()*3,tree.initialWidth*Math.random(),30,0,tree.newColor(),tree);
		}, tree.spawnInterval);
	}
	spawn();
	setInterval(function(){
		tree.fade();
	}, 250);
	
	$(window).mousemove(function(e){ tree.mouseMove(e); });
	$(window).resize(function(e){tree.resizeCanvas(); });
	
	// Branch adding:
	$('canvas').click(function(e){
		e.preventDefault();
		tree.branch(tree.mouse.p.x, tree.canvas.HEIGHT, 0, -Math.random()*3, Math.random()*tree.initialWidth,5,0,tree.newColor(),tree);
	});
});
</script>
<canvas width="100%" height="100%" id="bg" style="color:#09F"></canvas>
