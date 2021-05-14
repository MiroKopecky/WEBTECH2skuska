<?php

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
}

require_once "../config.php";

if (isset($_POST['question'])) {
    $question = $_POST['question'];
    $answer = $_POST['odpoved'];
    $test_id = $_SESSION['test_id'];
    $points = $_POST['body'];
    $type = "shortAnswer";

    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO testQuestions (question, answer, test_id, type, points) VALUES (:question, :answer, :test_id, :type, :points)");
        $stmt->bindParam(':question',$question);
        $stmt->bindParam(':answer',$answer);
        $stmt->bindParam(':test_id',$test_id);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':points',$points);
        $stmt->execute();
        header("Location: create_test.php");
    }
    catch (PDOException $exception){
        echo "Error." . $exception;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body class="bg-dark text-white container">

<form class="form mt-5" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="question"><h1>Zadaj otázku: </h1></label><br>
    <input class="ml-2 mb-2" type="text" size="50" name="question" id="question" required><br>
    <label for="odpoved">Správna odpoveď: </label><br>
    <input class="ml-2" type="text" size="50" name="odpoved" id="odpoved" required><br>
    <label for="body">Body: </label><br>
    <input class="ml-2" type="text" size="50" name="body" id="body" required><br>
    <input class="btn btn-primary btn-block mt-5 ml-2" type="submit" id="submit" value="Submit" />
</form>

</body>
</html>
