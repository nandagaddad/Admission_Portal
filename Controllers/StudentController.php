<?php require_once __DIR__ . '/../Config/AuthCheck.php'; ?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/student.php";

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
                'admission_year' => $_POST['admission_year'],
                'course_id' => $_POST['course'],
                'department_id' => $_POST['department'],
                'academic_year' => $_POST['year'],
                'semester' => $_POST['semester']
            ];

            if ($this->student->create($data))
            {
                $_SESSION['success'] = "Student added successfully";
                header("Location: /Admission_Portal/Views/students/list.php");
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
    public function getAll($limit = null, $offset = 0)
    {
        $studentslist = $this->student->getAll($limit, $offset);
        return $studentslist;

    }
    public function index()
    {
        
    }
    public function getStudentListData()
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);

        $search = trim($_GET['search'] ?? '');

        $allowedLimits = [5, 10, 20, 50];

        $page = ($page && $page > 0) ? $page : 1;
        $limit = ($limit && in_array($limit, $allowedLimits, true)) ? $limit : 10;

        $offset = ($page - 1) * $limit;

        $students = $this->student->getAll($limit, $offset, $search);

        $totalStudents = $this->student->countAll($search);

        $totalPagesCount = ($totalStudents > 0)
            ? (int) ceil($totalStudents / $limit)
            : 1;

        if ($page > $totalPagesCount) {
            $page = $totalPagesCount;
            $offset = ($page - 1) * $limit;
            $students = $this->student->getAll($limit, $offset);
        }

        return [
            'students' => $students,
            'search'=>$search,
            'currentPage' => $page,
            'perPage' => $limit,
            'totalStudents' => $totalStudents,
            'totalPagesCount' => $totalPagesCount
        ];
    }
    public function getYear()
    {
        $course_id = $_GET['course_id'];

        $years = $this->student->getYear($course_id);

        header('Content-Type: application/json');
        echo json_encode($years);
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $id = $_POST['id'];
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
                'admission_year' => $_POST['admission_year'],
                'course_id' => $_POST['course_id'],
                'department_id' => $_POST['department_id'],
                'academic_year' => $_POST['academic_year'],
                'semester' => $_POST['semester']
            ];

            if ($this->student->update($id, $data))
            {
                $_SESSION['success'] = "Student added successfully";
                header("Location: /Admission_Portal/Views/students/list.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to add student";
            }
        }
    }
    
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $id = $_POST['id'];

            if ($this->student->delete($id))
            {
                $_SESSION['success'] = "Student deleted successfully";
                header("Location: ../Views/students/list.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to delete student";
                header("Location: ../Views/students/list.php");
                exit();
            }
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
        
    case 'update':
        $controller->update();
        break;

    case 'delete':
        $controller->delete();
        break;

    default:
        $controller->index();
}
?>