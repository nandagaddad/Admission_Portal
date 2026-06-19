<?php

require_once "../Controllers/StudentSearchYearController.php";   

if(isset($_GET['year']))
{
    $year = $_GET['year'];

    $controller = new StudentSearchYearController();

    $student = $controller->searchByYear($year);
    //header('Content-Type: application/json');
    echo json_encode($student);
}