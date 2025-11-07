<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';
// // แสดงข้อผิดพลาดใน PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ตรวจสอบสิทธิ์ Superadmin
checkPermission('Superadmin');

if (!isset($_GET['id'])) {
    header('Location: manage_budget.php');
    exit;
}
$encoded_id = htmlspecialchars(strip_tags($_GET['id']));
$budget_id = decrypt(urldecode($encoded_id));
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = htmlspecialchars(strip_tags($_POST['year'])) - 543; // แปลงปี พ.ศ. เป็น ค.ศ.
    $budget = htmlspecialchars(strip_tags($_POST['budget']));
    $remaining_budget = htmlspecialchars(strip_tags($_POST['remaining_budget']));
    $project_name = htmlspecialchars(strip_tags($_POST['project_name']));
    $department_id = htmlspecialchars(strip_tags($_POST['department_id']));
    $funding = htmlspecialchars(strip_tags($_POST['funding']));

    $query = "UPDATE budget SET year = :year, budget = :budget, remaining_budget = :remaining_budget, project_name = :project_name, department_id = :department_id, funding = :funding WHERE id = :id";
    $params = [
        ':year' => $year,
        ':budget' => $budget,
        ':remaining_budget' => $remaining_budget,
        ':project_name' => $project_name,
        ':department_id' => $department_id,
        ':funding' => $funding,
        ':id' => $budget_id
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt) {
        $response['status'] = 'success';
        $response['message'] = 'แก้ไขข้อมูลงบประมาณสำเร็จ';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
} else {
    $query = "SELECT * FROM budget WHERE id = :id";
    $params = [':id' => $budget_id];
    $stmt = $database->secureQuery($query, $params);

    if ($stmt && $stmt->rowCount() > 0) {
        $budget_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['error'] = "Budget entry not found.";
        header('Location: manage_budget');
        exit;
    }
}

