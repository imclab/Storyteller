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
  width: 150px;
  height: auto;
  position: relative;
}

#thumbnails{
  position: relative;
  top: 500px;
  margin: 0 auto;
  width: 900px;
  text-align: left;
  overflow: hidden;
  height: 160px;
  background: red;
}

#thumbnailInner{
  background: green;
  margin-left: 370px;
  width: 1000px;
}

#gradientLeft{
  position: absolute;
  top: 0;
  left: 0;
  height: 160px;
  width: 20px;
  z-index: 9999;
  background-image: -webkit-gradient(linear, 100% 50%, 0% 50%, from(rgba(0,0,0,0)), to(#000000));
}

#gradientRight{
  position: absolute;
  top: 0;
  right: 0;
  height: 160px;
  width: 20px;
  z-index: 9999;
  background-image: -webkit-gradient(linear, 0% 50%, 100% 50%, from(rgba(0,0,0,0)), to(#000000));
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
  top: -100px;
  height: 500px;
  width: 600px;
  overflow: hidden;
  text-align: center;
}

#loading{
  background: green;
  visibility: hidden;
  height: 500px;
  width: 600px;
  position: relative;
  margin: 0 auto;
  overflow: hidden;
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
    echo "<div id='thumbnailInner'>";
    echo "<div id='gradientLeft'></div>";
  foreach($all_thumbnails as $key=>$value){
    echo "<img id='$key' class='thumbnail' src='$value'></img>";
    }
    
  echo "<img id='". count($all_mp4) ."' onClick='reveal()' id='$key' class='thumbnail' src='blank.png'></img>";
        echo "<div id='gradientRight'></div>";
      echo "</div>";
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
  
  $script = $script . 'var count=' . count($all_mp4); 
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
    if (key == count){
      reveal();
      return;
    }
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
  
  var thumbnails = $("#thumbnailInner");
  var key = 0;
  
  var sources = [];
  for (index in allMp4){
    var source = {url: allMp4[index]}
    sources.push(source);
    console.log(source);
  }

  $(document).keydown(function(e) {
    if (e.keyCode==37){
      if (key == 0){
        return; 
      }
      dehover(key);
      key--;
      hover(key)
      var currentMargin = thumbnails.css('margin-left','+=160');
      return false;

      //right
    } else if (e.keyCode==39){
      if (key == (count)){
        return;
      }
      dehover(key);
      key++;
      hover(key)
      var currentMargin = thumbnails.css('margin-left','-=160');
      return false;
      
      //enter
    } else if (e.keyCode==13){
      play(key);
    }
  });

  //initialize
  hover(0);

  $(document).ready(
    function() {
      $('#megaplaya').flash({
        swf: 'http://vhx.tv/swf/megaplaya_embed_beta.swf',
        width: 600,
        height: 400,
        allowFullScreen: true,
        allowScriptAccess: "always",
      });
    }
  );
  
  var megaplaya = false;
  function megaplaya_loaded(){
    megaplaya = $('#megaplaya').children()[0];
    megaplaya.api_playQueue(sources)
    megaplaya.api_addEventListener('onVideoLoad', hoverNext) //por la casey
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
  swfobject.embedSWF("http://framey.com/recorder.swf", "<?= divid ?>", "400", "600", "8", "", flashvars, params, attributes);


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