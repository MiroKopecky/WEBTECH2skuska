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

date_default_timezone_set('Europe/Bratislava');

if($_SESSION['timeEnd'] ==null){
    $secNow = date(time()); //aktualny cas v sekundach
    $secNow = intval($secNow);
    $timeEnd = $secNow + $test['timelimit']*60; // pripocitanie casu na vypracovanie testu => maxTime
    $_SESSION['timeEnd'] = $timeEnd;
    $_SESSION['secCounter'] = $test['timelimit'];
}else{
    $secNow = date(time()); //aktualny cas v sekundach
    $secNow = intval($secNow);
    if((($_SESSION['timeEnd']-$secNow)/60) >0){
        $_SESSION['secCounter'] = ($_SESSION['timeEnd']-$secNow)/60;
    }else{
        $_SESSION['secCounter'] = 0;
    }
    //var_dump($_SESSION['secCouter']);
}

$json = file_get_contents("https://wt15.fei.stuba.sk/skuska/student/get.php?testId=$test_id");

//var_dump($json);

$data = json_decode($json,true);
$questions = $data['otazky'];

function makeOpenQ($questions){
    $html = '';
    foreach($questions as $question){
        if($question['type'] == 'typ1'){
            $html .= '<div class="openQ mb-4"><p class="h3 mb-3">Otázka: '.$question['question'].'</p><label class="p-3" for="answers'.$question['questionID'].'">Odpoveď:</label><input id="'.$question['questionID'].'" type="text" name=answers'.$question['questionID'].'></div>';    
        }
    }
    echo $html;
}

