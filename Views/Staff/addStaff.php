<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php include '../layouts/header.php'; ?>
<?php
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Models/student.php';
require_once __DIR__ . '/../../Models/Course.php';
$db = new Database();
$conn = $db->connect();
$Courses = $conn->query(
    "SELECT * FROM courses"
);

?>

<div class="container-fluid">
    <div class="row">
        <?php include '../layouts/sidebar.php'; ?>

        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content">

            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Add Staff</h4>
                </div>

                <div class="card-body">
                    <form action="../../controllers/StaffController.php?action=store" method="POST">

                        <div class="row">
                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Course</label>
                                <select class="form-select" id="course" name="course_id" onchange="getDepartmentsForStaff(this.value)" required>
                                    <option value="">Select Course</option>
                                    <?php while ($row = $Courses->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Department</label>
                                <select class="form-select" id="department" name="department_id" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Staff ID</label>
                                <input type="text" name="staff_id" class="form-control" required>
                            </div>

                            <div class="col w-100 mb-3"></div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" >
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="phone" class="form-control" maxlength="10" required>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control" maxlength="10" required>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control" maxlength="10" required>
                            </div>

                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Date of Joining</label>
                                <input type="date" name="joining_date" class="form-control" required>
                            </div>

                            <div class="d-flex flex-row-reverse">
                            <div class="mt-3">
                                <a href="/Admission_Portal/Views/dashboard/index.php" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Save Staff
                                </button>
                            </div>
                            </div>
                        </div>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getDepartmentsForStaff(course_id)
    {
        let departmentDropdown = document.getElementById('department');

        if (!course_id) {
            departmentDropdown.innerHTML = '<option value="">Select Department</option>';
            return;
        }
        departmentDropdown.innerHTML = '<option value="">Loading...</option>';

        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/Admission_Portal/Controllers/StaffController.php?action=getDepartments&course_id=' + encodeURIComponent(course_id), true);
        xhr.onreadystatechange = function()
        {   
            if (xhr.readyState === 4 && xhr.status === 200)
            {   console.log(xhr.responseText);
                let departments = JSON.parse(xhr.responseText);
                let options = '<option value="">Select Department</option>';
                for (let i = 0; i < departments.length; i++)
                {
                    options += '<option value="' + departments[i].id + '">' + departments[i].department_name + '</option>';
                }
                departmentDropdown.innerHTML = options;
            }
        };
        xhr.send();
    }
</script>
<?php include '../layouts/footer.php'; ?>