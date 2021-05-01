<?php
require_once "config.php";

session_start();

$teachers = null;
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * from teachers");
    $stmt->execute();
    $teachers = $stmt->fetchAll();
}
catch (PDOException $exception){
    echo "Error:" . $exception->getMessage();
}

if (isset($_POST['name']) && ($_POST['surname']) && ($_POST['email']) && ($_POST['password'])) {
    if ($_POST['name'] != "" && $_POST['surname'] != "" && $_POST['email'] != "" && $_POST['password'] != "") {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $check = true;
        foreach ($teachers as $teacher) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p class='note'>Zadajte správny email!</p>";
                $check = false;
                break;
            }
            if ($email == $teacher['email']) {
                echo "<p class='note'>Konto s týmto emailom už existuje!</p>";
                $check = false;
                break;
            }
        }
        if ($check == true) {
            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("INSERT INTO teachers (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
                $stmt->bindParam(':name',$name);
                $stmt->bindParam(':surname',$surname);
                $stmt->bindParam(':email',$email);
                $stmt->bindParam(':password',$password);
                $stmt->execute();
                $id = $conn->lastInsertId();
                $_SESSION['id'] = $id;
                header("Location: login.php");
            }
            catch (PDOException $exception){
                echo "Error:" . $exception->getMessage();
            }
        }
    }
}
else {
    echo "<p class='note'>Vyplnené musia byť všetky polia!</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrácia</title>
    <meta charset="UTF-8">
</head>
<body>

<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

    <label for="name">Meno: </label><br>
    <input type="text" name="name" id="name">
    <br>
    <label for="surname">Priezvisko: </label><br>
    <input type="text" name="surname" id="surname">
    <br>
    <label for="email">Email: </label><br>
    <input type="text" name="email" id="email">
    <br>
    <label for="password">Heslo: </label><br>
    <input type="password" name="password" id="password">
    <br>
    <input type="submit" value="REGISTROVAŤ">

</form>

</body>
</html>