<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Student.php';

$db = new Database();
$conn = $db->connect();

$studentModel = new Student($conn);
$students = $studentModel->getAll();
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
                                        data-bs-target="#editStudentModal" >
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


<?php include __DIR__ . '/../layouts/footer.php'; ?>