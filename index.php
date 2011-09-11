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
  border-radius: 5px !important;
}

.lastThumbnail{
  display: inline !important;
}

#container{
  position: relative;
  width: 900px;
  margin: 0 auto;
}

#thumbnails{
  background: #222723;
  position: relative;
  top: 500px;
  margin: 0 auto;
  width: 900px;
  text-align: left;
  overflow: hidden;
  height: 174px;
  border-radius: 5px;
}

#thumbnailInner{
  background: #222723;
  margin-left: 195px;
  padding-top: 7px;
}

#thumbnailMacroInner{
  margin-left: 195px;
}

.story{
  display: inline;
  width:150px;
  font-family: "Helvetica", Arial, sans-serif;
  color: white;
  font-size: 20px;
  text-align: center;
  float: left;
  margin: 5px;
/*  padding-left: 51px;
  padding-right: 55px;*/
}

#thumbnailMacro{
  position: absolute;
  background: green;
  top: 683px;
  width: 900px;
  height: 30px;
}

#gradientLeft{
  position: absolute;
  top: 0;
  left: 0;
  height: 175px;
  width: 20px;
  z-index: 9999;
  background-image: -webkit-gradient(linear, 100% 50%, 0% 50%, from(rgba(0,0,0,0)), to(#000000));
}

#gradientRight{
  position: absolute;
  top: 0;
  right: 0;
  height: 175px;
  width: 20px;
  z-index: 9999;
  background-image: -webkit-gradient(linear, 0% 50%, 100% 50%, from(rgba(0,0,0,0)), to(#000000));
}

#megaplaya{
  position: absolute;
  top: 0;
}

#megaplaya > object{
  border: 3px solid #9DA09D;
  border-radius: 5px;
}

#theframeyRecorderContainer_1{
 visibility: hidden !important;
 position: absolute;
 top: -80px;
 left: 77px;
}

#tvContainer{
  position: relative;
  margin: 0 auto;
  top: -100px;
  height: 410px;
  width: 550px;
  overflow: hidden;
  float: left;
}

#text{
  float: right;
  text-align: left;
  color: white;
}

#loading{
  background: black;
  visibility: hidden;
  height: 500px;
  width: 540px;
  position: relative;
  margin: 0 auto;
  overflow: hidden;
  top: 0;
}
</style>
  
<div id="container">
  <?php
  require("opendb.php");
  require("framey.php");
  
  $all_mp4 = array();
  $all_thumbnails = array();
  $all_stories = array();
  
  $query = "SELECT DISTINCT title FROM stories";
  $result = mysql_query($query);
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
      array_push($all_stories, $row[0]);
  }
  
  $query = "SELECT * FROM stories";
  $result = mysql_query($query);
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
      array_push($all_mp4, $row[2]); 
      array_push($all_thumbnails, $row);
  }


  echo "<div id='thumbnails'>";
    echo "<div id='thumbnailInner'>";
    echo "<div id='gradientLeft'></div>";
    
  $pastValue = null;
  $counter = 0; 
  foreach($all_thumbnails as $key=>$value){
    
    if ($pastValue == $value[6]){
      $counter++;
    } else {
      $pastValue = $value[6];
      $counter = 0;
    }
    
    echo "<img id='$counter". $value[6] . "' class='$value[6] thumbnail' src='$value[4]'></img>";
    }
  echo "<img id='last' onClick='reveal()' id='$key' class='thumbnail lastThumbnail' src='blank.jpg'></img>";
        echo "<div id='gradientRight'></div>";
      echo "</div>";
  echo "</div>";
  
  //story links
  $storyCounter = 1;
  echo "<div id='thumbnailMacro'>";
    echo "<div id='thumbnailMacroInner'>";
    foreach($all_stories as $key=>$value) {
      echo "<div id='" . ($storyCounter * 1000) . "'js_value='$value' class='story'>$value</div>";
      $storyCounter++;
    }
      echo "<form method='GET' action='' id='" . ($storyCounter * 1000) . "'
            class='story'><input placeholder='Add Your Own' name=title></input></form>";
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
  $script = $script . '; var storyCount=' . count($all_stories); 
  echo $script . "</script>";
  ?>
  
  <div id="tvContainer">
    <div id="<?= divid ?>"></div>
    <div id="megaplaya"></div>
    <div id="loading"></div>
  </div>
  <div id="text">THIS IS SOME TEXT</div>
