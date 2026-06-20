<?php

require_once "../Config/database.php";
require_once "../Models/Student.php";

class StudentController
{
    private $student;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->connect();
        $this->student = new Student($conn);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $data = [
                'application_no' => trim($_POST['application_no']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'father_name' => trim($_POST['father_name']),
                'mother_name' => trim($_POST['mother_name']),
                'gender' => $_POST['gender'],
                'dob' => $_POST['dob'],
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'course' => trim($_POST['course']),
                'admission_year' => $_POST['admission_year'],
                'status' => $_POST['status']
            ];

            if ($this->student->create($data))
            {
                $_SESSION['success'] = "Student added successfully";

                header("Location: /Admission_Portal/Views/dashboard/index.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to add student";
            }
        }

        require "../Views/students/add.php";
    }
    public function index()
    {
        $students = $this->student->getAll();

        require "../Views/students/list.php";
    }
    public function edit()
    {
        $id = $_GET['id'];

        $student = $this->student->getById($id);

        require "../Views/students/edit.php";
    }

    public function update()
    {
        $id = $_POST['id'];

        if ($this->student->update($id, $_POST))
        {
            header("Location: ../Views/students/list.php");
            exit();
        }
    }
}

$studentController = new StudentController();
$studentController->add();

?>