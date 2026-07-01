<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php

require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Models/student.php';
require_once __DIR__ . '/../../Models/Course.php';

$db = new Database();
$conn = $db->connect();

$studentModel = isset($studentModel) ? $studentModel : new Student($conn);
$students = isset($students) ? $students : $studentModel->getAll();
$currentPage = isset($currentPage) ? $currentPage : 1;
$perPage = isset($perPage) ? $perPage : 10;
$totalStudents = isset($totalStudents) ? $totalStudents : $studentModel->countAll();
$totalPagesCount = isset($totalPagesCount) ? $totalPagesCount : ($totalStudents > 0 ? (int) ceil($totalStudents / $perPage) : 1);

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

                    <div class="card-header d-flex ">
                        <h4 class="me-auto">Students List</h4>
                        <input type="text" id="searchInput" class="form-control w-25" placeholder="Search Students..." onkeyup="filterStudentList()">
                        <a href="../students/add.php" class="btn btn-primary ms-2"> Add Student </a>
                    </div>

                <div class="card-body">

                    <?php if(isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-nowrap">Show</span>
                            <form method="GET" action="../../Controllers/StudentController.php" class="d-flex align-items-center">
                                <input type="hidden" name="page" value="1">
                                <select name="limit" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                    <?php foreach ([5, 10, 20, 50] as $option): ?>
                                        <option value="<?= $option ?>" <?= (int) $perPage === $option ? 'selected' : '' ?>><?= $option ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="ms-2 text-nowrap">entries</span>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover" id="StudentTable">

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

                                <tr data-search="<?php echo strtolower($student['first_name'] . ' ' . $student['last_name'] . ' ' . $student['course_name'] . ' '. $student['department_name']); ?>">
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
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm editStudentBtn"
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
                                        <button
                                           class="btn btn-danger btn-sm deleteBtn"
                                           data-bs-toggle="modal"
                                           data-bs-target="#deleteStudentModal"
                                           data-student-id="<?= $student['id']; ?>"
                                           data-student-name="<?= htmlspecialchars($student['first_name'].' '.$student['last_name']); ?>">
                                            Delete
                                        </button>
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
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div id="tableInfo" class="text-muted small">
                                <?php if ($totalStudents > 0): ?>
                                    Showing <?= (($currentPage - 1) * $perPage) + 1 ?> to <?= min($currentPage * $perPage, $totalStudents) ?> of <?= $totalStudents ?> entries
                                <?php else: ?>
                                    Showing 0 to 0 of 0 entries
                                <?php endif; ?>
                            </div>
                            <nav aria-label="Student pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                        <?php if ($currentPage > 1): ?>
                                            <a class="page-link" href="../../Controllers/StudentController.php?page=<?= $currentPage - 1 ?>&limit=<?= $perPage ?>">Previous</a>
                                        <?php else: ?>
                                            <span class="page-link">Previous</span>
                                        <?php endif; ?>
                                    </li>
                                    <?php for ($i = 1; $i <= $totalPagesCount; $i++): ?>
                                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="../../Controllers/StudentController.php?page=<?= $i ?>&limit=<?= $perPage ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= $currentPage >= $totalPagesCount ? 'disabled' : '' ?>">
                                        <?php if ($currentPage < $totalPagesCount): ?>
                                            <a class="page-link" href="../../Controllers/StudentController.php?page=<?= $currentPage + 1 ?>&limit=<?= $perPage ?>">Next</a>
                                        <?php else: ?>
                                            <span class="page-link">Next</span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>   

                </div>
        </div>
    </div>
</div>
<!-- The Edit Student Modal -->
<div class="modal" id="editStudentModal" tabindex="-1">
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
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Application No</label>
                            <input type="text" name="application_no" id="applicationNo" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" id="firstName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="lastName" class="form-control" >
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" id="fatherName" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother Name</label>
                            <input type="text" name="mother_name" id="motherName" class="form-control">
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
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" required>
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
                            <label class="form-label">Admission Year</label>
                            <input type="number" name="admission_year" id="admissionYear" class="form-control" min="2000" max="<?= date('Y'); ?>" required>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Course</label>
                            <select class="form-select" id="course" name="course_id" onchange="loadDepartments(this.value, ''); loadYears(this.value, '', '');" required>
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
                            <label class="form-label">Academic Year</label>
                            <select class="form-select" id="year" name="academic_year" onchange="setSemesterOptions(this.value, '');" required>
                                <option value="">Select Year</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
                            <label class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group required">
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

<!-- Delete Student Modal -->
<div class="modal" id="deleteStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title">Delete Student</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this student?</p>
                <p><strong>Student Name:</strong> <span id="deleteStudentName"></span></p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form action="../../controllers/StudentController.php?action=delete" method="POST" id="deleteForm">
                    <input type="hidden" name="id" id="deleteStudentId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
function setEditModalValues(button) {
    var studentId = button.dataset.studentId || '';
    var applicationNo = button.dataset.studentApplicationNo || '';
    var firstName = button.dataset.studentFirstName || '';
    var lastName = button.dataset.studentLastName || '';
    var fatherName = button.dataset.studentFatherName || '';
    var motherName = button.dataset.studentMotherName || '';
    var gender = button.dataset.studentGender || '';
    var dob = button.dataset.studentDob || '';
    var email = button.dataset.studentEmail || '';
    var phone = button.dataset.studentPhone || '';
    var address = button.dataset.studentAddress || '';
    var admissionYear = button.dataset.studentAdmissionYear || '';
    var courseId = button.dataset.studentCourseId || '';
    var departmentId = button.dataset.studentDepartmentId || '';
    var academicYear = button.dataset.studentAcademicYear || '';
    var semester = button.dataset.studentSemester || '';

    document.getElementById('studentId').value = studentId;
    document.getElementById('applicationNo').value = applicationNo;
    document.getElementById('firstName').value = firstName;
    document.getElementById('lastName').value = lastName;
    document.getElementById('fatherName').value = fatherName;
    document.getElementById('motherName').value = motherName;
    document.getElementById('gender').value = gender;
    document.getElementById('dob').value = dob;
    document.getElementById('email').value = email;
    document.getElementById('phone').value = phone;
    document.getElementById('address').value = address;
    document.getElementById('admissionYear').value = admissionYear;
    document.getElementById('course').value = courseId;

    loadDepartments(courseId, departmentId);
    loadYears(courseId, academicYear, semester);
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button click
    var editButtons = document.querySelectorAll('.editStudentBtn');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            setEditModalValues(button);
        });
    });

    // Handle delete button click
    const deleteButtons = document.querySelectorAll('.deleteBtn');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const studentId = this.getAttribute('data-student-id');
            const studentName = this.getAttribute('data-student-name');
            document.getElementById('deleteStudentId').value = studentId;
            document.getElementById('deleteStudentName').textContent = studentName;
        });
    });
});

