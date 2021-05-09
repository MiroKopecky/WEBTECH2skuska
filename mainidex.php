<?php 
  include_once "config/Database.php";
  $database = new Database();
  $conn = $database->createConnection();

  ini_set ('display_errors', 'on');
  ini_set ('log_errors', 'on');
  ini_set ('display_startup_errors', 'on');
  ini_set ('error_reporting', E_ALL);


  /*function separate($data){
  $sides = [];
  $left= [];
  $right= [];
  $index = 0;
  foreach($data as $part){
    $left[$index] = $part[0];
    $right[$index] = $part[1];
    $index++;
  }
  shuffle($right); // rozhadzanie poradia 
  $sides['right'] = $right;
  $sides['left'] =  $left;
  return $sides; 
  }*/


 $json = file_get_contents("questions.json");

 $obj = json_decode($json);


 $data = json_decode($json,true);
 $questions = $data['otazky'];

  
  /*function createMathdivs($otazky){
    $divs = '';
    foreach($otazky as $otazka){
      if($otazka['type'] == "typ5"){
        $divs.= '<div id="'.$otazka["questionID"].'"></div>';
    }
    echo $divs;
  }*/


  function generateMathScript($otazky){
    $script = 'var MQ = MathQuill.getInterface(2);';

    foreach($otazky as $otazka){

      if($otazka['type'] == 'typ5'){
        $data = $otazka["question"];
        $script.= 'var preview'.$otazka["questionID"]. '= document.getElementById("'.$otazka["questionID"].'");
        var mathField'.$otazka["questionID"] .'=MQ.MathField(preview'.$otazka["questionID"].');
        mathField'.$otazka["questionID"] .'.latex("'.$data.'");';
        echo $script;
      }
    }

  }

  function generateScript($questions){
    $script = ' $( function() {';
    foreach($questions as $question){
      if($question['type'] == 'typ3'){
        $script.= '$( "#'.$question['questionID'].'").sortable();';
      } 
    }

    $script.='});';
    echo $script;
  }

  function finals($questions){
    $html = '';
    foreach($questions as $question){ 
      if($question['type'] == 'typ3'){
        $html.= '<p>Priraďte hodnoty v pravom stĺpci ku hodnotám v ľavom.</p><br>';
        $id = $question['questionID'];
        $randomShuffle = $question['question']['rightCol'];
        shuffle($randomShuffle);
        $html.=makeDivs($question['question']['leftCol'],$randomShuffle,$id);
      }  
    }
    echo $html;
  }


  function makeDivs($lefts,$rights,$id){

    $divIdL = "sortable".$id."L";
    
    $lefDiv= '<ul id="'.$divIdL.'" class="column">';
    foreach($lefts as $left){
      $lefDiv.="<li>{$left}</li>";
    }
    $lefDiv.="</ul>";


    $rightDiv = '<ul id="'.$id.'" class="column help">';
    foreach($rights as $right){
      $rightDiv.="<li>{$right}</li>";
    }
    $rightDiv.="</ul>";
    return $lefDiv . " " . $rightDiv;
  }


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Sortable - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style>
    .column {
      width: 25%;
      min-height: 20px;
      list-style-type: none;
      margin-left: 10%;
      padding: 10px 0 0 0;
      float: left;
      margin-right: 10px;
    }

    .column li {
      margin: 0 3px 10px 10px;
      padding: 0.8em;
      padding-left: 1.5em;
      font-size: 1em;
      height: 18px;
    }

    .column li span {
      position: absolute;
      margin-left: -1.3em;
    }

    .wraper{
      border-style: solid;
    }

  </style>

  <script>
    <?php
    //generateScript($questions);
    ?>
  </script>
</head>




<body>

<div id="1200"></div>
  

<script>
  <?php
    //finals($questions);
    generateMathScript($questions);

  ?>
</script>

<script>
  function getChildElement(element, index) {
    var elementCount = 0;
    var child = element.firstChild;

    while (child) {
      if (child.nodeType == 1) { // Node with nodeType 1 is an Element
        if (elementCount == index) {
          return child;
        }
        elementCount++;
      }
      child = child.nextSibling;
    }
  }
    //všetky ID elementov podľa mena triedy
  var ids = $('.help').map(function() {
  return $(this).attr('id');
  });



  function a(id) {

    var txt ='';
    var element = document.getElementById(id);
    var size = element.childElementCount;
    var pole = [];
    for (var x = 0; x < size; x++) { 
      var child = getChildElement(element, x);
      var text = child.textContent || child.innerText;
      pole.push(text);
    }
    return ({type:"type3",id:id,answer:pole});


  }
  

  var json;
  function results( ){
    var data = [];
    for(var i = 0; i < ids.length; i++){
    data.push(a(ids[i])); 
    }
    var json = JSON.stringify(data);
    console.log(json);
  }
  
  
  $.ajax({
    url: 'https://wt113.fei.stuba.sk/skuskove/api/insertTest.php',
    type: 'post',
    data: {json},
    success: function(response){
      console.log(okkk);
    }
 })

</script>

<button onclick="results()">dd</button>

</body>



