<?php require_once __DIR__ . '/../../Config/AuthCheck.php'; ?>
<?php include '../layouts/header.php'; ?>
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

            <h2>Dashboard</h2>
            <div class="card">
                <div class="card-body"> Welcome to Admission Portal </div>
            </div>
        </div>      
    </div>
</div>    

<?php include '../layouts/footer.php'; ?>