<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php

require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Models/Course.php';

$db = new Database();
$conn = $db->connect();

$courseModel = new Course($conn);
$courses = $courseModel->getAll();

?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Courses</h4>
                    <a href="addCourses.php" class="btn btn-primary"> Add Course </a>
                </div>
                <div class="card-body">

                    <?php if(isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Course Name</th>
                                    <th>Duration (years)</th>
                                    <th>Departments</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($courses)): ?>
                                    <?php foreach($courses as $course): ?>
                                        <tr>
                                            <td><?php echo $course['id']; ?></td>
                                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                            <td><?php echo htmlspecialchars($course['duration_years']); ?></td>
                                            <td>
                                                <?php
                                                    $depts = $conn->prepare("SELECT department_name FROM departments WHERE course_id = :cid ORDER BY id ASC");
                                                    $depts->execute([':cid' => $course['id']]);
                                                    $names = $depts->fetchAll(PDO::FETCH_COLUMN);
                                                    echo htmlspecialchars(implode(', ', $names));
                                                ?>
                                            </td>
                                            <td><?php echo $course['created_at']; ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $course['id']; ?>">Edit</button>
                                                <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $course['id']; ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-danger">No Courses Found</td>
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

<!-- Edit Modal -->
<div class="modal" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Course</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm" method="POST" action="../../controllers/CoursesController.php?action=update">
                    <input type="hidden" name="id" id="editCourseId">
                    <div class="row g-3">
                        <div class="col-md-6 form-group required">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" id="editCourseName" class="form-control" required>
                        </div>
                        <div class="col-md-3 form-group required">
                            <label class="form-label">Duration (years)</label>
                            <input type="number" name="duration_years" id="editDuration" class="form-control" min="1" required>
                        </div>
                        <div class="col-12 form-group required">
                            <label class="form-label">Departments</label>
                            <div id="edit-departments-list"></div>
                            <button type="button" id="edit-add-dept" class="btn btn-sm btn-secondary mt-2">Add Department</button>
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

<!-- Delete Modal -->
<div class="modal" id="deleteCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">Delete Course</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this course?</p>
                <p><strong>Course:</strong> <span id="deleteCourseName"></span></p>
                <p class="text-danger"><strong>Warning:</strong> This will remove the course and its departments.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteCourseForm" method="POST" action="../../controllers/CoursesController.php?action=delete">
                    <input type="hidden" name="id" id="deleteCourseId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

