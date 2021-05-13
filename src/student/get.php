<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../config.php";

session_start();

function separate($data){
    $sides = [];
    $left= [];
    $right= [];
    $index = 0;
    foreach($data as $part){
        $left[$index] = $part[0];
        $right[$index] = $part[1];
        $index++;
    }
    $sides['rightCol'] = $right;
    $sides['leftCol'] =  $left;
    return $sides;
}


if(isset($_GET['testId']))

$testId = $_GET['testId'];

if($testId  != null){
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM testQuestions WHERE test_id='{$testId}'";
    $stm = $conn->query($sql);
    $questions = $stm->fetchAll(PDO::FETCH_ASSOC);
    if($questions != null){

        $array = array();
        $array['otazky'] = array();


        foreach($questions as $question){
            if($question['type'] == 'shortAnswer'){
                $array['otazky'];
                array_push($array['otazky'], array
                (
                    "type" =>"typ1",
                    "questionID" =>$question['id'],
                    "question" => $question['question']
                ));
            }else if($question['type'] == 'select'){
                $subarray = array();
                $subarray["textOtazky"] = $question['question'];
                $subarray["mozneOdpovede"] = json_decode($question['answer'],true);
                array_push($array['otazky'],array
                (
                    "type" =>"typ2",
                    "questionID" =>$question['id'],
                    "question" => $subarray
                ));
            }else if($question['type'] == 'pairing'){

                $sides = separate(json_decode($question['answer'],true));
                $subarray = array();

                $subarray['rightCol'] = $sides['rightCol'];
                $subarray['leftCol'] = $sides['leftCol'];

                array_push($array['otazky'],array
                (
                    "type" =>"typ3",
                    "questionID" =>$question['id'],
                    "question" => $subarray
                ));

            }else if($question['type'] == 'drawing'){
                array_push($array['otazky'],array
                (
                    "type" =>"typ4",
                    "questionID" =>$question['id'],
                    "question" => $question['question']
                ));

            }else{
                array_push($array['otazky'],array
                (
                    "type" =>"typ5",
                    "questionID" =>$question['id'],
                    "question" => $question['question']
                ));
            }

        }
        http_response_code(200);
        echo json_encode($array);
    }else{
        http_response_code(404);
        echo json_encode(
            array('message' => 'No questions in the requested')
        );
    }

}else{
    http_response_code(404);
    echo json_encode(
        array('message' => 'No test available with requested code')
    );
}

?>
