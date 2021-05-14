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

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $timelimit = $_POST['time'];
    if (isset($_POST['active'])) {
        $active = 1;
    }
    else {
        $active = 0;
    }
    $teacher_id = $_SESSION['id'];
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO test (code, teacher_id, active, timelimit) VALUES (:code, :teacher_id, :active, :timelimit)");
        $stmt->bindParam(':code',$code);
        $stmt->bindParam(':teacher_id',$teacher_id);
        $stmt->bindParam(':active',$active);
        $stmt->bindParam(':timelimit',$timelimit);
        $stmt->execute();
        $test_id = $conn->lastInsertId();
        $_SESSION['test_id'] = $test_id;
        $_SESSION['created'] = "true";
    }
    catch (PDOException $exception){
        $_SESSION['created'] = "false";
        echo "<h1 class='text-danger'>Test s týmto ID už existuje!</h1>";
    }
}

if (isset($_POST['type'])) {
    if ($_POST['type'] == "shortAnswer") {
        header("Location: create_q_fill.php");
    }
    if ($_POST['type'] == "select") {
        header("Location: create_q_select.php");
    }
    if ($_POST['type'] == "pairing") {
        header("Location: create_q_link.php");
    }
    if ($_POST['type'] == "math") {
        header("Location: create_q_math.php");
    }
    if ($_POST['type'] == "drawing") {
        header("Location: create_q_draw.php");
    }
}

if (isset($_POST['null'])) {
    $_SESSION['created'] = "false";
    header("Location: ../");
}

?>

<!DOCTYPE HTML>
<html lang="sk">
<head>
    <title>Učiteľ</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">
<div class="container p-5 m-5">
    <h1 class="h1 mb-5">Vytvorte test:</h1>
    <div class="container">
        <form class="form" id="createTest" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <label for="code">Kód testu: </label>
            <input type="text" name="code" id="code" required><br>
            <label for="time">Čas na vypracovanie (v minútach): </label>
            <input type="number" name="time" id="time" required><br>
            <label for="active">Aktívny: </label>
            <input type="checkbox" name="active" id="active" checked="checked" value="checked" /><br>
            <input class="btn btn-primary btn-block" type="submit" value="VYTVORIŤ TEST" />
        </form>

        <form id="createQuestions" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

            <h2>Pridať otázku:</h2>

            <label for="type">Typ otázky:</label>
            <select id="type" name="type">
                <option value='shortAnswer'>Doplnenie odpovede</option>
                <option value='select'>Výber odpovede</option>
                <option value='pairing'>Párovanie</option>
                <option value='drawing'>Kreslenie</option>
                <option value='math'>Matematický príklad</option>
            </select><br><br>

            <input class="btn btn-primary" type="submit" value="PRIDAŤ OTÁZKU" />

        </form>

    <?php

    session_start();

    if(isset($_SESSION['test_id'])){
        $test_id = $_SESSION['test_id'];
    } else {
        $test_id = "";
    }

    $teacher_id = $_SESSION['id'];
    $questions = null;
    $count = null;
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * from testQuestions WHERE test_id='$test_id'");
        $stmt->execute();
        $questions = $stmt->fetchAll();
        $stmt = $conn->prepare("SELECT COUNT(*) from testQuestions WHERE test_id='$test_id'");
        $stmt->execute();
        $count = $stmt->fetchAll();
    }
    catch (PDOException $exception){
        echo "Error:" . $exception->getMessage();
    }

    echo "V tomto teste je " . $count[0][0] . " otázok.<br><br>";
    foreach ($questions as $question) {
        echo $question[1] . " - " . $question[4] . "<br>";
    }

    ?>
    <form id="endButton" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" value="false" id="null" name="null">
        <input class="btn btn-warning btn-block mt-5" type="submit" value="VYTVORIŤ TEST" />
    </form>

    <script>

        document.getElementById("createQuestions").style.display = "none";
        document.getElementById("endButton").style.display = "none";

        let created = "<?php if(isset($_SESSION['created'])){ echo $_SESSION['created'];}?>";
        if (created == "true") {
            document.getElementById("createTest").style.display = "none";
            document.getElementById("createQuestions").style.display = "block";
            document.getElementById("endButton").style.display = "block";
        }

    </script>

    </div>

</div>
</body>
</html>
