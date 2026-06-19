<?php
session_start();

require_once "../Config/database.php";
require_once "../Models/Student.php";

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