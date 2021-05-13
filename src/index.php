<?php

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
}

?>

<!DOCTYPE HTML>
<html lang="sk">
<head>
    <title>Učiteľ</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>

        input {
            margin-bottom: 15px;
        }

        .container {
            width: 80%;
        }

    </style>
</head>
<body>
<div class="container pt-5 bg-dark text-white mt-4">
    <h1>Hlavna stranka ucitela</h1>

    <input class="btn btn-primary" type="button" onclick="location.href='./teacher/create_test.php';" value="Vytvoriť test" />
    <input class="btn btn-primary" type="button" onclick="location.href='./teacher/pdf.php';" value="Exportovat test do pdf" />

    <h4 class="h4">Vytvorené testy:</h4>

    <?php
    require_once "config.php";

    session_start();

    unset($_SESSION['test_id']);

    $teacher_id = $_SESSION['id'];

    $tests = null;
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * from test where teacher_id='$teacher_id'");
        $stmt->execute();
        $tests = $stmt->fetchAll();
    }
    catch (PDOException $exception){
        echo "Error:" . $exception->getMessage();
    }

    foreach ($tests as $test) {
        echo "id: " . $test[0] . " active: " . $test[3] . " code: " . $test[1] ." 
    <a href='./teacher/active.php?test=$test[0]&active=$test[3]'><input type='button' class='btn btn-primary' value='DE/AKTIVOVAŤ TEST'></a>  
    <a href='./teacher/test_done.php?test=$test[0]'><input class='btn btn-primary' type='button' value='ZOZNAM ŠTUDENTOV KTORÍ UŽ PÍSALI TENTO TEST'></a>
    <a href='./teacher/test_solving.php?test=$test[0]'><input class='btn btn-primary' type='button' value='ZOZNAM ŠTUDENTOV KTORÍ AKTUÁLNE PÍŠU TENTO TEST'></a>
    <br>";
    }

    ?>
    <br>
    <form class="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="submit" class='btn btn-warning' value="ODHLÁSIŤ" name="logout">
    </form>
</div>
</body>
</html>