$departments = getDepartments();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./partials/head.php'); ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">


        <!-- ========== Topbar Start ========== -->
        <?php include('./partials/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->

        <?php include('./partials/sidebar.php'); ?>
        <!-- ========== Left Sidebar Start ========== -->

        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <!-- <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Velonic</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                        <li class="breadcrumb-item active">Welcome!</li>
                                    </ol> -->
                                </div>
                                <h4 class="page-title">ข้อมูลงบประมาณ</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="header-title">ปรับแก้ข้อมูลงบประมาณ</h4>
                                    <!-- <p class="text-muted mb-0">
                                        DataTables has most features enabled by default, so all you need to do to use it
                                        with your own tables is to call the construction
                                        function:
                                        <code>$().DataTable();</code>. KeyTable provides Excel like cell navigation on
                                        any table. Events (focus, blur, action etc) can be assigned to individual
                                        cells, columns, rows or all cells.
                                    </p> -->
                                </div>
                                <div class="card-body">
                                    <?php if (isset($success)) : ?>
                                        <div class="alert alert-success"><?php echo $success; ?></div>
                                    <?php endif; ?>
                                    <?php if (isset($error)) : ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form method="POST" action="edit_budget?id=<?php echo $encoded_id; ?>">
                                        <div class="form-group mt-2">
                                            <label for="year">Year (พ.ศ.)</label>
                                            <select class="form-control" id="year" name="year" required>
                                                <?php for ($i = (date("Y") + 543) + 5; $i >= (date("Y") + 543) - 20; $i--) : ?>
                                                    <option value="<?php echo $i; ?>" <?php if ($i == ($budget_data['year'] + 543)) echo 'selected'; ?>><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="funding">แหล่งเงิน</label>
                                            <select class="form-control" id="funding" name="funding" required>
                                                <option value="เงินงบประมาณ" <?php if ($budget_data['funding'] == 'เงินงบประมาณ') echo 'selected'; ?>>เงินงบประมาณ</option>
                                                <option value="เงินรายได้" <?php if ($budget_data['funding'] == 'เงินรายได้') echo 'selected'; ?>>เงินรายได้</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="budget">งบประมาณทั้งหมด</label>
                                            <input type="number" step="1000" class="form-control" id="budget" name="budget" value="<?php echo htmlspecialchars($budget_data['budget']); ?>" required>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="remaining_budget">งบประมาณคงเหลือที่ใช้ได้</label>
                                            <input type="number" step="1000" class="form-control" id="remaining_budget" name="remaining_budget" value="<?php echo htmlspecialchars($budget_data['remaining_budget']); ?>" required readonly>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="project_name">ชื่อโครงการ</label>
                                            <input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo htmlspecialchars($budget_data['project_name']); ?>" required>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="department_id">หน่วยงาน</label>
                                            <select class="form-control" id="department_id" name="department_id" required>
                                                <?php foreach ($departments as $department) : ?>
                                                    <option value="<?php echo $department['id']; ?>" <?php if ($department['id'] == $budget_data['department_id']) echo 'selected'; ?>><?php echo $department['department_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                                    </form>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- container -->

                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © PROMS - Develop by <b>Jeffy ITC MBU</b>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Theme Settings -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
            <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                <h5 class="text-white m-0">Theme Settings</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div data-simplebar class="h-100">
                    <div class="p-3">
                        <h5 class="mb-3 fs-16 fw-bold">Color Scheme</h5>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check form-switch card-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-light" value="light">
                                    <label class="form-check-label" for="layout-color-light">
                                        <img src="assets/images/layouts/light.png" alt="" class="img-fluid">
                                    </label>
                                </div>
                                <h5 class="font-14 text-center text-muted mt-2">Light</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check form-switch card-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-dark" value="dark">
                                    <label class="form-check-label" for="layout-color-dark">
                                        <img src="assets/images/layouts/dark.png" alt="" class="img-fluid">
                                    </label>
                                </div>
                                <h5 class="font-14 text-center text-muted mt-2">Dark</h5>
                            </div>
                        </div>

                        <div id="layout-width">
                            <h5 class="my-3 fs-16 fw-bold">Layout Mode</h5>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                                        <label class="form-check-label" for="layout-mode-fluid">
                                            <img src="assets/images/layouts/light.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Fluid</h5>
                                </div>

                                <div class="col-4">
                                    <div id="layout-boxed">
                                        <div class="form-check form-switch card-switch mb-1">
                                            <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-boxed" value="boxed">
                                            <label class="form-check-label" for="layout-mode-boxed">
                                                <img src="assets/images/layouts/boxed.png" alt="" class="img-fluid">
                                            </label>
                                        </div>
                                        <h5 class="font-14 text-center text-muted mt-2">Boxed</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="my-3 fs-16 fw-bold">Topbar Color</h5>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check form-switch card-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-light" value="light">
                                    <label class="form-check-label" for="topbar-color-light">
                                        <img src="assets/images/layouts/light.png" alt="" class="img-fluid">
                                    </label>
                                </div>
                                <h5 class="font-14 text-center text-muted mt-2">Light</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check form-switch card-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-dark" value="dark">
                                    <label class="form-check-label" for="topbar-color-dark">
                                        <img src="assets/images/layouts/topbar-dark.png" alt="" class="img-fluid">
                                    </label>
                                </div>
                                <h5 class="font-14 text-center text-muted mt-2">Dark</h5>
                            </div>
                        </div>

                        <div>
                            <h5 class="my-3 fs-16 fw-bold">Menu Color</h5>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-light" value="light">
                                        <label class="form-check-label" for="leftbar-color-light">
                                            <img src="assets/images/layouts/sidebar-light.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Light</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-dark" value="dark">
                                        <label class="form-check-label" for="leftbar-color-dark">
                                            <img src="assets/images/layouts/light.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Dark</h5>
                                </div>
                            </div>
                        </div>

                        <div id="sidebar-size">
                            <h5 class="my-3 fs-16 fw-bold">Sidebar Size</h5>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-default" value="default">
                                        <label class="form-check-label" for="leftbar-size-default">
                                            <img src="assets/images/layouts/light.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Default</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-compact" value="compact">
                                        <label class="form-check-label" for="leftbar-size-compact">
                                            <img src="assets/images/layouts/compact.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Compact</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small" value="condensed">
                                        <label class="form-check-label" for="leftbar-size-small">
                                            <img src="assets/images/layouts/sm.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Condensed</h5>
                                </div>


                                <div class="col-4">
                                    <div class="form-check form-switch card-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-full" value="full">
                                        <label class="form-check-label" for="leftbar-size-full">
                                            <img src="assets/images/layouts/full.png" alt="" class="img-fluid">
                                        </label>
                                    </div>
                                    <h5 class="font-14 text-center text-muted mt-2">Full Layout</h5>
                                </div>
                            </div>
                        </div>

                        <div id="layout-position">
                            <h5 class="my-3 fs-16 fw-bold">Layout Position</h5>

                            <div class="btn-group checkbox" role="group">
                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                                <label class="btn btn-soft-primary w-sm" for="layout-position-fixed">Fixed</label>

                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                                <label class="btn btn-soft-primary w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer border-top p-3 text-center">
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
                    </div>
                    <div class="col-6">
                        <a href="https://1.envato.market/velonic" target="_blank" role="button" class="btn btn-primary w-100">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var response = <?php echo json_encode($response); ?>;
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        // Redirect กลับไปที่หน้าเดิมหลังจาก 2 วินาที
                        window.location.href = window.location.href;
                    });
                } else if (response.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: response.message
                    }).then(function() {
                        // Redirect กลับไปที่หน้าเดิมหรือหน้าที่ต้องการหลังจากมีข้อผิดพลาด
                        window.location.href = window.location.href;
                    });
                }
            });
        </script>

        <script>
            document.getElementById('budget').addEventListener('input', function() {
                const originalTotal = <?php echo $budget_data['budget']; ?>;
                const originalRemaining = <?php echo $budget_data['remaining_budget']; ?>;

                const newTotal = parseFloat(this.value);
                const difference = newTotal - originalTotal;

                const newRemaining = originalRemaining + difference;

                const remainingInput = document.getElementById('remaining_budget');
                remainingInput.value = newRemaining.toFixed(2);

                if (newRemaining < 0) {
                    alert('งบประมาณคงเหลือที่ใช้ได้ไม่สามารถติดลบได้');
                    remainingInput.value = '0.00';
                }
            });
        </script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!--  Select2 Plugin Js -->
        <script src="assets/vendor/select2/js/select2.min.js"></script>

        <!-- Daterangepicker Plugin js -->
        <script src="assets/vendor/daterangepicker/moment.min.js"></script>
        <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

        <!-- Bootstrap Datepicker Plugin js -->
        <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        <!-- Bootstrap Timepicker Plugin js -->
        <script src="assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>

        <!-- Input Mask Plugin js -->
        <script src="assets/vendor/jquery-mask-plugin/jquery.mask.min.js"></script>

        <!-- Bootstrap Touchspin Plugin js -->
        <script src="assets/vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>

        <!-- Bootstrap Maxlength Plugin js -->
        <script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>

        <!-- Typehead Plugin js -->
        <script src="assets/vendor/handlebars/handlebars.min.js"></script>
        <script src="assets/vendor/typeahead.js/typeahead.bundle.min.js"></script>

        <!-- Flatpickr Timepicker Plugin js -->
        <script src="assets/vendor/flatpickr/flatpickr.min.js"></script>

        <!-- Typehead Demo js -->
        <script src="assets/js/pages/typehead.init.js"></script>

        <!-- Timepicker Demo js -->
        <script src="assets/js/pages/timepicker.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

</body>

</html>