<?php
require_once "../config.php";

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
}

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $timelimit = $_POST['time'];
    if (isset($_POST['active'])) {
        $active = 1;
    }
    else {
        $active = 0;
    }
    $teacher_id = $_SESSION['id'];
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO test (code, teacher_id, active, timelimit) VALUES (:code, :teacher_id, :active, :timelimit)");
        $stmt->bindParam(':code',$code);
        $stmt->bindParam(':teacher_id',$teacher_id);
        $stmt->bindParam(':active',$active);
        $stmt->bindParam(':timelimit',$timelimit);
        $stmt->execute();
        $test_id = $conn->lastInsertId();
        $_SESSION['test_id'] = $test_id;
        $_SESSION['created'] = "true";
    }
    catch (PDOException $exception){
        $_SESSION['created'] = "false";
        echo "Test s týmto ID už existuje!";
    }
}

if (isset($_POST['type'])) {
    if ($_POST['type'] == "select") {
        header("Location: create_q_select.php");
    }
    if ($_POST['type'] == "pairing") {
        header("Location: create_q_link.php");
    }
    if ($_POST['type'] == "math") {
        header("Location: create_q_math.php");
    }
    if ($_POST['type'] == "drawing") {
        header("Location: create_q_draw.php");
    }
}

if (isset($_POST['null'])) {
    $_SESSION['created'] = "false";
    header("Location: ../");
}

?>

<!DOCTYPE HTML>
<html lang="sk">
<head>
    <title>Učiteľ</title>
    <meta charset="UTF-8">
</head>
<body>

<form id="createTest" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="code">Kód testu: </label>
    <input type="text" name="code" id="code" required><br>
    <label for="time">Čas na vypracovanie (v minútach): </label>
    <input type="number" name="time" id="time" required><br>
    <label for="active">Aktívny: </label>
    <input type="checkbox" name="active" id="active" checked="checked" value="checked" /><br>
    <input type="submit" value="VYTVORIŤ TEST" />
</form>

<form id="createQuestions" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

    <h2>Pridať otázku:</h2>

    <label for="type">Typ otázky:</label>
    <select id="type" name="type">
        <option value='shortAnswer'>Doplnenie odpovede</option>
        <option value='select'>Výber odpovede</option>
        <option value='pairing'>Párovanie</option>
        <option value='drawing'>Kreslenie</option>
        <option value='math'>Matematický príklad</option>
    </select><br><br>

    <input type="submit" value="VYTVOR OTÁZKU" />

</form>

<?php

session_start();

$test_id = $_SESSION['test_id'];

$teacher_id = $_SESSION['id'];
$questions = null;
$count = null;
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * from testQuestions WHERE test_id='$test_id'");
    $stmt->execute();
    $questions = $stmt->fetchAll();
    $stmt = $conn->prepare("SELECT COUNT(*) from testQuestions WHERE test_id='$test_id'");
    $stmt->execute();
    $count = $stmt->fetchAll();
}
catch (PDOException $exception){
    echo "Error:" . $exception->getMessage();
}

echo "V tomto teste je " . $count[0][0] . " otázok.<br><br>";
foreach ($questions as $question) {
    echo $question[1] . " - " . $question[4] . "<br>";
}

?>
<form id="endButton" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="hidden" value="false" id="null" name="null">
    <input type="submit" value="VYTVORIŤ TEST" />
</form>

<script>

    document.getElementById("createQuestions").style.display = "none";
    document.getElementById("endButton").style.display = "none";

    let created = "<?php echo $_SESSION['created'];?>";
    if (created == "true") {
        document.getElementById("createTest").style.display = "none";
        document.getElementById("createQuestions").style.display = "block";
        document.getElementById("endButton").style.display = "block";
    }

</script>

</body>
</html>