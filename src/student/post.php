<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
include_once "../config.php";


// Get raw posted data


if( !empty(file_get_contents("php://input")) ){
    $data = json_decode(file_get_contents("php://input"),true);

    $testId =  $data['metaData'][0];
    $studentId =  $data['metaData'][1];
    $answers = $data['odpovede'];


    foreach($answers as $answer){
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM testQuestions WHERE id=?';
        $stm = $conn->prepare($sql);
        $stm->bindValue(1,$answer['questionID']);
        $stm->execute();
        $question = $stm->fetch(PDO::FETCH_ASSOC);
        $points = 0;
        if($answer['type'] == 'typ1'){
            if($answer['answer'] == $question['answer']){
                $points = $question['points'];
            }
            $sql = "INSERT INTO studentsAnswerLogs (student_id, testQuestion_id, test_id, answer, points) VALUES (?,?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->execute([$studentId,$answer['questionID'],$testId,$answer['answer'],$points]);

        }elseif($answer['type'] == 'typ2'){
            $ans = json_decode($question['answer'],true);
            $rightAns = $ans[0];
            if($answer['answer'] == $rightAns){
                $points = $question['points'];
            }
            $sql = "INSERT INTO studentsAnswerLogs (student_id, testQuestion_id, test_id, answer, points) VALUES (?,?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->execute([$studentId,$answer['questionID'],$testId,$answer['answer'],$points]);

        }elseif($answer['type'] == 'typ3'){
            $rightAns= json_decode($question['answer'],true);
            $sutdentAns = $answer['answer'];
            $sutdentAnsString = json_encode($answer['answer']);


            $totalMatches =0;
            $index=0;
            foreach($rightAns as $part){
                if($part[1] == $sutdentAns[$index]){
                    $totalMatches++;
                }
                $index++;
            }
            $points = ($question['points']/$index)*$totalMatches;

            $sql = "INSERT INTO studentsAnswerLogs (student_id, testQuestion_id, test_id, answer, points) VALUES (?,?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->execute([$studentId,$answer['questionID'],$testId,$sutdentAnsString,$points]);


        }elseif($answer['type'] == 'typ4'){
            $sql = "INSERT INTO studentsAnswerLogs (student_id, testQuestion_id, test_id, answer) VALUES (?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->execute([$studentId,$answer['questionID'],$testId,$answer['answer']]);

        }elseif($answer['type'] == 'typ5'){
            $sql = "INSERT INTO studentsAnswerLogs (student_id, testQuestion_id, test_id, answer) VALUES (?,?,?,?)";
            $stm = $conn->prepare($sql);
            $stm->execute([$studentId,$answer['questionID'],$testId,$answer['answer']]);
        }
    }
    http_response_code(200);
}else{
    http_response_code(503);
    echo json_encode(
        array('message' => 'Answers not logged')
    );
}



?>