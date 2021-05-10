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

    Otazka: <input type="text" id="question" name="question">

    Priklad: <span id="answer">x=</span><br>

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

           xhr.open("POST", "sendToDB.php");
           xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           xhr.send('question='+ document.getElementById('question').value + '&answer=' + enteredMath);

        });


    </script>
</body>

</html>
