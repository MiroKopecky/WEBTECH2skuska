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

if (isset($_POST["input_name"]) && is_array($_POST["input_name"])){


    $input_name = $_POST["input_name"];
    $arrlength= count( $input_name);
    $mainArray = [];

    for($x= 0; $x < $arrlength; $x+=2){
        $subArray = [];
        $subArray[0]=$input_name[$x];
        $subArray[1]=$input_name[$x+1];
        $mainArray[$x] = $subArray;
    }

    $a = json_encode($mainArray);
    $question = "Pospájaj čo k sebe patrí:";
    $test_id = $_SESSION['test_id'];
    $type = "pairing";
    $points = $_POST['points'];

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
    <style>
        body {
            font-family: arial;
            padding-left: 10px;
        }

        .input-box {
            margin: 15px 0;
        }

        .input-box input {
            padding: 5px 10px;
            border-radius: 2px;
            border: 1px solid #999;
        }

        .btn {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 2px;
            border: 1px solid #17a2b8;
            color: #fff;
            background-color: #17a2b8;
            margin-left: 25%;
        }

        .btn:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .btn:focus {
            outline: 0;
        }

        .input-box a {
            width: 200px;
            color: red;
            font-size: 13px;
            text-decoration: none;
        }

        .input-box a:hover {
            text-decoration: underline;
        }
        .wrapper{
            margin-left: 25%;
        }

        .info{
            margin-left: 25%;
        }
    </style>
</head>

<body>

<button class="btn add-btn">Add More</button>
<small>Stalačením pridáte input pre dvojicu</small>
<div class="info">
    <p><strong>MAX počet dvojíc 10</strong> </p>
</div>

<form action="" method="post">
    <div class="wrapper">
        <div class="input-box">
            <input type="text" size="120" name="input_name[] " required/>
            <input type="text" size="120" name="input_name[]" required/>
        </div>
    </div>
    <label for="points">Počet bodov: </label>
    <input type="number" name="points" id="points" required><br><br>
    <input type="submit" class="btn" value="Submit" />
</form>

<script type="text/javascript">
    $(document).ready(function () {

        // allowed maximum input fields
        var max_input = 10;

        // initialize the counter for textbox
        var x = 1;

        // handle click event on Add More button
        $('.add-btn').click(function (e) {
            e.preventDefault();
            if (x < max_input) { // validate the condition
                x++; // increment the counter
                $('.wrapper').append(`
            <div class="input-box">
              <input type="text" size="120" name="input_name[] " required/>
              <input type="text" size="120" name="input_name[]" required/>
              <a href="#" class="remove-lnk">Remove</a>
            </div>
          `); // add input field
            }
        });

        // handle click event of the remove link
        $('.wrapper').on("click", ".remove-lnk", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();  // remove input field
            x--; // decrement the counter
        })

    });
</script>
</body>

</html>