function filterStudentList() {
    // Get the search query and convert to lowercase
    const query = document.getElementById('searchInput').value.toLowerCase();
    
    // Target all table rows inside the tbody
    const rows = document.querySelectorAll('#StudentTable tbody tr');

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
<!--
<script>

document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("StudentTable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const maxRowsSelect = document.getElementById("maxRows");
    const paginationWrapper = document.getElementById("paginationWrapper");
    const tableInfo = document.getElementById("tableInfo");
    
    let currentPage = 1;

    function displayTable() {
        const rowsPerPage = parseInt(maxRowsSelect.value);
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        // Normalize current page if rows per page changes drastically
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // Hide all rows initially
        rows.forEach(row => row.style.display = "none");

        // Calculate start and end indices
        const start = (currentPage - 1) * rowsPerPage;
        const end = Math.min(start + rowsPerPage, totalRows);

        // Show rows for the active page
        for (let i = start; i < end; i++) {
            if (rows[i]) rows[i].style.display = "";
        }

        // Update entry information text
        tableInfo.textContent = totalRows > 0 
            ? `Showing ${start + 1} to ${end} of ${totalRows} entries`
            : "Showing 0 to 0 of 0 entries";

        buildPagination(totalPages);
    }

    function buildPagination(totalPages) {
        paginationWrapper.innerHTML = "";

        if (totalPages <= 1) return; // No pagination buttons needed if only 1 page

        // Previous Button
        const prevLi = document.createElement("li");
        prevLi.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
        prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
        paginationWrapper.appendChild(prevLi);

        // Page Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${currentPage === i ? "active" : ""}`;
            li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
            paginationWrapper.appendChild(li);
        }

        // Next Button
        const nextLi = document.createElement("li");
        nextLi.className = `page-item ${currentPage === totalPages ? "disabled" : ""}`;
        nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
        paginationWrapper.appendChild(nextLi);
    }

    // Event listener for pagination clicks
    paginationWrapper.addEventListener("click", function (e) {
        e.preventDefault();
        const targetPage = parseInt(e.target.getAttribute("data-page"));
        if (targetPage && targetPage !== currentPage) {
            currentPage = targetPage;
            displayTable();
        }
    });

    // Event listener for dropdown rows change
    maxRowsSelect.addEventListener("change", function () {
        currentPage = 1;
        displayTable();
    });

    // Initial load
    displayTable();
});
</script>
                                -->                          

<?php include __DIR__ . '/../layouts/footer.php'; ?>