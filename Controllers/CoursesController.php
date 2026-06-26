<?php require_once __DIR__ . '/../Config/AuthCheck.php'; ?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Models/Course.php";

class CoursesController
{
    private $courseModel;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->connect();
        $this->courseModel = new Course($conn);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseName = trim($_POST['course_name'] ?? '');
            $duration = intval($_POST['duration_years'] ?? 1);
            $departments = $_POST['departments'] ?? [];

            // Clean and ensure at least one department
            $cleanDepts = [];
            if (is_array($departments)) {
                foreach ($departments as $d) {
                    $t = trim($d);
                    if ($t !== '') $cleanDepts[] = $t;
                }
            }

            if ($courseName === '') {
                $_SESSION['error'] = 'Course name is required';
            } elseif (count($cleanDepts) === 0) {
                $_SESSION['error'] = 'Please add at least one department for the course';
            } else {
                $ok = $this->courseModel->createWithDepartments($courseName, $duration, $cleanDepts);

                if ($ok) {
                    $_SESSION['success'] = 'Course and departments added successfully';
                    header('Location: ../Views/Courses/addCourses.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'Failed to save course';
                }
            }
        }

        require __DIR__ . '/../Views/Courses/addCourses.php';
    }

    public function index()
    {
        $courses = $this->courseModel->getAll();
        require __DIR__ . '/../Views/Courses/list.php';
    }

    public function getCourse()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            return;
        }

        $course = $this->courseModel->getById($id);
        header('Content-Type: application/json');
        echo json_encode($course);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id'] ?? null;
        $courseName = trim($_POST['course_name'] ?? '');
        $duration = intval($_POST['duration_years'] ?? 1);
        $departments = $_POST['departments'] ?? [];

        // Clean depts
        $cleanDepts = [];
        if (is_array($departments)) {
            foreach ($departments as $d) {
                $t = trim($d);
                if ($t !== '') $cleanDepts[] = $t;
            }
        }

        if (!$id || $courseName === '' || count($cleanDepts) === 0) {
            $_SESSION['error'] = 'Invalid input. Course name and at least one department required.';
            header('Location: ../Views/Courses/list.php');
            exit();
        }

        $result = $this->courseModel->updateWithDepartments($id, $courseName, $duration, $cleanDepts);
        if (is_array($result) && $result['success']) {
            $_SESSION['success'] = 'Course updated successfully';
        } else {
            $_SESSION['error'] = $result['error'] ?? 'Failed to update course';
        }

        header('Location: ../Views/Courses/list.php');
        exit();
    }

    public function deleteCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Invalid course id';
            header('Location: ../Views/Courses/list.php');
            exit();
        }

        if ($this->courseModel->delete($id)) {
            $_SESSION['success'] = 'Course deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete course. One or more students are currently enrolled in this course.';
        }

        header('Location: ../Views/Courses/list.php');
        exit();
    }
}

$controller = new CoursesController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'store':
        $controller->add();
        break;
    case 'getCourse':
        $controller->getCourse();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->deleteCourse();
        break;

    default:
        $controller->index();
}

?>
