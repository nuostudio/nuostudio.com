var exec = function(main){
	var imgd = main.canvas.ctx.getImageData(main.img.x, main.img.y, main.canvas.WIDTH, main.canvas.HEIGHT); 
	var pix = imgd.data, i=0, auxAvg;
	for(var y = 0; y < main.canvas.HEIGHT; y += main.strokeResolution){
		for(var x = 0; x < main.canvas.WIDTH; x += main.strokeResolution){
			// Draw strokes
			var rad = main.canvas.ctx.createRadialGradient(Math.round(x+main.strokeResolution/2),Math.round(main.img.y+y+main.strokeResolution/2),0.01,Math.round(x+main.strokeResolution/2),Math.round(main.img.y+y+main.strokeResolution/2), main.strokeResolution+20);
			rad.addColorStop(0, 'rgba('+main.avg[i][0]+','+main.avg[i][1]+','+main.avg[i][2]+',1)');
			rad.addColorStop(1, 'rgba('+main.avg[i][0]+','+main.avg[i][1]+','+main.avg[i][2]+',0)');
			main.circle(Math.round(x+main.innerMargin+main.strokeResolution/2),Math.round(main.img.y+y+main.strokeResolution/2), main.strokeResolution*10, rad);
			i++;
		}
	}
}
