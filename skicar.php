<?php
//Create database connection
$db = mysqli_connect("localhost", "xpetrikj3", "Jak.Pet.1999", "image_upload");


// If upload button is clicked ...
if (isset($_POST['upload'])) {
    //var_dump($_FILES['image'];
    // Get image name
    $image = $_FILES['image']['name'];
    // Get text
    //$image_text = mysqli_real_escape_string($db, $_POST['image_text']);
    //
    //
    $image_text = "blablabla";
    // image file directory
    $target = "images/".basename($image);

    $sql = "INSERT INTO images (image, image_text) VALUES ('$image', '$image_text')";
    // execute query
    mysqli_query($db, $sql);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Image uploaded successfully";
    }else{
        $msg = "Failed to upload image";
    }
}
$result = mysqli_query($db, "SELECT * FROM images ORDER BY ID DESC Limit 1");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Image Upload</title>
    <style type="text/css">
        #content{
            width: 50%;
            margin: 20px auto;
            border: 1px solid #cbcbcb;
        }
        form{
            width: 50%;
            margin: 20px auto;
        }
        form div{
            margin-top: 5px;
        }
        #img_div{
            width: 80%;
            padding: 5px;
            margin: 15px auto;
            border: 1px solid #cbcbcb;
        }
        #img_div:after{
            content: "";
            display: block;
            clear: both;
        }
        img{
            float: left;
            margin: 5px;
            width: 300px;
            height: 140px;
        }
        #canvas {
            border: 1px solid black;

        }
        .tools{
            display: flex;
            justify-content: center;
            flex-direction: row;
        }
        .tools .stroke-color {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor : pointer;
            display: inline-block;
        }
        .field{
            display: inline-block;

        }

    </style>
</head>

<body>
<canvas id="canvas" style=" margin-left: 1%; " ></canvas>
<div class="tools" style="width: 60%;   margin-left: 20%;">

    <button onclick="Restore()" class="btn btn-secondary">Undo</button>
    <button onclick="Clear()" class="btn btn-secondary">Clear</button>
    <button id="download" class="btn btn-secondary" >Download</button>
    <button onclick="location.href = 'https://wt119.fei.stuba.sk/skuskove_zadanie/';"  class=" submit-button btn btn-secondary" >Spat na test</button>


    <div onclick="change_color(this)" style="background:red" class="stroke-color"></div>
    <div onclick="change_color(this)" style="background:blue" class="stroke-color"></div>
    <div onclick="change_color(this)" style="background:yellow" class="stroke-color"></div>
    <div onclick="change_color(this)" style="background:green" class="stroke-color"></div>

    <input type="color" oninput="stroke_color = this.value" placeholder="Colors">
    <input type="range" min="1" max="100" value="1" oninput="stroke_width = this.value">


</div>
<script>
    let canvas = document.getElementById("canvas");
    canvas.width = window.innerWidth - 60;
    canvas.height = window.innerHeight * 0.6;
    let context = canvas.getContext("2d");
    context.fillStyle = "white";
    context.fillRect(0, 0, canvas.width, canvas.height);
    let restore_array = [];
    let start_index = -1;
    let stroke_color = 'black';
    let stroke_width = "2";
    let is_drawing = false;

    function change_color(element) {
        stroke_color = element.style.background;
    }

    function change_width(element) {
        stroke_width = element.innerHTML
    }




    function start(event) {
        is_drawing = true;
        context.beginPath();
        context.moveTo(getX(event), getY(event));
        event.preventDefault();
    }

    function draw(event) {
        if (is_drawing) {
            context.lineTo(getX(event), getY(event));
            context.strokeStyle = stroke_color;
            context.lineWidth = stroke_width;
            context.lineCap = "round";
            context.lineJoin = "round";
            context.stroke();
        }
        event.preventDefault();
    }

    function stop(event) {
        if (is_drawing) {
            context.stroke();
            context.closePath();
            is_drawing = false;
        }
        event.preventDefault();
        restore_array.push(context.getImageData(0, 0, canvas.width, canvas.height));
        start_index += 1;
    }

    function getX(event) {
        if (event.pageX == undefined) {return event.targetTouches[0].pageX - canvas.offsetLeft}
        else {return event.pageX - canvas.offsetLeft}
    }


    function getY(event) {
        if (event.pageY == undefined) {return event.targetTouches[0].pageY - canvas.offsetTop}
        else {return event.pageY - canvas.offsetTop}
    }

    canvas.addEventListener("touchstart", start, false);
    canvas.addEventListener("touchmove", draw, false);
    canvas.addEventListener("touchend", stop, false);
    canvas.addEventListener("mousedown", start, false);
    canvas.addEventListener("mousemove", draw, false);
    canvas.addEventListener("mouseup", stop, false);
    canvas.addEventListener("mouseout", stop, false);

    function Restore() {
        if (start_index <= 0) {
            Clear()
        } else {
            start_index += -1;
            restore_array.pop();
            if ( event.type != 'mouseout' ) {
                context.putImageData(restore_array[start_index], 0, 0);
            }
        }
    }

    function Clear() {
        context.fillStyle = "white";
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.fillRect(0, 0, canvas.width, canvas.height);
        restore_array = [];
        start_index = -1;
    }


    const download = document.getElementById('download');
    download.addEventListener('click', function(e) {
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        ctx.beginPath();
        ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
        ctx.lineWidth = 10;
        ctx.stroke();
        var imgData = canvas.toDataURL();
        console.log( imgData);
    });




</script>



</body>

</html>