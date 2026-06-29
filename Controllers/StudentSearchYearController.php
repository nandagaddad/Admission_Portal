<?php require_once __DIR__ . '/../Config/AuthCheck.php'; ?>
<?php

require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/student.php";

class StudentSearchYearController
{
    private $student;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->connect();

        $this->student = new Student($conn);
    }

    public function searchByYear($year)
    {
        return $this->student->searchByYear($year);
    }
}

if(isset($_GET['year']))
{
    $year = $_GET['year'];

    $controller = new StudentSearchYearController();

    $student = $controller->searchByYear($year);
    //header('Content-Type: application/json');
    echo json_encode($student);
}
