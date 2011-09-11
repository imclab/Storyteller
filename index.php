<!DOCTYPE HTML>
<html>
<head>
  <script src="http://framey.com/javascripts/swfobject.js"></script>
  <!--
  <script src="http://popcornjs.org/code/dist/popcorn-complete.min.js"></script>
  <script src="popcorn.sequence.js"></script>
  -->
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
  <script type="text/javascript" src="swf.js"></script>
  <script type="text/javascript" src="framey.js"></script>
  <script type="text/javascript" src="http://fgnass.github.com/spin.js/spin.min.js"></script>
  
<style type="text/css">
html, body{
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  background: black;
}

.thumbnail{
  display: inline;
  margin: 5px;
}

#thumbnails{
  position: relative;
  top: 500px;
  margin: 0 auto;
  width: 800px;
  text-align: center;
  overflow: hidden;
  height: 110px;
}

#megaplaya{
  position: absolute;
  top: 0;
}

#theframeyRecorderContainer_1{
 visibility: hidden !important;
 position: absolute;
 top: 0;
}

#tvContainer{
  position: relative;
  margin: 0 auto;
  top: 20px;
  height: 350px;
  width: 400px;
  overflow: hidden;
  text-align: center;
}

#loading{
  background: black;
  visibility: hidden;
  height: 400px;
  width: 400px;
  position: relative;
  margin: 0 auto;
  top: 0;
}
</style>
  
  
  <?php
  require("opendb.php");
  require("framey.php");
  
  $all_mp4 = array();
  $all_thumbnails = array();
  $query = "SELECT * FROM stories";
  $result = mysql_query($query);
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
      array_push($all_mp4, $row[2]); 
      array_push($all_thumbnails, $row[4]); 
  }


  echo "<div id='thumbnails'>";
  foreach($all_thumbnails as $key=>$value){
    echo "<img onMouseOut='dehover($key)' onMouseOver='hover($key)' onClick='play($key)' id='$key' class='thumbnail' src='$value'></img>";
    }
    
  echo "<img id='-1' onMouseOut='dehover(-1)' onMouseOver='hover(-1)' onClick='reveal()' id='$key' class='thumbnail' src='blank.png' height='100px' width='100px'></img>";
    
  echo "</div>";
  
  $script = $script . "\n" . '<script type="text/javascript"> '. "\nvar allMp4 = new Array(";
  if (count($all_mp4)>1) {
    foreach ($all_mp4 as $key => $value){
        if ($key < (count($all_mp4)-1)){
            $script = $script .'"'. $value .'",';
        }
        else {
            $script = $script .'"'. $value .'"'. ");\n";
        }
    }
  } else {
    $script = $script . "1);\nallMp4[0]=" .'"'. $all_mp4[0] .'"'. ";\n";
  }
    
  echo $script . "</script>";
  ?>
  
<div id="tvContainer">
  <div id="<?= divid ?>"></div>
  <div id="megaplaya"></div>
  <div id="loading"></div>
  </div>

<script>
    
  var hover = function(key){
    document.getElementById(key).style.margin = "0px";
    document.getElementById(key).style.border = '5px solid green';
  }
  
  var dehover = function(key){
    document.getElementById(key).style.margin = "5px";
    document.getElementById(key).style.border = 'none';
  }
    
  var play = function(key){
    document.getElementById('theframeyRecorderContainer_1').style.visibility = "hidden !important";
    document.getElementById('megaplaya').style.visibility = "visible";
    megaplaya.api_pause();
    megaplaya.api_playQueueAt(key);
  }
  
  var reveal = function(){
    document.getElementById('theframeyRecorderContainer_1').style.visibility = "visible !important";
    document.getElementById('megaplaya').style.visibility = "hidden";
    megaplaya.api_pause();
  }
  
  var hoverNext = function(){
    console.log("worrd")
  }
  
  var count;
  var sources = [];
  for (index in allMp4){
    var source = {url: allMp4[index]}
    sources.push(source);
    console.log(source);
    count++;
  }


  $(document).ready(
    function() {
      $('#megaplaya').flash({
        swf: 'http://vhx.tv/swf/megaplaya_embed_beta.swf',
        width: 400,
        height: 300,
        allowFullScreen: true,
        allowScriptAccess: "always",
      });
    }
  );
  
  var megaplaya = false;
  function megaplaya_loaded(){
    megaplaya = $('#megaplaya').children()[0];
    megaplaya.api_playQueue(sources)
    // megaplaya.api_addEventListener('onNextVideo', hoverNext) //por la casey
  }
  
  //cant add videos to megaplaya
  //caching seamless
</script>
  
  
</head>
<body>

<script type="text/javascript">

    Framey.configure({
        api_key: "UDUS0YUL8DLSMGUWJEJC2RH9L",
        timestamp: "<?= $timestamp ?>",
        signature: "<?= $signature ?>"
    })

    Framey.renderRecorder("<?= $objid ?>",{
        id: "<?= $objid ?>",     // optional, "the_"+yourDivId by default
        max_time: 60,      // optional, 30 by default
    })


Framey.observe("publishSucceeded", function(session_data){
   publish_state();
 });

  var flashvars = {
  	api_key: "<?= $api_key ?>",
  	signature: "<?= $signature ?>",
  	time_stamp: "<?= $timestamp ?>",
  	session_data: "<?= $session_data_string ?>",
  	max_time: "<?= $max_record_time ?>"
  };
  var params = {
    'allowscriptaccess': 'always',
    "wmode": "transparent"
  };
  var attributes = {
    'id': "<?= $objid ?>",
    'name': "<?= $objid ?>"
  };
  swfobject.embedSWF("http://framey.com/recorder.swf", "<?= divid ?>", "340", "340", "8", "", flashvars, params, attributes);


var total = document.getElementsByClassName("thumbnail");
total = total.length - 1

var publish_state = function(){
  console.log(total)
  $.ajax({
    type: "GET",
    url: "poll.php",
    data: {total : total},
    timeout: "10000",
    success: function(data){
      document.getElementById("loading").style.visibility = "visible";
      if(data=="false"){
        publish_state();
      } else {
        window.location.reload();
      }
    }
  });
}


//spinner

var opts = {
  lines: 12, // The number of lines to draw
  length: 7, // The length of each line
  width: 5, // The line thickness
  radius: 10, // The radius of the inner circle
  color: '#FFF', // #rbg or #rrggbb
  speed: 1, // Rounds per second
  trail: 100, // Afterglow percentage
  shadow: true // Whether to render a shadow
};
var target = document.getElementById('loading');
var spinner = new Spinner(opts).spin(target);


</script>
  </body>
</html>



<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {

    var sources = [];
    var inVar = 0;
    var outVar = 10;
    for (index in allMp4){
      var source = {src: allMp4[index], in: inVar, out: outVar}
      inVar += 10;
      outVar += 10;
      sources.push(source);
      console.log(source)
    }
    

  var sources = [
     {
       src: "http://framey.com/videos/source/e95c6ad0-be37-012e-22b8-12313b0f15b0.mp4",  
       in: 0, 
       out: 5
     },
     {
       src: "http://framey.com/videos/source/dd4a5e40-be37-012e-22b8-12313b0f15b0.mp4",  
       in: 5,
       out: 20
     }
    ];


    var sequence = Popcorn.sequence( "footnotediv", sources );
    sequence.play();
  })
</script> 
  <div id="footnotediv"></div>

-->