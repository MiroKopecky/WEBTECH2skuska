<?php

$json = file_get_contents('questions.json');
$obj = json_decode($json);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>

    <title>Document</title>
</head>
<body>

    <div id="type5-math"></div>


<script>

    var MQ = MathQuill.getInterface(2);

    var preview = document.getElementById("type5-math");

    var mathField = MQ.MathField(preview);

    var json = <?php
        echo json_encode($obj);
        ?>;


    mathField.latex(json.otazky[4].question); //na tvrdo najebane na poslednu otazku

</script>

</body>
</html>