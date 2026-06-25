
<?php
// Prevent caching on login page as well
header("Cache-Control: no-cache, no-store, must-revalidate, private");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Admin Login</title>
</head>
<body class="bg-light">
     <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php
                    session_start();
                    if(isset($_SESSION['error']))                
                    {
                        ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                        unset($_SESSION['error']);
                    }
                    ?>
                    <form action="/Admission_Portal/Controllers/AuthController.php" method="POST">
                        <div class="mb-3 form-group required">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group required">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                            <div class="row">
                                <div class="col-6">
                                    <button
                                        type="submit"
                                        name="login"
                                        class="btn btn-primary w-100">
                                        Login
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button
                                        type="reset"
                                        class="btn btn-secondary w-100">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>            
     </div>
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>