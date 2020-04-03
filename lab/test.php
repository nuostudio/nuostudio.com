<?php
/*
MegaVideo Video Scraper
Premium Account Required
Written by Aziz S. Hussain
@
www.AzizSaleh.com
Produced under LGPL license
@
http://www.gnu.org/licenses/lgpl.html

Example Use of the class: megavideo.class.php
*/

# Include Main Class
require_once('megavideo.class.php');

# Setup the URL
$newVideo = new megaVideo('http://www.megavideo.com/?v=6PTHEVUY');
# Work to get the link
$newVideo->doScrape();
# You now have the link
echo "
<script type='text/javascript' src='lib/jwplayer/jwplayer.js'></script>

<div id='mediaplayer'></div>

<script>
  jwplayer('mediaplayer').setup({
    'flashplayer': 'lib/jwplayer/player.swf',
    'id': 'playerID',
    'width': '480',
    'height': '270',
    'file': '".$newVideo->getLink()."'
  });
</script>";
# The link can be used for download or stream
# To use for stream, you will need a flash player like JW Flash Player
# http://www.longtailvideo.com/players/jw-flv-player/
?>