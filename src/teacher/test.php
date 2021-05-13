<?php
error_reporting(E_ERROR | E_PARSE);
include_once "../config.php";




function getIdForTitle(){
    //**************************************************
    $testid = $_GET['test'];
    $studentid = $_GET['student'];
    echo "<h2 class='row justify-content-center'>Zobrazenie test ID = ". $testid."  a student ID = ". $studentid." </h2>";

}

function fetch_data(){
    $idqueston =  $_GET['idquestion'];
    $points =  $_GET['newpoints'];

    if(($points!="") && ($idqueston !="" )){
        $conn3 = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt3 = $conn3->prepare("UPDATE studentsAnswerLogs SET points = :points WHERE id = :idqueston");
        $stmt3->bindParam(":idqueston", $idqueston, PDO::PARAM_STR);
        $stmt3->bindParam(":points", $points, PDO::PARAM_STR);
        $stmt3->execute();
    }
    //*************************************************
    $testid = $_GET['test'];
    $studentid = $_GET['student'];
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM studentsAnswerLogs where test_id = :testid ");
    $stmt->bindParam(':testid', $testid);
    $stmt->execute();
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '';
    $pairing='';
    $pairing2='';
    foreach ($parameters as $parameter) {
        $testQuestionsID = $parameter['testQuestion_id'];
        $stmt2 = $conn->prepare("SELECT * FROM testQuestions where id = :testQuestionsID ");
        $stmt2->bindParam(':testQuestionsID', $testQuestionsID);
        $stmt2->execute();
        $parameters2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        if ($parameter['student_id'] == $studentid){


            foreach ($parameters2 as $parameter2) {


                $output .= '<tr class="d-flex">
                             
                              <td class="col-1">' . $parameter["id"] . '</td>
                              <td class="col-2">' . $parameter2["question"] . '</td>';
                if($parameter2 ["type"]== "select") {
                    $select = explode("\"", $parameter2["answer"]);
                    $output.='<td class="col-3">' . '"'.$select[1] .'"'. '</td>';
                }
                else{
                    if($parameter2 ["type"]== "pairing"){

                        $rightAns= json_decode($parameter2['answer'],true);
                        $pairing.= '[';
                        foreach($rightAns as $part){
                            $pairing.= '"'.$part[0]. '",';
                        }
                        $pairing.= ']';
                        $rightAns2= json_decode($parameter2['answer'],true);
                        $pairing2.= '[';
                        foreach($rightAns2 as $part2){
                            $pairing2.= '"'.$part2[1]. '",';
                        }
                        $pairing2.= ']';
                        $output.='<td class="col-3">'.$pairing.'-'.$pairing2.' </td>';
                    }
                    else{$output.='<td class="col-3">' . $parameter2["answer"] . '</td>';}
                }

                if($parameter2 ["type"]== "drawing"){
                    $output.='<td class="col-5"><img src="'.$parameter["answer"].'" alt="" width="550em"></td>';
                }
                else {
                    if ($parameter2 ["type"] == "pairing") {
                        $output .= '<td class="col-5">' . $pairing .'-'. $parameter["answer"] . '</td>';
                        $pairing = '';
                    } else {$output .= '<td class="col-5">' . $parameter["answer"] . '</td>';}
                }

                $output .='<td class="col-1">' . $parameter["points"] . '</td>
                        ';

                $output.='</tr>';

            }
        }
    }
    return $output;

}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ViewTeacher</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body style="padding: 2em;">




<?php
echo getIdForTitle();
?>

<br>
<div id="topdf">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr class="d-flex">

            <th class="col-1">id</th>
            <th class="col-2">Question</th>
            <th class="col-3">Correct Answer</th>
            <th class="col-5">Student Answer</th>
            <th class="col-1">Points</th>


        </tr>
        </thead>
        <tbody>
        <?php
        echo fetch_data();

        ?>
        </tbody>
    </table>
    <form name="form" action="" method="get">
        <h4>Zadaj ID otázky v ktorej chceš zmeniť body:</h4>
        <input class="input-group mb-3" type="int" name="idquestion" id="idquestion" value="">
        <h4>Zadaj počet bodov:</h4>
        <input class="input-group mb-3" type="double" name="newpoints" id="newpoints" value="">
        <button type="submit" class="btn btn-secondary row justify-content-center">Update</button>
    </form>
    <!--    <div class="d-flex justify-content-center ">-->
    <!--       // <button class="btn btn-secondary row justify-content-center" onclick="sendPoints()"Save</button>-->
    <!--    </div>-->
</div>

</body>

</html>