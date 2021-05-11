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
</head>
<body>
Hlavna stranka ucitela

<input type="button" onclick="location.href='./teacher/create_test.php';" value="Vytvoriť test" />

<h4>Vytvorené testy:</h4>

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
<a href='./teacher/active.php?test=$test[0]&active=$test[3]'><input type='button' value='DE/AKTIVOVAŤ TEST'></a>
<br>";
}

?>
<br>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="submit" value="ODHLÁSIŤ" name="logout">
</form>

</body>
</html>