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
    $answer = "";
    $test_id = $_SESSION['test_id'];
    $type = "drawing";

    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO testQuestions (question, answer, test_id, type) VALUES (:question, :answer, :test_id, :type)");
        $stmt->bindParam(':question',$question);
        $stmt->bindParam(':answer',$answer);
        $stmt->bindParam(':test_id',$test_id);
        $stmt->bindParam(':type',$type);
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
</head>
<body>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="question">Zadaj otázku: </label><br>
    <input type="text" size="50" name="question" id="question" required><br>
    <input type="submit" id="submit" value="Submit" />
</form>

</body>
</html>