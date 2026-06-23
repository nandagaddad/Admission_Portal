<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/student.php";

class StudentsListController
{
    private $student;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->connect();

        $this->student = new Student($conn);
    }

    public function index()
    {
        $students = $this->student->getAll();

        require "../Views/students/list.php";
    }
}

$controller = new StudentsListController();
$controller->index();