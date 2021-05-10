<?php

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
}

require_once "../config.php";

$question = $_POST['question'];
$answer = $_POST['answer'];
$test_id = $_SESSION['test_id'];
$type = "mathFormula";

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("INSERT INTO testQuestions (question, answer, test_id, type) VALUES (:question, :answer, :test_id, :type)");
    $stmt->bindParam(':question',$question);
    $stmt->bindParam(':answer',$answer);
    $stmt->bindParam(':test_id',$test_id);
    $stmt->bindParam(':type',$type);
    $stmt->execute();
}
catch (PDOException $exception){
    echo "Error." . $exception;
}