</div>
<script>
  
  var hover = function(key){
    console.log(key)
    try {
      document.getElementById(key).style.margin = "0px";
      document.getElementById(key).style.border = '5px solid #FFF';
    } catch (e){
      document.getElementById('last').style.margin = "0px";
      document.getElementById('last').style.border = '5px solid #FFF';
    }
  }
  
  var dehover = function(key){
    try {
      document.getElementById(key).style.margin = "5px";
      document.getElementById(key).style.border = 'none';
    } catch (e){
      document.getElementById('last').style.margin = "5px";
      document.getElementById('last').style.border = 'none';
    }
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
  
  
  var left = function (){
      if (key == 0){
        return; 
      }
      dehover(key + currentStory);
      key--;
      hover(key + currentStory)
      var currentMargin = thumbnails.css('margin-left','+=160');
      return false;
  }
  
  var right = function(){
    if (key == (count)){
      return;
    }
    dehover(key + currentStory);
    key++;
    hover(key + currentStory)
    var currentMargin = thumbnails.css('margin-left','-=160');
    return false;
  }
  
  var storyLeft = function (){
      if (storyKey == 1000){
        return; 
      }
      dehover(storyKey);
      storyKey -= 1000;
      hover(storyKey)
      var currentMargin = macro.css('margin-left','+=160');
      return false;
  }
  
  var storyRight = function(){
    var tempCount = storyCount;
    tempCount++;
    if (storyKey == (tempCount * 1000)){
      return;
    }
    dehover(storyKey);
    storyKey += 1000;
    hover(storyKey)
    var currentMargin = macro.css('margin-left','-=160');
    return false;
  }
  
  var filterAll = function(storyKey){
    var value = $('#' + storyKey).attr('js_value');
    currentStory = value;
    $('.thumbnail').each(function(index) {
        $(this).css('display', 'none');
      });

    $('.' + value).each(function(index) {
        $(this).css('display', 'inline');
      });
      
      count = $('.' + value).length;
  }
  
  var hoverNext = function(){
    console.log("worrd")
  }
  
  var thumbnails = $("#thumbnailInner");
  var macro = $("#thumbnailMacroInner")
  var key = 0;
  var storyKey = 1000;
  var currentStory = null;
  
  var sources = [];
  for (index in allMp4){
    var source = {url: allMp4[index]}
    sources.push(source);
    console.log(source);
  }

  var position= -1;
    
  $(document).keydown(function(e) {
    //left
    if (e.keyCode==37){
      if (position == 0){
        left()
      } else {
        storyLeft()
        filterAll(storyKey);
        //reset thumbnails
        key = 0;
        $("#thumbnailInner").css('margin-left','195px');
      }
    }

    //right    
    if (e.keyCode==39){
      if (position == 0){
        right()
      } else {
        storyRight()
        filterAll(storyKey);
        //reset thumbnails
        key = 0;
        $("#thumbnailInner").css('margin-left','195px');
      }
    }
    
    //down
    if (e.keyCode==40){
      console.log("down")
      if (position == (-1)){
        return;
      }
      hover(storyKey)
      filterAll(storyKey)
      dehover(key + currentStory)
      
      position = -1;
      return false;
    }
    
    //up
    if (e.keyCode==38){
      console.log("up")
      if (position == (0)){
        return;
      }
      
      dehover(storyKey);
      hover(key + currentStory)
      position = 0;
      return false;
    }

    //enter    
    if (e.keyCode==13){
      play(key);
    }
  });

  //initialize
  hover(1000);
  filterAll(1000);

  $(document).ready(
    function() {
      $('#megaplaya').flash({
        swf: 'http://vhx.tv/swf/megaplaya_embed_beta.swf',
        width: 540,
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