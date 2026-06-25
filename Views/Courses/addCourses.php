<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php include '../layouts/header.php'; ?>
<?php include '../layouts/navbar.php'; ?>
<?php
require_once __DIR__ . '/../../Config/Database.php';

$db = new Database();
$conn = $db->connect();

?>
<div class="container-fluid">
    <div class="row">
        <!--<div class="col-md-3 col-lg-2 p-2 d-md-block bg-dark overflow-auto sidebar collapse" id="sidebar">-->
            <?php include '../layouts/sidebar.php'; ?>
        <!--</div>-->

        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 p-4 content">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Add Course & Departments</h4>
                </div>
                <div class="card-body">

                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/CoursesController.php?action=store" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3 form-group required">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="course_name" class="form-control" required>
                            </div>

                            <div class="col-md-3 mb-3 form-group required">
                                <label class="form-label">Duration (years)</label>
                                <input type="number" name="duration_years" class="form-control" min="1" value="1" required>
                            </div>

                            <div class="col-12 mb-3 form-group required">
                                <label class="form-label">Departments</label>
                                <div id="departments-list">
                                    <div class="input-group mb-2">
                                        <input type="text" name="departments[]" class="form-control" placeholder="Department name" required>
                                        <button class="btn btn-outline-secondary remove-dept" type="button">Remove</button>
                                    </div>
                                </div>
                                <button id="add-dept" class="btn btn-sm btn-secondary" type="button">Add Department</button>
                            </div>

                        </div>

                        <div class="d-flex flex-row-reverse">
                            <div class="mt-3">
                                <a href="/Admission_Portal/Views/dashboard/index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Course</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../layouts/footer.php'; ?>

<script>
document.addEventListener('click', function(e){
    if (e.target && e.target.id === 'add-dept'){
        const list = document.getElementById('departments-list');
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';
        wrapper.innerHTML = '<input type="text" name="departments[]" class="form-control" placeholder="Department name" required> <button class="btn btn-outline-secondary remove-dept" type="button">Remove</button>';
        list.appendChild(wrapper);
    }

    if (e.target && e.target.classList.contains('remove-dept')){
        const list = document.getElementById('departments-list');
        const groups = list.querySelectorAll('.input-group');
        if (groups.length > 1) {
            const group = e.target.closest('.input-group');
            if (group) group.remove();
        } else {
            // prevent removing last department
            alert('At least one department is required.');
        }
    }
});

// ensure initial state: if only one input, keep remove button present but prevent removal
document.addEventListener('DOMContentLoaded', function(){
    const list = document.getElementById('departments-list');
    // nothing extra needed; removal is blocked above when only one remains
});
</script>
