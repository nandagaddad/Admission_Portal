<?php require_once __DIR__ . '/../Config/AuthCheck.php'; ?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/Staff.php";

class StaffController
{
    private $Staff;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->connect();
        $this->Staff = new Staff($conn);
    }

    public function getDepartments()
    {
        $course_id = $_GET['course_id'];

        $departments = $this->Staff->getDepartments($course_id);

        header('Content-Type: application/json');
        echo json_encode($departments);
    }

    public function index()
    {
        $students = $this->Staff->getAll();

        require "../Views/Stafff/listStaff.php";
    }
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $data = [
                'staff_id' => trim($_POST['staff_id']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'gender' => $_POST['gender'],
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'designation' => trim($_POST['designation']),
                'course_id' => $_POST['course_id'],
                'department_id' => $_POST['department_id'],
                'qualification' => $_POST['qualification'],
                'joining_date' => $_POST['joining_date'],
            ];

            if ($this->Staff->create($data))
            {
                $_SESSION['success'] = "Staff added successfully";
                header("Location: /Admission_Portal/Views/dashboard/index.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to add Staff";
            }
        }

        require "../Views/students/add.php";
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $id = $_POST['id'];
            $data = [
                'staff_id' => trim($_POST['staff_id']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'gender' => $_POST['gender'],
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'designation' => trim($_POST['designation']),
                'course_id' => $_POST['course_id'],
                'department_id' => $_POST['department_id'],
                'qualification' => $_POST['qualification'],
                'joining_date' => $_POST['joining_date'],
            ];
            if ($this->Staff->update($id, $data))
            {
                $_SESSION['success'] = "Staff Details Updated successfully";
                header("Location: /Admission_Portal/Views/Staff/listStaff.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to update Staff details";
            }
        
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $id = $_POST['id'];

            if ($this->Staff->delete($id))
            {
                $_SESSION['success'] = "Student deleted successfully";
                header("Location: ../Views/Staff/listStaff.php");
                exit();
            }
            else
            {
                $_SESSION['error'] = "Failed to delete student";
                header("Location: ../Views/Staff/listStaff.php");
                exit();
            }
        }
    }

}

$controller = new StaffController();

$action = $_GET['action'] ?? '';
switch ($action)
{   
    case 'store':
        $controller->add();
        break;

    case 'getDepartments':
        $controller->getDepartments(); 
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