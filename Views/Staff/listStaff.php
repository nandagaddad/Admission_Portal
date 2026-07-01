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

                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php elseif (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card-header d-flex">
                    <h4 class="me-auto">Staff List</h4>
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="Search Staff..." onkeyup="filterStaffList()">
                    <a href="../Staff/addStaff.php" class="btn btn-primary ms-2"> Add Staff </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover" id="staffTable">

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
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($staffs)): ?>
                                <?php foreach($staffs as $staff): ?>

                                <tr data-search="<?php echo strtolower($staff['first_name'] . ' ' . $staff['last_name'] . ' ' . $staff['designation'] . ' ' . $staff['course_name'] . ' '. $staff['department_name']); ?>">
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
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm editStaffBtn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStaffModal"
                                            data-staff-id="<?= $staff['id']; ?>"
                                            data-staff-staff_id="<?= htmlspecialchars($staff['staff_id']); ?>"
                                            data-staff-first_name="<?= htmlspecialchars($staff['first_name']); ?>"
                                            data-staff-last_name="<?= htmlspecialchars($staff['last_name']); ?>"
                                            data-staff-gender="<?= htmlspecialchars($staff['gender']); ?>"
                                            data-staff-email="<?= htmlspecialchars($staff['email']); ?>"
                                            data-staff-phone="<?= htmlspecialchars($staff['phone']); ?>"
                                            data-staff-designation="<?= htmlspecialchars($staff['designation']); ?>"
                                            data-staff-course_id="<?= $staff['course_id']; ?>"
                                            data-staff-department_id="<?= $staff['department_id']; ?>"
                                            data-staff-qualification="<?= htmlspecialchars($staff['qualification']); ?>"
                                            data-staff-joining_date="<?= htmlspecialchars($staff['joining_date']); ?>">
                                            Edit
                                        </button>
                                        <button
                                           class="btn btn-danger btn-sm deleteStaffBtn"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteStaffModal"
                                           data-staff-id="<?= $staff['id']; ?>"
                                           data-staff-name="<?= htmlspecialchars($staff['first_name'].' '.$staff['last_name']); ?>">
                                            Delete
                                        </button>
                                    </td>
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

<!-- The Edit Staff Modal -->
<div class="modal" id="editStaffModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Staff Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="../../controllers/StaffController.php?action=update" method="POST">
                    <input type="hidden" name="id" id="staffId">
                    <div class="row g-3">
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Staff ID</label>
                            <input type="text" name="staff_id" id="staff_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" >
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" min="2000" max="<?= date('Y'); ?>" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Course</label>
                            <select class="form-select" id="course_id" name="course_id" onchange="loadDepartments(this.value, ''); loadYears(this.value, '', '');" required>
                                <option value="">Select Course</option>
                                <?php while ($row = $Courses->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Qualification</label>
                            <input type="text" name="qualification" id="qualification" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">joining_date</label>
                            <input type="date" name="joining_date" id="joining_date" class="form-control" required>
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

<!-- Delete Student Modal -->
<div class="modal" id="deleteStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">Delete Staff</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this staff?</p>
                <p><strong>Staff Name:</strong> <span id="deleteStaffName"></span></p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form action="../../controllers/StaffController.php?action=delete" method="POST" >
                    <input type="hidden" name="id" id="deleteStaffId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
function setEditModalValuesForStaff(button) {
        var staffId = button.dataset.staffId || '';
        var staff_id = button.dataset.staffStaff_id || '';
        var first_name = button.dataset.staffFirst_name || '';
        var last_name = button.dataset.staffLast_name || '';
        var gender = button.dataset.staffGender || '';
        var email = button.dataset.staffEmail || '';
        var phone = button.dataset.staffPhone || '';
        var designation = button.dataset.staffDesignation || '';
        var course_id = button.dataset.staffCourse_id || '';
        var department_id = button.dataset.staffDepartment_id || '';
        var qualification = button.dataset.staffQualification || '';
        var joining_date = button.dataset.staffJoining_date || '';

        document.getElementById('staffId').value = staffId;
        document.getElementById('staff_id').value = staff_id;
        document.getElementById('first_name').value = first_name;
        document.getElementById('last_name').value = last_name;
        document.getElementById('gender').value = gender;
        document.getElementById('email').value = email;
        document.getElementById('phone').value = phone;
        document.getElementById('designation').value = designation;
        document.getElementById('course_id').value = course_id;

        loadDepartments(course_id, department_id);

        document.getElementById('qualification').value = qualification;
        document.getElementById('joining_date').value = joining_date
    }

document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button click
    var editButtons = document.querySelectorAll('.editStaffBtn');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            setEditModalValuesForStaff(button);
        });
    });
    // Handle delete button click
    const deleteButtons = document.querySelectorAll('.deleteStaffBtn');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.getAttribute('data-staff-id');
            const staffName = this.getAttribute('data-staff-name');
            document.getElementById('deleteStaffId').value = staffId;
            document.getElementById('deleteStaffName').textContent = staffName;
        });
    });
});

function filterStaffList() {
    // Get the search query and convert to lowercase
    const query = document.getElementById('searchInput').value.toLowerCase();
    
    // Target all table rows inside the tbody
    const rows = document.querySelectorAll('#staffTable tbody tr');

    rows.forEach(row => {
        // Read the pre-compiled searchable text from the data attribute
        const searchableText = row.getAttribute('data-search');

        // Check if the query exists anywhere inside first_name, last_name, or designation
        if (searchableText.includes(query)) {
            row.style.display = "";      // Show row
        } else {
            row.style.display = "none";  // Hide row
        }
    });
}

</script>

<?php include '../layouts/footer.php'; ?>