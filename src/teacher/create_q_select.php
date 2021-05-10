<?php

session_start();

if ($_SESSION['teacher_check'] == false) {
    header("Location: ../login.php");
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
}

require_once "../config.php";

if (isset($_POST['question'])) {
    $question = $_POST['question'];
    $answerTrue = $_POST['answerTrue'];
    $answerFalse = $_POST["answer"];
    $test_id = $_SESSION['test_id'];
    $type = "select";
    $points = $_POST['points'];
    $answers = [];
    array_push($answers,$answerTrue);

    foreach ($answerFalse as $answer) {
        array_push($answers,$answer);
    }

    $a = json_encode($answers);

    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO testQuestions (question, answer, test_id, type, points) VALUES (:question, :answer, :test_id, :type, :points)");
        $stmt->bindParam(':question',$question);
        $stmt->bindParam(':answer',$a);
        $stmt->bindParam(':test_id',$test_id);
        $stmt->bindParam(':type',$type);
        $stmt->bindParam(':points',$points);
        $stmt->execute();
        header("Location: create_test.php");
    }
    catch (PDOException $exception){
        echo "Error." . $exception;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<button class="btn add-answer">Pridaj odpoveď</button>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="question">Zadaj otázku: </label><br>
    <input type="text" size="50" name="question" id="question" required>
    <label for="points">Počet bodov: </label>
    <input type="number" name="points" id="points" required>
    <br>
    <div class="answers">
        <div>
            <label for="answerTrue">Správna odpoveď: </label><br>
            <input type="text" name="answerTrue" required/>
        </div><br>
        <p>Nesprávne odpovede</p>
        <div class="input-box">
            <input type="text" name="answer[]" required/>
        </div>
    </div>
    <input type="submit" id="submit" value="Submit" />
</form>

<script type="text/javascript">
    $(document).ready(function () {

        let max_input = 8;
        let counter = 1;

        $('.add-answer').click(function (e) {
            e.preventDefault();
            if (counter < max_input) {
                counter++;
                $('.answers').append(`
                    <div>
                      <input type="text" name="answer[]" required/>
                      <a href="#" class="remove-answer">Odstráň odpoveď</a>
                    </div>
                `);
            }
        });

        $('.answers').on("click", ".remove-answer", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            counter--;
        })

    });
</script>
</body>
</html>