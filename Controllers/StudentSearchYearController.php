<?php require_once __DIR__ . '/../Config/AuthCheck.php'; ?>
<?php

require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/Student.php";

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
