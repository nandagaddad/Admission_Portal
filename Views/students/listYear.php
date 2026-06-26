<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php include '../layouts/header.php'; ?>
<?php

if (!isset($students))
{
    $students = [];
}

?>
<div class="container-fluid">
    <div class="row">
        <!--<div class="col-md-3 col-lg-2 p-2 d-md-block bg-dark overflow-auto sidebar collapse" id="sidebar">-->
            <?php include '../layouts/sidebar.php'; ?>
        <!--</div>-->

        <!-- Search by Admission Year Form -->
        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Search Students by Admission Year
                    </div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label class="form-label">Admission Year</label>
                            <select id="year" class="form-select" onchange="searchStudent()">
                                <option value="">Select Year</option>
                                    <?php
                                        for($year = date('Y'); $year >= 2020; $year--)
                                        {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                    ?>
                            </select>
                        </div>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Application No</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>Address</th>
                                <th>Admission Year</th>
                            </tr>
                        </thead>

                        <tbody id="studentTableBody">

                            <?php foreach($students as $student): ?>

                            <tr>
                                <td><?= $student['id'] ?></td>
                                <td><?= $student['application_no'] ?></td>
                                <td><?= $student['first_name']." ".$student['last_name'] ?></td>
                                <td><?= $student['gender'] ?></td>
                                <td><?= $student['email'] ?></td>
                                <td><?= $student['phone'] ?></td>
                                <td><?= htmlspecialchars($student['course_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['department_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['academic_year'] ?? '') ?></td>
                                <td><?= htmlspecialchars($student['semester'] ?? '') ?></td>
                                <td><?= $student['address'] ?></td>
                                <td><?= $student['admission_year'] ?></td>
                            </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include '../layouts/footer.php'; ?>