<?php

$equation = $_POST['eq'];

require_once "Database.php";

$conn = (new Database())->getConnection();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("INSERT INTO equation (eq) VALUES (:eq)");///pls zmen t podla tvojej DB, ja som to zatial daval len na svoju
$stmt->bindParam(':eq', $equation);
$stmt->execute();
