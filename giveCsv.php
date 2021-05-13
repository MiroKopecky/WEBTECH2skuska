<?php

    include_once "config/Database.php";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=hodnotenia.csv');

    $database = new Database();
    $conn = $database->createConnection();

    $testId = 1;

    $sql = "SELECT testParticipants.student_id, students.name, students.surname, students.aisid FROM testParticipants JOIN test ON testParticipants.test_id = test.id JOIN students ON students.id = testParticipants.student_id WHERE test.id=$testId AND testParticipants.status='done'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $vysledky = $stmt->fetchAll(PDO::FETCH_ASSOC);
  


    $output = fopen("php://output", "wb");
    fputcsv($output, array('Ais ID', 'Krstné meno', 'Priezvisko', 'Celkové body'));

    foreach($vysledky as $vysledok){
        $oneRecord = [];

        $sql = "SELECT SUM(points) as points FROM studentsAnswerLogs WHERE studentsAnswerLogs.student_id=? AND studentsAnswerLogs.test_id=?";
        $stm = $conn->prepare($sql);
        $stm->bindValue(1,$vysledok["student_id"]);
        $stm->bindValue(2,$testId);
        $stm->execute();
        $points = $stm->fetch(PDO::FETCH_ASSOC);
        array_push($oneRecord, $vysledok['aisid'], $vysledok['name'], $vysledok['surname'], $points["points"]);

        fputcsv($output, $oneRecord);

    }
    fclose($output);



?>
