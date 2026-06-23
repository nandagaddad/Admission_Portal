<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Student.php';

$db = new Database();
$conn = $db->connect();

$studentModel = new Student($conn);
$students = $studentModel->getAll();

$Courses = $conn->query(
    "SELECT * FROM courses"
);

?>

<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<div class="Container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 d-md-block bg-dark sidebar collapse" id="sidebar">
            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>
        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> 
            <div class="container mt-4">

                <div class="card shadow">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Students List</h4>
                        <a href="../students/add.php" class="btn btn-primary"> Add Student </a>
                    </div>

                <div class="card-body">

                    <?php if(isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; ?>
                        </div>
                    <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                <th>ID</th>
                                <th>Application No</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>Address</th>
                                <th>Admission Year</th>
                                <th>Created At</th>
                                <th>Action</th> 
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(!empty($students)): ?>
                                <?php foreach($students as $student): ?>

                                <tr>
                                    <td><?= $student['id']; ?></td>
                                    <td><?= htmlspecialchars($student['application_no']); ?></td>
                                    <td><?= htmlspecialchars($student['first_name']." ".$student['last_name']); ?> </td>
                                    <td><?= htmlspecialchars($student['gender']); ?></td>
                                    <td><?= $student['dob']; ?></td>
                                    <td><?= htmlspecialchars($student['email']); ?></td>
                                    <td><?= htmlspecialchars($student['phone']); ?></td>
                                    <td><?= htmlspecialchars($student['course_name'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($student['department_name'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($student['academic_year'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($student['semester'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($student['address']); ?></td>
                                    <td><?= $student['admission_year']; ?></td>
                                    <td><?= $student['created_at']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStudentModal"
                                            data-student-id="<?= $student['id']; ?>"
                                            data-student-application-no="<?= htmlspecialchars($student['application_no']); ?>"
                                            data-student-first-name="<?= htmlspecialchars($student['first_name']); ?>"
                                            data-student-last-name="<?= htmlspecialchars($student['last_name']); ?>"
                                            data-student-father-name="<?= htmlspecialchars($student['father_name']); ?>"
                                            data-student-mother-name="<?= htmlspecialchars($student['mother_name']); ?>"
                                            data-student-gender="<?= htmlspecialchars($student['gender']); ?>"
                                            data-student-dob="<?= $student['dob']; ?>"
                                            data-student-email="<?= htmlspecialchars($student['email']); ?>"
                                            data-student-phone="<?= htmlspecialchars($student['phone']); ?>"
                                            data-student-address="<?= htmlspecialchars($student['address']); ?>"
                                            data-student-admission-year="<?= $student['admission_year']; ?>"
                                            data-student-course-id="<?= $student['course_id']; ?>"
                                            data-student-department-id="<?= $student['department_id']; ?>"
                                            data-student-academic-year="<?= htmlspecialchars($student['academic_year']); ?>"
                                            data-student-semester="<?= htmlspecialchars($student['semester']); ?>">
                                            Edit
                                        </button>
                                        <a href="delete.php?id=<?= $student['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this student?')">
                                            Delete
                                        </a>
                                    </td>
                                    
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>

                                <tr>
                                    <td colspan="12" class="text-center text-danger">
                                        No Students Found
                                    </td>
                                </tr>

                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>   

                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Edit Student Modal -->
<div class="modal" id="editStudentModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Student Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="../../controllers/StudentController.php?action=update" method="POST">
                    <input type="hidden" name="id" id="studentId">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Application No</label>
                            <input type="text" name="application_no" id="applicationNo" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" id="firstName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="lastName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" id="fatherName" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother Name</label>
                            <input type="text" name="mother_name" id="motherName" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Admission Year</label>
                            <input type="number" name="admission_year" id="admissionYear" class="form-control" min="2000" max="<?= date('Y'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Course</label>
                            <select class="form-select" id="course" name="course" onchange="loadDepartments(this.value, ''); loadYears(this.value, '', '');" required>
                                <option value="">Select Course</option>
                                <?php while ($row = $Courses->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Academic Year</label>
                            <select class="form-select" id="year" name="year" onchange="setSemesterOptions(this.value, '');" required>
                                <option value="">Select Year</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>