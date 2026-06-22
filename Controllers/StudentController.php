<?php

session_start();
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
                /*'course' => trim($_POST['course']),*/
                'admission_year' => $_POST['admission_year'],
                /*'status' => $_POST['status'],*/
                'course_id' => $_POST['course'],
                'department_id' => $_POST['department'],
                'academic_year' => $_POST['year'],
                'semester' => $_POST['semester']
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

    public function getDepartments()
    {
        $course_id = $_GET['course_id'];

        $departments = $this->student->getDepartments($course_id);

        header('Content-Type: application/json');
        echo json_encode($departments);
    }
    public function index()
    {
        $students = $this->student->getAll();

        require "../Views/students/list.php";
    }
    public function getYear()
    {
        $course_id = $_GET['course_id'];

        $years = $this->student->getYear($course_id);

        header('Content-Type: application/json');
        echo json_encode($years);
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

$controller = new StudentController();

$action = $_GET['action'] ?? '';

switch ($action)
{
    case 'store':
        $controller->add();
        break;

    case 'getDepartments':
        $controller->getDepartments(); 
        break;
    
    case 'getYear':
        $controller->getYear();
        break;
        
    case 'edit':
        $controller->edit();
        break;

    case 'update':
        $controller->update();
        break;

    default:
        $controller->index();
}
/* find any bugs present in these 3 files add.php, studentcontroller.php, student.php */
?>