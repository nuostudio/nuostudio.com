// Simulates watercolor painting (I hope)
var exec = function(main){
	var i=0, auxAvg;
	for(var y = 0; y < main.canvas.HEIGHT; y += main.strokeResolution){
		for(var x = 0; x < main.canvas.WIDTH; x += main.strokeResolution){
			// Draw strokes
			auxAvg = (main.avg[i][0]+main.avg[i][1]+main.avg[i][2])/3;
			main.circle(Math.round(x+main.strokeResolution/2)+main.randPosition*Math.cos(Math.random()),Math.round(main.img.y+y+main.strokeResolution/2)+main.randPosition*Math.sin(Math.random()),(255-auxAvg)*(main.strokeResolution-main.minRadius)/255+main.minRadius+main.randRadius*Math.random(),'rgba('+main.avg[i][0]+','+main.avg[i][1]+','+main.avg[i][2]+',0.5)');
			i++;
		}
	}
}
