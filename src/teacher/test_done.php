<?php

require_once "../config.php";

$test_id = $_GET['test'];



$student_ids = null;
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT student_id from testParticipants where test_id='$test_id' AND status='done'");
    $stmt->execute();
    $student_ids = $stmt->fetchAll();
}
catch (PDOException $exception){
    echo "Error:" . $exception->getMessage();
}

echo "<h1>Zoznam študentov ktorí už písali tento test</h1>";
echo "<a href='./csv.php?test=$test_id'><input type='button' value='EXPORT CSV'></a><br><br>";

foreach ($student_ids as $student_id) {
    $students = null;
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * from students where id='$student_id[0]'");
        $stmt->execute();
        $students = $stmt->fetchAll();
    }
    catch (PDOException $exception){
        echo "Error:" . $exception->getMessage();
    }

    $student = $students[0][0];

    echo $students[0]['name'] . " " . $students[0]['surname'] . "
    <a href='./test.php?test=$test_id&student=$student'><input type='button' value='ZOBRAZIŤ ODPOVEDE'></a> 
    <br>";
}