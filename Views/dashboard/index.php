<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php include '../layouts/header.php'; 

require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Models/dashboard.php';
require_once __DIR__ . '/../../Models/Course.php';

$db = new Database();
$conn = $db->connect();

$DashboardModel = new Dashboard($conn);
$totalStudents = $DashboardModel->getStudentsCount();
$totalCourses = $DashboardModel->getCourseCount();
$totalDepartments = $DashboardModel->getDepartmentsCount();
$totalStaffs = $DashboardModel->getStaffsCount();
$admissionStats = $DashboardModel->getAdmissionsByYear();
$recentAdmissions = $DashboardModel->getRecentAdmissions();
?>
<div class="container-fluid">
    <div class="row flex-column flex-md-row">
        <?php include '../layouts/sidebar.php'; ?>

        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content">

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

            <!-- Main content-->
            <div class="container-fluid py-2">
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="fs-2">Welcome to Administration Portal</h2>
                        <p class="text-muted mb-0">
                            Here's what's happening in your admission portal today.
                        </p>
                    </div>
                    
                    <div class="col-auto text-end">
                        <h6 class="text-muted d-none d-lg-block  hover-zoom">
                            <i class="bi bi-calendar3 me-2"></i>
                            <?= date("l, d F Y"); ?>
                        </h6>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">

                        <div class="col-xl-3 col-md-6 ">
                            <div class="card shadow-sm h-100 hover-zoom">
                                <a href="../students/list.php" style="text-decoration: none; color: inherit;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-primary fw-semibold">Total Students</h6>
                                            <h2 class="fw-bold text-center"><?php echo $totalStudents?></h2>
                                            <small class="text-muted">Active Students</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-people-fill text-primary fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card shadow-sm h-100 hover-zoom">
                                <a href="../Courses/list.php" style="text-decoration: none; color: inherit;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-success fw-semibold">Total Courses</h6>
                                            <h2 class="fw-bold text-center"><?php echo $totalCourses?></h2>
                                            <small class="text-muted">Available Courses</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-book-half text-success fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card shadow-sm h-100 hover-zoom">
                                <a href="../Courses/list.php" style="text-decoration: none; color: inherit;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-warning fw-semibold">Departments</h6>
                                            <h2 class="fw-bold text-center"><?php echo $totalDepartments?></h2>
                                            <small class="text-muted">Active Departments</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-building text-warning fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card shadow-sm h-100 hover-zoom">
                                <a href="../Staff/listStaff.php" style="text-decoration: none; color: inherit;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="text-info fw-semibold">Staff</h6>
                                            <h2 class="fw-bold text-center"><?php echo $totalStaffs?></h2>
                                            <small class="text-muted">Active Staff</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-person-workspace text-info fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!-- Quick Actions -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i>Quick Actions</h5>
                            </div>
                            <div class="card-body d-grid gap-3 pb-4">
                                <a href="../students/add.php" class="btn btn-primary hover-zoom"><i class="bi bi-person-plus-fill"></i> Add Student</a>
                                <a href="../Courses/addCourses.php" class="btn btn-success hover-zoom"><i class="bi bi-book-half"></i> Add Course</a>
                                <a href="../Staff/addStaff.php" class="btn btn-warning text-white hover-zoom"><i class="bi bi-person-workspace"></i> Add Staff</a>
                                <a href="../students/list.php" class="btn btn-info text-white hover-zoom"><i class="bi bi-eye-fill"></i> View Students</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0">Recent Admissions</h5></div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Student</th>
                                                <th scope="col">Course</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Mobile Number</th>
                                                <th scope="col">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentAdmissions as $index => $student) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($index + 1); ?></td>
                                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                                    <td><?= htmlspecialchars($student['course_name'] ?? 'N/A'); ?></td>
                                                    <td><?= htmlspecialchars($student['department_name'] ?? 'N/A'); ?></td>
                                                    <td><?= htmlspecialchars($student['phone']); ?></td>
                                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                                        <!-- Admission Summary-->
                    <div class="col-lg-6 offset-lg-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white"><h5>Admission Summary</h5></div>
                            <div class="card-body"><canvas id="admissionChart" style="width:100%;max-width:600px"></canvas></div>
                        </div>
                    </div>

            </div>
        </div>    

    </div>
</div> 
<script>
window.admissionChartData = {
    labels: <?= json_encode(array_column($admissionStats, 'year'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
    values: <?= json_encode(array_map('intval', array_column($admissionStats, 'total')), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
};
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('admissionChart');
    if (!ctx || !window.admissionChartData) {
        return;
    }

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: window.admissionChartData.labels,
            datasets: [{
                label: 'Admissions by Year',
                data: window.admissionChartData.values,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796'
                ],
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Student Admissions by Year',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
});

</script>

<?php include '../layouts/footer.php'; ?>