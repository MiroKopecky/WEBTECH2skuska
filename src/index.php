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

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="submit" value="ODHLÁSIŤ" name="logout">
</form>

</body>
</html>