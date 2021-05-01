<?php
require_once "config.php";
session_start();

$_SESSION['teacher_check'] = false;
$_SESSION['student_check'] = false;

if (isset($_POST['ucitel'])) {
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

    if (isset($_POST['email']) && ($_POST['password'])) {
        if ($_POST['email'] != "" && $_POST['password'] != "") {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            $_SESSION['email'] = $email;
            foreach ($teachers as $teacher) {
                if ($email == $teacher["email"]) {
                    if ($password == $teacher["password"]) {
                        $_SESSION['id'] = $teacher['id'];
                        $_SESSION['teacher_check'] = true;
                        header("Location: index.php");
                    }
                }
            }
        }
        echo "<p class='note'>Nesprávny email alebo heslo!</p>";
    }
}
else if (isset($_POST['student'])) {
    if ($_POST['name'] != "" && $_POST['surname'] != "" && $_POST['ais_id'] != null) {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $ais_id = $_POST['ais_id'];
        if ($ais_id < 10000 || $ais_id > 120000) {
            echo "<p class='note'>Neplatné AIS ID!</p>";
        }
        else {
            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("INSERT INTO students (name, surname, AISid) VALUES (:name, :surname, :ais_id)");
                $stmt->bindParam(':name',$name);
                $stmt->bindParam(':surname',$surname);
                $stmt->bindParam(':ais_id',$ais_id);
                $stmt->execute();
                $id = $conn->lastInsertId();
                $_SESSION['id'] = $id;
                $_SESSION['student_check'] = true;
                header("Location: ./student");
            }
            catch (PDOException $exception){
                echo "Tento študent už píše test!";
            }
        }
    }
    else {
        echo "<p class='note'>Vyplňte všetky údaje!</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Prihlásenie</title>
    <meta charset="UTF-8">
</head>
<body>

<div>
    <input type="button" onclick="switch_role_teacher()" value="UČITEĽ" />
    <input type="button" onclick="switch_role_student()" value="ŠTUDENT" />
</div>

<div id="teacher_login">

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

        <label for="email">Email: </label><br>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">Heslo: </label><br>
        <input type="password" name="password" id="password">
        <br>
        <input type="hidden" name="ucitel" id="ucitel" value="ucitel">
        <input type="submit" value="PRIHLÁSIŤ">

    </form>

</div>

<div id="student_login">

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

        <label for="name">Meno: </label><br>
        <input type="text" name="name" id="name">
        <br>
        <label for="surname">Priezvisko: </label><br>
        <input type="text" name="surname" id="surname">
        <br>
        <label for="ais_id">AIS ID: </label><br>
        <input type="number" name="ais_id" id="ais_id">
        <br>
        <input type="hidden" name="student" id="student" value="student">
        <input type="submit" value="OTVORIŤ TEST">

    </form>

</div>

<div>
    <input id="signin" type="button" onclick="location.href='signin.php';" value="ZAREGISTROVAŤ" />
</div>

<script>

    document.getElementById("student_login").style.display = "none";

    let student = "<?php echo $_POST['student'];?>";
    let teacher = "<?php echo $_POST['ucitel'];?>";
    if (student != "") {
        switch_role_student();
    }
    else if (teacher != "") {
        switch_role_teacher();
    }

    function switch_role_teacher() {
        document.getElementById("teacher_login").style.display = "block";
        document.getElementById("student_login").style.display = "none";
        document.getElementById("signin").style.display = "block";
    }

    function switch_role_student() {
        document.getElementById("teacher_login").style.display = "none";
        document.getElementById("student_login").style.display = "block";
        document.getElementById("signin").style.display = "none";
    }

</script>

</body>
</html>