function makeSelect($questions){
    foreach($questions as $question){
        if($question['type'] == 'typ2'){
            $answers = [];
            $id = $question['questionID'];
            foreach ($question['question']['mozneOdpovede'] as $answer) {
                array_push($answers, $answer);
            }
            sort($answers);
            echo "
                <p class='h3 mb-3'>Vyber správnu odpoveď: </p>
                <div id='$id' class='answer_select p-3'>
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
            $html.= '<p class="pairingQ h3">Priraďte hodnoty v pravom stĺpci ku hodnotám v ľavom.</p><br>';
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


    $rightDiv = '<ul  id="'.$id.'" class="column help">';
    foreach($rights as $right){
        $rightDiv.="<li>{$right}</li>";
    }
    $rightDiv.="</ul><div style='clear: both;'></div>";
    return $lefDiv . " " . $rightDiv;
}

function makePainting($questions){
    foreach($questions as $question){
        if($question['type'] == 'typ4'){
            ?>
            <p class="h3"><?php echo $question["question"] ?></p>
            <canvas id="canvas<?php echo $question['questionID'] ?>" style=" margin-left: 1%; border: solid black 1px" ></canvas>
            <div class="tools" class="text-center">
                <button onclick="canvas<?php echo $question['questionID'] ?>.Restore()" class="btn btn-secondary">Undo</button>
                <button onclick="canvas<?php echo $question['questionID'] ?>.Clear()" class="btn btn-secondary">Clear</button>
                <div class="farby">
                    <div class="farba" onclick="canvas<?php echo $question['questionID'] ?>.change_color(this)" style="background:red" class="stroke-color"><pre>   </pre></div>
                    <div class="farba" onclick="canvas<?php echo $question['questionID'] ?>.change_color(this)" style="background:blue" class="stroke-color"><pre>   </pre></div>
                    <div class="farba" onclick="canvas<?php echo $question['questionID'] ?>.change_color(this)" style="background:yellow" class="stroke-color"><pre>   </pre></div>
                    <div class="farba" onclick="canvas<?php echo $question['questionID'] ?>.change_color(this)" style="background:green" class="stroke-color"><pre>   </pre></div>
                    <input type="color" style="top: 4px; position: relative; display: inline" oninput="canvas<?php echo $question['questionID'] ?>.stroke_color = this.value" placeholder="Colors">
                    <input type="range" style="top: 6px; position: relative;" min="1" max="100" value="1" oninput="canvas<?php echo $question['questionID'] ?>.stroke_width = this.value">
                </div>
            </div>
            <script>
                let canvas<?php echo $question['questionID'] ?> = new Skicar("<?php echo $question['questionID'] ?>");
            </script>
            <?php
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css">

    <style>

        .tools {
            margin-left: 300px;
        }

        .tools div {
            display: inline-block;
        }

        .farba {
            top: 16px;
            position: relative;
        }

        ul {
            list-style-type: none;
        }

        .column {
            width: 44%;
            min-height: 20px;
            list-style-type: none;
            float: left;
            margin-right: 10px;
        }

        .column li {
            padding: 25px;
            padding-bottom: 50px;
            padding-right: 450px;
            font-size: 1em;
            height: 18px;
            margin: 3px;
            background-color: #6c757d;
            color: #fff;
            border-radius: .25rem;
        }

        .column li:active{
            background-color: #999999;
        }

        .column li span {
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
        #c {
            position: fixed;
            top: 20px;
            left: 20px;
        }

    </style>




    <!-- potrebne pre párovacie otázkky -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!--    Matematicka otazka-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>

    <script>
        <?php
        generateScript($questions);
        ?>

skicarOdpovede = {};
kratkeOdpovede = {};
class Skicar{
    constructor(qID){
        this.qID = qID;
        this.canvasID = "canvas" + this.qID;
        this.canvas = document.getElementById(this.canvasID);
        this.canvas.width = this.canvas.parentElement.clientWidth - 50;
        this.canvas.height = this.canvas.width * 0.6;
        this.context = this.canvas.getContext("2d");
        this.context.fillStyle = "white";
        this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
        this.restore_array = [];
        this.start_index = -1;
        this.stroke_color = 'black';
        this.stroke_width = "2";
        this.is_drawing = false;
        this.canvas.addEventListener("touchstart", function(e){this.start(e)}.bind(this), false);
        this.canvas.addEventListener("touchmove", function(e){this.draw(e)}.bind(this), false);
        this.canvas.addEventListener("touchend", function(e){this.stop(e)}.bind(this), false);
        this.canvas.addEventListener("mousedown", function(e){this.start(e)}.bind(this), false);
        this.canvas.addEventListener("mousemove", function(e){this.draw(e)}.bind(this), false);
        this.canvas.addEventListener("mouseup", function(e){this.stop(e)}.bind(this), false);
        this.canvas.addEventListener("mouseout", function(e){this.stop(e)}.bind(this), false);
    }
    change_color(element) {
        this.stroke_color = element.style.background;
    }
    change_width(element) {
        this.stroke_width = element.innerHTML;
    }
    start(event) {
        this.is_drawing = true;
        this.context.beginPath();
        this.context.moveTo(this.getX(event), this.getY(event));
        event.preventDefault();
    }
    draw(event) {
        if (this.is_drawing) {
            this.context.lineTo(this.getX(event), this.getY(event));
            this.context.strokeStyle = this.stroke_color;
            this.context.lineWidth = this.stroke_width;
            this.context.lineCap = "round";
            this.context.lineJoin = "round";
            this.context.stroke();
        }
        event.preventDefault();
    }
    stop(event) {
        if (this.is_drawing) {
            this.context.stroke();
            this.context.closePath();
            this.is_drawing = false;
        }
        event.preventDefault();
        this.restore_array.push(this.context.getImageData(0, 0, this.canvas.width, this.canvas.height));
        this.start_index += 1;
        skicarOdpovede[this.qID] = document.getElementById(this.canvasID).toDataURL();
    }
    getX(event) {
        if (event.pageX == undefined) {return event.targetTouches[0].pageX - this.canvas.offsetLeft}
        else {return event.pageX - this.canvas.offsetLeft}
    }
    getY(event) {
        if (event.pageY == undefined) {return event.targetTouches[0].pageY - this.canvas.offsetTop}
        else {return event.pageY - this.canvas.offsetTop}
    }
    Restore() {
        if (this.start_index <= 0) {
            this.Clear()
        } else {
            this.start_index += -1;
            this.restore_array.pop();
            if ( event.type != 'mouseout' ) {
                this.context.putImageData(this.restore_array[this.start_index], 0, 0);
            }
        }
        skicarOdpovede[this.qID] = document.getElementById(this.canvasID).toDataURL();
    }
    Clear() {
        this.context.fillStyle = "white";
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.context.fillRect(0, 0, this.canvas.width, this.canvas.height);
        this.restore_array = [];
        this.start_index = -1;
        skicarOdpovede[this.qID] = document.getElementById(this.canvasID).toDataURL();
    }
}


    </script>
</head>



<body>

<div class="container bg-light pb-5 mb-5">

    <h1 class="h1 text-dark">Test <?php echo $_SESSION['test_id']; ?>:</h1>

    <div class="container pb-5 mb-5">

<?php
makeOpenQ($questions);
makeSelect($questions);
makePairingQ($questions);
makePainting($questions);
?>

    <!--****************************************************************MATEMATICKA-->
    <p class="h2">Vypočítaj príklad:</p>

    <div id="mathDiv" style="margin: 20px; font-size: 25px;">
        <div id="mathQ"></div><br>
    </div>

    <div id="c">
        <h1 class="h5">Zostávajúci čas na vypracovanie</h1>
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
    </div>

    <script>

        $(".openQ input").change(function(){
            kratkeOdpovede[this.id] = this.value;
        })

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
            var answer;
            for (var x = 1; x < size; x++) {
                var child = getChildElement(element, x);
                var text = child.value;
                answer = text;
            }
            return ({type:"typ2",questionID:id,answer:answer});
        }

        //typ3
        var ids = $('.help').map(function() {
            return $(this).attr('id');
        });

        //typ5
        var mathId = function() {
            if(document.querySelector('.mathAnswer') != null)
                return document.querySelector('.mathAnswer').id;
        };

        function pairing(id) {
            var element = document.getElementById(id);
            var size = element.childElementCount;
            var pole = [];
            for (var x = 0; x < size; x++) {
                var child = getChildElement(element, x);
                var text = child.textContent || child.innerText;
                pole.push(text);
            }
            return ({type:"typ3",questionID:id,answer:pole});
        }

        //typ5
        function math(id,latex) {
            return ({type:"typ5",questionID:id,answer:latex});
        }



        function results(){

            var endTime = <?php echo $_SESSION['timeEnd']?>;
            var timeNow = (new Date().getTime() / 1000);
            var dif = endTime-timeNow;
            if(dif>=-5){ // 5 sekundova tolerancia,kvôli delayu, ktory môže nastať pri viacnasobnom refreshi avšak stačila by asi aj sekunda
                var json = {};
                var metadata = [<?php echo $_SESSION['test_id'];?>,<?php echo $_SESSION['id'];?>];

                var data = [];

                for(var i = 0; i < select_ids.length; i++){
                    data.push(select(select_ids[i]));
                }
                for(var i = 0; i < ids.length; i++){
                    data.push(pairing(ids[i]));
                }

                for(key in kratkeOdpovede){
                    data.push({type:"typ1",questionID:key,answer:kratkeOdpovede[key]});
                }
                for(key in skicarOdpovede){
                    data.push({type:"typ4",questionID:key,answer:skicarOdpovede[key]});
                }

                data.push(math(mathId(),enteredMath));

                json['metaData'] = metadata;
                json['odpovede'] = data;

                json = JSON.stringify(json);
                console.log(json);

                $.ajax({
                    url: 'https://wt15.fei.stuba.sk/skuska/student/post.php',
                    type: 'post',
                    data: json,
                    success: function(response){
                        console.log("ok");
                    }
                })
            }else{
                alert("Nonono!! Ty špekulant");
            }


        }



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
                    var endTime = <?php echo $_SESSION['timeEnd']?>;
                    var timeNow = (new Date().getTime() / 1000);
                    var dif = endTime-timeNow;
                    if(dif>=-5){ // 5 sekundova tolerancia,kvôli delayu, ktory môže nastať pri viacnasobnom refreshi avšak stačila by asi aj sekunda
                        results();
                        alert("čas vypršal - odpovede zaznamené");
                        window.location = '../login.php';
                    }
                }
            }

            updateClock();
            const timeinterval = setInterval(updateClock, 1000);
        }

        const deadline = new Date(Date.parse(new Date()) + <?php echo $_SESSION['secCounter']?>* 60 * 1000);
        initializeClock('clockdiv', deadline);


    </script>

    <script>

        var MQ = MathQuill.getInterface(2);

        var questionDiv = document.getElementById("mathQ");

        var answerDiv = document.getElementById("mathDiv");

        var enteredMath = 0;

        var json = <?php
            echo json_encode($questions);
            ?>;

        console.log(json);
        for(var k in json) {
            if (json[k].type === "typ5"){

                ///OTAZKA
                var mathField = MQ.MathField(questionDiv);
                mathField.latex(json[k].question);

                ///ODPOVED
                var mathA = document.createElement("div");
                mathA.setAttribute("id", json[k].questionID);
                mathA.setAttribute("class", "mathAnswer");
                mathA.textContent = "x=";
                answerDiv.appendChild(mathA);

                var answerMathField = MQ.MathField(mathA, {
                    handlers: {
                        edit: function() {
                            enteredMath = answerMathField.latex();
                            //console.log(enteredMath);
                        }
                    }
                });

                break;
            }


        }

    </script>

    <form class="form" action="" method="post">
        <input style="float: right" class="btn btn-lg btn-primary" type="submit" onclick="results()" value="UKONČIŤ TEST" name="exit">
    </form>

    </div>
</div>

</body>
</html>
