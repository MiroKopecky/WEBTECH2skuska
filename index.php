<?php

$json = file_get_contents("https://wt113.fei.stuba.sk/skuskove/api/giveTest.php?codeTest=abc");

$data = json_decode($json,true);
$questions = $data['otazky'];


foreach($questions as $question){
    if ($question['type'] == "typ4"){

        $content = $question['question'];
        //echo $content;
    }
}

$db = mysqli_connect("localhost", "xpetrikj3", "Jak.Pet.1999", "image_upload");

// Initialize message variable
$msg = "";

// If upload button is clicked ...
if (isset($_POST['upload'])) {
    // Get image name
    $image = $_FILES['image']['name'];
    // Get text
    //echo $_FILES['image']['name'];
    $image_text = mysqli_real_escape_string($db, $_POST['image_text']);

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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">


</head>
<body>
<div class="tools">

    <h2><?php echo $content ?></h2>
    <div>
        <input type="button" value="Skicar" class="homebutton btn btn-secondary" id="btnHome"
               onClick="window.location = 'https://wt119.fei.stuba.sk/skuskove_zadanie/skicar.php'" />
    </div>
</div>

<div id="content">
    <?php
    while ($row = mysqli_fetch_array($result)) {
        echo "<div id='img_div'>";
        echo "<img src='images/".$row['image']."' >";
        echo "</div>";
    }
    ?>



    <form method="POST" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="size" value="1000000">
        <div>
            <input type="file" name="image">
        </div>
        <div>
            <button type="submit" name="upload">POST</button>

        </div>
    </form>
</div>
</body>
<script src="script.js"></script>
</html>