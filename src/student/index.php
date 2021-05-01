<?php

require_once "../config.php";

session_start();

if ($_SESSION['student_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['exit'])) {
    $id = $_SESSION['id'];
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM students WHERE id='$id'";
        $conn->exec($sql);
    }
    catch (PDOException $exception){
        echo "Error:" . $exception->getMessage();
    }
    session_destroy();
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

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="submit" value="UKONČIŤ TEST" name="exit">
</form>

</body>
</html>