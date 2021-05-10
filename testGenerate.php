<?php 
  include_once "config/Database.php";
  $database = new Database();
  $conn = $database->createConnection();


 $json = file_get_contents("https://wt113.fei.stuba.sk/skuskove/api/giveTest.php?codeTest=abc");

 $data = json_decode($json,true);
 $questions = $data['otazky'];

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

  function makePairingQ($questions){
    $html = '';
    foreach($questions as $question){ 
      if($question['type'] == 'typ3'){
        $html.= '<p class="pairingQ">Priraďte hodnoty v pravom stĺpci ku hodnotám v ľavom.</p><br>';
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
  <title>Test Template</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
     .pairingQ{
       float: left;
     }
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




  <!-- potrebne pre párovacie otázkky -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    <?php
    generateScript($questions);
    ?>
  </script>
</head>



<body>
<?php
    makePairingQ($questions);
?>












<script>
  /*Script ku párovacej otzáke- Dano  */
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


</body>




</html>