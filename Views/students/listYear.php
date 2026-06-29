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
        <?php include '../layouts/sidebar.php'; ?>

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

<script>
    function searchStudent()
    {
        let year = document.getElementById('year').value;
        let tbody = document.getElementById('studentTableBody');

        tbody.innerHTML = `<tr><td colspan="12" class="text-center">Loading...</td></tr>`;

        let xhr = new XMLHttpRequest();
        xhr.open("GET", "/Admission_Portal/Controllers/StudentSearchYearController.php?year=" + encodeURIComponent(year), true);

        xhr.onreadystatechange = function()
        {
            if(xhr.readyState === 4)
            {
                if(xhr.status === 200)
                {   
                    let data = JSON.parse(xhr.responseText);
                    let output = "";

                    if(data.length > 0)
                    {
                        data.forEach(student => {
                            const address = student.address ?? '';
                            const courseName = student.course_name ?? '';
                            const departmentName = student.department_name ?? '';
                            const academicYear = student.academic_year ?? '';
                            const semester = student.semester ?? '';

                            output +=
                            `<tr>
                                <td>${student.id}</td>
                                <td>${student.application_no}</td>
                                <td>
                                    ${student.first_name}
                                    ${student.last_name}
                                </td>
                                <td>${student.gender}</td>
                                <td>${student.email}</td>
                                <td>${student.phone}</td>
                                <td>${courseName}</td>
                                <td>${departmentName}</td>
                                <td>${academicYear}</td>
                                <td>${semester}</td>
                                <td>${address}</td>
                                <td>${student.admission_year}</td>
                            </tr>`;
                        });
                    }
                    else
                    {
                        output =`<tr><td colspan="12" class="text-center text-danger"> No Students Found </td></tr>`;
                    }
                    tbody.innerHTML = output;
                }
            }
        };
        xhr.send();
    }
</script>
<?php include '../layouts/footer.php'; ?>