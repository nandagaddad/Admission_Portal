<?php include '../layouts/header.php'; ?>
<?php include '../layouts/navbar.php'; ?>
<div class="Container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 d-md-block bg-dark sidebar collapse" id="sidebar">
            <?php include '../layouts/sidebar.php'; ?>
        </div>

        <!-- Add Student Form -->
        <div class="col-md-9 col-lg-10 p-4">

            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Add Student Admission</h4>
                </div>

                <div class="card-body">

                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="../../controllers/StudentController.php?action=store" method="POST">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Application No</label>
                                <input type="text" name="application_no" class="form-control" required>
                            </div>

                            <div class="col w-100 mb-3"></div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father Name</label>
                                <input type="text" name="father_name" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mother Name</label>
                                <input type="text" name="mother_name" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="phone" class="form-control" maxlength="10" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Course</label>
                                <input type="text" name="course" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Admission Year</label>
                                <input type="number"
                                       name="admission_year"
                                       class="form-control"
                                       min="2000"
                                       max="<?= date('Y'); ?>"
                                       value="<?= date('Y'); ?>"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                </select>
                            </div>

                        </div>
                        <div class="d-flex flex-row-reverse">
                        <div class="mt-3">
                            <a href="/Admission_Portal/Views/dashboard/index.php" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Admission
                            </button>
                        </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>


<?php include '../layouts/footer.php'; ?>