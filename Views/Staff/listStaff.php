<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php

require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Models/Staff.php';
require_once __DIR__ . '/../../Models/Course.php';

$db = new Database();
$conn = $db->connect();

$staffModel = new Staff($conn);
$staffs = $staffModel->getAll();

$Courses = $conn->query(
    "SELECT * FROM courses"
);
?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content"> 

            <div class="card shadow">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Staff List</h4>
                    <a href="../Staff/addStaff.php" class="btn btn-primary"> Add Staff </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                <th>ID</th>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Designation</th>
                                <th>Course</th>
                                <th>Department</th>
                                <th>Qulification</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($staffs)): ?>
                                <?php foreach($staffs as $staff): ?>

                                <tr>
                                    <td><?= $staff['id']; ?></td>
                                    <td><?= htmlspecialchars($staff['staff_id']); ?></td>
                                    <td><?= htmlspecialchars($staff['first_name']." ".$staff['last_name']); ?> </td>
                                    <td><?= htmlspecialchars($staff['gender']); ?></td>
                                    <td><?= htmlspecialchars($staff['email']); ?></td>
                                    <td><?= htmlspecialchars($staff['phone']); ?></td>
                                    <td><?= htmlspecialchars($staff['designation']); ?></td>
                                    <td><?= htmlspecialchars($staff['course_name'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($staff['department_name'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($staff['qualification'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($staff['joining_date'] ?? ''); ?></td>
                                    <td><?php if (htmlspecialchars($staff['status'])== 1) {?>
                                            <p>Active</p> 
                                            <?php } 
                                            elseif(htmlspecialchars($staff['status'])== 0) {?>
                                            <p>Inactive</p> 
                                            <?php }?>
                                    </td>
                                    <td><?= $staff['created_at']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>

                                <tr>
                                    <td colspan="12" class="text-center text-danger">
                                        No Staff Found
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

<?php include '../layouts/footer.php'; ?>