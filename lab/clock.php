<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Clock test</title>
<style type="text/css">
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
#viewport{
	width:100%;
	height:100%;
	position:relative;
}
.view{
	width:300px;
	height:100%;
	position:absolute;
	top:0;
	overflow:hidden;
	border-left:#333 solid 1px;
	border-right:#333 solid 1px;
	z-index:1;
}
#visor{
	background:rgba(255,255,255,0.1);
	border-top:#FFF solid 1px;
	border-bottom:#FFF solid 1px;
	height:300px;
	width:100%;
	position:absolute;
	top:45%;
	left:0;
	z-index:2;
	box-shadow:#000 0 0 50px;
}
.numBlock{
	display:block;
	font-size:130px;
	line-height:200px;
	text-indent:24px;
	color:#FFF;
	height:200px;
	width:200px;
	overflow:hidden;
	font-family:"Courier New", Courier, monospace;
	text-shadow:#000 -1px -2px;
}
.stripe{
	position:absolute;	
}
#fps{
	font-family:"Courier New", Courier, monospace;
	font-size:13px;
	position:absolute;
	top:3px;
	right:3px;	
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
var fps = 0, now, lastUpdate = (new Date)*1 - 1;
var fpsFilter = 50;
var cnClock = {
	viewport : {
		h : 0,
		w : 0
	},
	block : {
		w : 200
	},
	moveInterval : null,
	init : function(){
		this.adjust();
		$(window).resize(function(e) {
			cnClock.adjust();
		});
		this.stripe.create('hour','next');
		this.stripe.create('minute','next');
		this.stripe.create('second','next');
		
		this.stripe.create('hour','current');
		this.stripe.create('minute','current');
		this.stripe.create('second','current');
		
		this.moveInterval = setInterval(function(){
			cnClock.stripe.move();
			var thisFrameFPS = 1000 / ((now=new Date) - lastUpdate);
			fps += (thisFrameFPS - fps) / fpsFilter;
			lastUpdate = now * 1 - 1;
		},1000/60);
		
		setInterval(function(){ $('#fps').text('FPS: '+fps.toFixed(0)); }, 500); 
	},
	adjust : function(){
		// Adjust all sizes
		this.viewport.h = $(window).height();
		this.viewport.w = $(window).width();
		
		// Center visors
		var center = this.viewport.w/2;
		var hourLeft = center - this.block.w/2 - this.block.w;
		$('#hourView').css({left : hourLeft, width : this.block.w});
		$('#minuteView').css({left : hourLeft+this.block.w, width : this.block.w});
		$('#secondView').css({left : hourLeft+this.block.w*2, width : this.block.w});
		
		$('#viewport').css({
			height : cnClock.viewport.h+'px',
			width : cnClock.viewport.w+'px'
		});
		
		$('#visor').css({
			top : this.viewport.h/2-this.block.w/2,
			height : this.block.w
		});
	},
	stripe : {
		hour : 4801,
		minute : 12001,
		create : function(type,which){
			// Creates a new number stripe up to num
			var draw = '<div class="stripe" id="'+type+'_'+which+'">';
			var num = 60;
			if(type=='hour') num = 24;
			for(var i=0;i<num;i++){
				if(i<10) i = '0'+i;
				draw += '<div class="numBlock">'+i+'</div>';	
			}
			draw += '</div>';
			$('#'+type+'View').append(draw);
		},
		move : function(){
			// Calculates each position and apply
			// I want it continuous, so I'll use all digits
			var d = new Date();
			var ms = d.getMilliseconds();
			var s = d.getSeconds() + ms/1000;
			var m = d.getMinutes() + s/60;
			var h = d.getHours();
			// Current offset
			var curOffset = cnClock.viewport.h/2-cnClock.block.w/2;
			// Calculate offset
			var hourOffset = -h*cnClock.block.w + curOffset;
			var minuteOffset = -m*cnClock.block.w + curOffset;
			var secondOffset = -s*cnClock.block.w + curOffset;
			// Apply to current and next
			$('#hour_current').css({top:hourOffset});
			$('#hour_next').css({top:this.hour+hourOffset});
			
			$('#minute_current').css({top:minuteOffset});
			$('#minute_next').css({top:this.minute+minuteOffset});
			
			$('#second_current').css({top:secondOffset});
			$('#second_next').css({top:this.minute+secondOffset});
		}
	}
};
$(document).ready(function(e) {
	cnClock.init();
});
</script>
</head>

<body>
<div id="fps">FPS: 0</div>
<div id="viewport">
	<div id="visor"></div>
	<div class="view" id="hourView"></div>
	<div class="view" id="minuteView"></div>
	<div class="view" id="secondView"></div>
</div>
</body>
</html>