<?php
require_once "../config.php";

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
}

$test_id = $_SESSION['test_id'];
$_SESSION['test_id'] = $test_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Matematicka otazka</title>

    <style>
        span {
            caret-color: white;
        }
    </style>
</head>

<body class="bg-dark text-white container">

<p id="note"></p>

<h1 class="h1 mt-5">Zadaj priklad:</h1> <br><br><span class="ml-5 p-5" style="font-size: 50px;" id="answer">x=</span><br><br>

<button class="btn btn-primary ml-5" id="send">Odoslat</button><br><br>


<script>


    var MQ = MathQuill.getInterface(2);

    var answerSpan = document.getElementById('answer');

    var enteredMath = "";

    var firstMathField = MQ.MathField(answerSpan, {
        handlers: {
            edit: function() {
                enteredMath = firstMathField.latex();
                //console.log(enteredMath);
            }
        }
    });


    document.getElementById("send").addEventListener("click", function (){

        console.log(enteredMath);

        const xhr = new XMLHttpRequest();

        xhr.open("POST", "send_q_math_to_db.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send('question=' + enteredMath);

        window.location.assign('create_test.php')
    });


</script>
</body>

</html>
