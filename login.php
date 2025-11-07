<?php
session_start();
require_once 'server/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $password = htmlspecialchars(strip_tags($_POST['password']));

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, first_name, last_name, password, role, tel FROM Users WHERE username = :username";
    $params = [
        ':username' => $username
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt && $stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_firstname'] = $user['first_name'];
            $_SESSION['user_lastname'] = $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_tel'] = $user['tel'];

            header("Location: home");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Velonic - Bootstrap 5 Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="assets/images/login.png" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="text-center mt-4">
                                        <div class="auth-brand p-2">
                                            <a href="index.html" class="logo-light">
                                                <img src="assets/images/logo.png" alt="logo" height="70">
                                            </a>
                                            <a href="index.html" class="logo-dark">
                                                <img src="assets/images/logo-dark.png" alt="dark logo" height="70">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-4 my-auto">
                                        <div class="text-center mt-2">
                                            <p class="text-muted mb-1">ระบบบริหารจัดการโครงการ
                                            </p>
                                            <p class="text-muted mb-1">มหาวิทยาลัยมหามกุฏราชวิทยาลัย
                                            </p>
                                        </div>
                                        <!-- form -->
                                        <?php if (isset($error)) : ?>
                                            <div class="alert alert-danger"><?php echo $error; ?></div>
                                        <?php endif; ?>
                                        <form method="POST" action="login.php">
                                            <div class="mb-3">
                                                <label for="emailaddress" class="form-label">ชื่อผู้ใช้</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">รหัสผ่าน</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                            <div class="mb-0 text-start">
                                                <button class="btn btn-soft-primary w-100" type="submit"><i class="ri-login-circle-fill me-1"></i> <span class="fw-bold">Log
                                                        In</span> </button>
                                            </div>

                                            <!-- <div class="text-center mt-4">
                                                <p class="text-muted fs-16">Sign in with</p>
                                                <div class="d-flex gap-2 justify-content-center mt-3">
                                                    <a href="javascript: void(0);" class="btn btn-soft-primary"><i class="ri-facebook-circle-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-danger"><i class="ri-google-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-info"><i class="ri-twitter-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-dark"><i class="ri-github-fill"></i></a>
                                                </div>
                                            </div> -->
                                        </form>
                                        <!-- end form-->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-dark-emphasis">Don't have an account? <a href="auth-register.html" class="text-dark fw-bold ms-1 link-offset-3 text-decoration-underline"><b>Sign up</b></a>
                    </p>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark"> Develop by IT MBU
        </span>
    </footer>
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>