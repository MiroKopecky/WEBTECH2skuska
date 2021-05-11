<?php

require_once "../config.php";

$test_id = $_GET['test'];
$active = $_GET['active'];

if ($active == 1) {
    $active = 0;
}
else {
    $active = 1;
}

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE test SET active='$active' WHERE id='$test_id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    header("Location: ../");
}
catch (PDOException $exception){
    echo "error: " . $exception;
}