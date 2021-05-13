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

    <title>Matematicka otazka</title>
</head>

<body>

<p id="note"></p>

Zadaj priklad: <br><br><span id="answer">x=</span><br><br>

<button id="send">Odoslat</button><br><br>


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