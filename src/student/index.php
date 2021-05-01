<?php

session_start();

if ($_SESSION['student_check'] == false) {
    header("Location: ../login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Študent</title>
    <meta charset="UTF-8">
</head>
<body>
hlavna stranka studenta

</body>
</html>