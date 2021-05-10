<?php

require_once "../config.php";

session_start();

if ($_SESSION['student_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['exit'])) {
    $student_id = $_SESSION['id'];
    $test_id = $_SESSION['test_id'];
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE testParticipants SET status='done' WHERE student_id='$student_id' AND test_id='$test_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        session_destroy();
        header("Location: ../login.php");
    }
    catch (PDOException $exception){
        echo "Error:" . $exception->getMessage();
    }

}

$test_id = $_SESSION['test_id'];

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT timelimit FROM test WHERE id='$test_id'";
$stm = $conn->query($sql);
$test = $stm->fetch(PDO::FETCH_ASSOC);

$json = file_get_contents("https://wt79.fei.stuba.sk/skuska/student/get.php?testId=$test_id");

var_dump($json);

$data = json_decode($json,true);
$questions = $data['otazky'];

function makeOpenQ($questions){
    $html = '';
    foreach($questions as $question){
        if($question['type'] == 'typ1'){
            $html .= '<div class="openQ"><p><b>Otázka:</b>'.$question['question'].'</p><label for="answers'.$question['questionID'].'">Odpoveď:</label><input type="text" name=answers'.$question['questionID'].'></div>';
        }
    }
    echo $html;
}

function makeSelect($questions){
    foreach($questions as $question){
        if($question['type'] == 'typ2'){
            $answers = [];
            $id = $question['questionID'];
            var_dump($id);
            foreach ($question['question']['mozneOdpovede'] as $answer) {
                array_push($answers, $answer);
            }
            sort($answers);
            echo "
                <p>Vyber správnu odpoveď: </p>
                <div id='$id' class='answer_select'>
                <label for='answer_select'>". $question['question']['textOtazky'] ."</label>
                <select id='answer_select' name='answer_select'>";
            foreach ($answers as $answer) {
                echo "<option value='$answer'>$answer</option>";
            }
            echo "</select></div><br><br>";
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

function makePainting($questions){
    $html = '';
    foreach($questions as $question){
        if($question['type'] == 'typ4'){
            $html.= '<p class="painting">Otvorte skicar a nakresli obrazok podla zadania a stiahni ho!</p><br>';
            $id = $question['questionID'];
            $content = $question['question'];


            $html.='<h2>'.$content.'</h2>';
        }
    }

    echo $html;
    ?>
    <button onclick=" window.open( 'https://wt119.fei.stuba.sk/skuska2/skicar.php');"  class=" submit-button btn btn-secondary" >Skicar</button>


    <form method="POST" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="size" value="1000000">
        <div>

            <input type="file" class="btn btn-secondary" name="image">

        </div>
        <div>
            <button type="submit" class="btn btn-secondary" name="upload">POST</button>

        </div>
    </form>
    <?php
    if (isset($_POST['upload'])) {

        $image = $_FILES['image']['name'];

        if ($image ==""){
            echo "Nevlozil si obrazok";
        }
        else {
            $target = "images/" . basename($image) . $question['questionID'] . ".png";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $msg = "Image uploaded successfully";
            } else {
                $msg = "Failed to upload image";
            }
            $url = "images/" . basename($image) . $question['questionID'] . ".png";
            echo "<img width='50%' height='50%' src='" . $url . "' >";
        }
    }

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
        h1{
            color: rgb(115, 28, 196);
            font-weight: 100;
            font-size: 20px;
            margin: 40px 0px 20px;
        }

        #clockdiv{
            font-family: sans-serif;
            color: #fff;
            display: inline-block;
            font-weight: 50;
            text-align: center;
            font-size: 30px;
        }

        #clockdiv > div{
            padding: 10px;
            border-radius: 3px;
            background: #594f8d;
            display: inline-block;
        }

        #clockdiv div > span{
            padding: 15px;
            border-radius: 3px;
            background: #010807;
            display: inline-block;
        }

        .smalltext{
            padding-top: 5px;
            font-size: 16px;
        }
        .openQ input {
            margin: 5px;
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
makeOpenQ($questions);
makeSelect($questions);
makePairingQ($questions);
makePainting($questions);
?>

<h1>Zostávajúci čas na vypracovanie</h1>
<div id="clockdiv">
    <div>
        <span class="hours"></span>
        <div class="smalltext">Hours</div>
    </div>
    <div>
        <span class="minutes"></span>
        <div class="smalltext">Minutes</div>
    </div>
    <div>
        <span class="seconds"></span>
        <div class="smalltext">Seconds</div>
    </div>
</div>






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

    //typ2
    var select_ids = $('.answer_select').map(function() {
        console.log($(this).attr('id'));
        return $(this).attr('id');
    });

    function select(id) {
        var element = document.getElementById(id);
        var size = element.childElementCount;
        var pole = [];
        for (var x = 1; x < size; x++) {
            var child = getChildElement(element, x);
            var text = child.value;
            pole.push(text);
        }
        return ({type:"type2",id:id,answer:pole});
    }

    //typ3
    var ids = $('.help').map(function() {
        return $(this).attr('id');
    });

    function a(id) {
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
    function results(){
        var data = [];
        for(var i = 0; i < select_ids.length; i++){
            data.push(select(select_ids[i]));
        }
        for(var i = 0; i < ids.length; i++){
            data.push(a(ids[i]));
        }
        var json = JSON.stringify(data);
        console.log(json);
    }

    $.ajax({
        url: 'https://wt79.fei.stuba.sk/skuska/student/insertTest.php',
        type: 'post',
        data: {json},
        success: function(response){
            console.log("ok");
        }
    })

    //timer
    function getTimeRemaining(endtime) {
        const total = Date.parse(endtime) - Date.parse(new Date());
        const seconds = Math.floor((total / 1000) % 60);
        const minutes = Math.floor((total / 1000 / 60) % 60);
        const hours = Math.floor((total / (1000 * 60 * 60)) % 24);


        return {
            total,
            hours,
            minutes,
            seconds
        };
    }

    function initializeClock(id, endtime) {
        const clock = document.getElementById(id);
        const daysSpan = clock.querySelector('.days');
        const hoursSpan = clock.querySelector('.hours');
        const minutesSpan = clock.querySelector('.minutes');
        const secondsSpan = clock.querySelector('.seconds');

        function updateClock() {
            const t = getTimeRemaining(endtime);


            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
                clearInterval(timeinterval);
            }
        }

        updateClock();
        const timeinterval = setInterval(updateClock, 1000);
    }

    const deadline = new Date(Date.parse(new Date()) + <?php echo $test['timelimit']?>* 60 * 1000);
    initializeClock('clockdiv', deadline);

</script>

<form action="" method="post">
    <input type="submit" onclick="results()" value="UKONČIŤ TEST" name="exit">
</form>

</body>
</html>