<?php

$question = $_POST['question'];
$answer = $_POST['answer'];


require_once "Database.php";

$conn = (new Database())->getConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("INSERT INTO equation (question,answer) VALUES (:question, :answer)");///pls zmen t podla tvojej DB, ja som to zatial daval len na svoju
$stmt->bindParam(':question',$question);
$stmt->bindParam(':answer',$answer);
$stmt->execute();
