<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูล project_id จาก URL


if (!isset($_GET['id'])) {
    header('Location: manage_project');
    exit;
}
$encoded_id = htmlspecialchars(strip_tags($_GET['id']));
$project_id = decrypt(urldecode($encoded_id));
if ($project_id == '') {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

// ดึงข้อมูล project และ budget
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM project_requests WHERE id = :project_id";
$stmt = $db->prepare($query);
$stmt->execute([':project_id' => $project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

$department_id = $project['department_id'];
$project_number = $project['project_number'];

$query = "SELECT * FROM budget WHERE project_number = :project_number ORDER BY year DESC LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute([':project_number' => $project_number]);
$budget = $stmt->fetch(PDO::FETCH_ASSOC);

$id_budget = $budget['id'];

$query = "SELECT * FROM expense_type";
$stmt = $db->prepare($query);
$stmt->execute();
$expense_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./partials/head.php'); ?>
    <style>
        .expense-section {
            margin-bottom: 20px;
        }

        .summary-table {
            margin-top: 20px;
        }

        .over-budget {
            color: red;
            border-color: red;
        }
    </style>
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
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Velonic</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                        <li class="breadcrumb-item active">Welcome!</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Welcome!</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="header-title">เพิ่มงบประมาณโครงการ</h4>
                                    <p class="text-muted mb-0">
                                        ชื่อโครงการ : <?php echo $project['project_name']; ?>
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group mb-2">
                                                    <!-- <label class="form-label">งบประมาณทั้งหมด:</label> -->
                                                    <input type="hidden" class="form-control" value="9999999999" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-2">
                                                    <!-- <label class="form-label">งบประมาณคงเหลือที่ใช้ได้:</label> -->
                                                    <input type="hidden" class="form-control" value="9999999999" readonly id="remainingBudget">
                                                </div>
                                            </div>
                                        </div>

                                        <form action="process_add_expense" method="POST" id="expenseForm">
                                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

                                            <input type="hidden" name="budget_id" value="<?php echo $id_budget; ?>">
                                            <div class="col-3">
                                                <label for="funding_source">แหล่งเงิน:</label>
                                                <select class="form-select" id="funding_source" name="funding_source" required>
                                                    <option selected="">กรุณาเลือกประเภทแหล่งเงิน</option>
                                                    <option value="เงินงบประมาณ">เงินงบประมาณ</option>
                                                    <option value="เงินรายได้">เงินรายได้</option>
                                                </select><br>
                                            </div>
                                            <hr>
                                            <div id="expense-sections">
                                                <!-- Sections for expenses will be added here dynamically -->
                                            </div>

                                            <button type="button" class="btn btn-info btn-sm" id="addExpenseBtn"><i class="ri-add-circle-line"></i> เพิ่มรายการค่าใช้จ่าย</button>
                                            <hr>
                                            <div class="summary-table mt-3">
                                                <h3>ตารางสรุปรายการค่าใช้จ่าย</h3>
                                                <table id="summaryTable" class="table table-striped w-100 nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>ประเภทค่าใช้จ่าย</th>
                                                            <th>รายการค่าใช้จ่าย</th>
                                                            <th>ค่าใช้จ่ายต่อหน่วย</th>
                                                            <th>จำนวนหน่วย</th>
                                                            <th>รวมค่าใช้จ่าย</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Summary items will be added here dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>ค่าใช้จ่ายรวมทั้งหมด:</label>
                                                        <input type="text" class="form-control" id="totalExpense" name="total_budget" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <!-- <label>ค่าใช้จ่ายที่เหลือ:</label> -->
                                                        <input type="hidden" class="form-control" id="remainingExpense" name="remaining_budget" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-danger mt-2" id="budgetAlert" style="display: none;">
                                                ค่าใช้จ่ายทั้งหมดเกินจากงบประมาณที่ใช้ได้
                                            </div>
                                            <button type="button" class="btn btn-primary mt-2" id="openModalBtn" disabled>ยืนยัน</button>
                                        </form>
                                    </div>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- container -->
                    <!-- Modal -->
                    <div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="summaryModalLabel">สรุปข้อมูลค่าใช้จ่าย</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="summary-table">
                                        <table class="table table-bordered" id="modalSummaryTable">
                                            <thead>
                                                <tr>
                                                    <th>ประเภทค่าใช้จ่าย</th>
                                                    <th>รายการค่าใช้จ่าย</th>
                                                    <th>ค่าใช้จ่ายต่อหน่วย</th>
                                                    <th>จำนวนหน่วย</th>
                                                    <th>รวมค่าใช้จ่าย</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Summary items will be added here dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <label>ค่าใช้จ่ายรวมทั้งหมด:</label>
                                    <input type="text" class="form-control" id="modalTotalExpense" readonly>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">ยังก่อน</button>
                                    <button type="button" class="btn btn-primary" id="confirmSubmitBtn">ยืนยันส่งข้อมูล</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
        </div>
        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        <!-- Datatables js -->
        <script src="assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
        <script src="assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
        <script src="assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>


        <script>
            $(document).ready(function() {
                var expenseIndex = 0;

                $('#addExpenseBtn').on('click', function() {
                    addExpenseSection();
                });

                $('#openModalBtn').on('click', function() {
                    updateModalSummaryTable();
                    $('#summaryModal').modal('show');
                });

                $('#confirmSubmitBtn').on('click', function() {
                    $('#expenseForm').submit();
                });

                function addExpenseSection() {
                    var expenseHtml = `
                    <div class="expense-section" id="expenseSection_${expenseIndex}">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="expense_type_${expenseIndex}">ประเภทค่าใช้จ่าย:</label>
                                    <select class="form-select expense-type" id="expense_type_${expenseIndex}" name="expenses[${expenseIndex}][expense_type]" required>
                                        <option value="">เลือกประเภทค่าใช้จ่าย</option>
                                        <?php foreach ($expense_types as $type) : ?>
                                            <option value="<?php echo $type['expense_id']; ?>"><?php echo $type['expense_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="expense_item_${expenseIndex}">รายการค่าใช้จ่าย:</label>
                                    <select class="form-select expense-item" id="expense_item_${expenseIndex}" name="expenses[${expenseIndex}][expense_item]" required>
                                        <option value="">เลือกรายการค่าใช้จ่าย</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="unit_cost_${expenseIndex}">ค่าใช้จ่ายต่อหน่วย:</label>
                                    <input type="number" class="form-control unit-cost" id="unit_cost_${expenseIndex}" name="expenses[${expenseIndex}][unit_cost]" step="1.00" required>
                                     <p class="alert alert-danger note" id="note_${expenseIndex}" style="display: none;"></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="unit_quantity_${expenseIndex}">จำนวนหน่วย:</label>
                                    <input type="number" class="form-control unit-quantity" id="unit_quantity_${expenseIndex}" name="expenses[${expenseIndex}][unit_quantity]" required>
                                </div>
                            </div>
                            <div class="col-lg-2 unit-quantity-2-container" style="display: none;">
                                <div class="form-group">
                                    <label for="unit_quantity_2_${expenseIndex}">จำนวนหน่วย 2:</label>
                                    <input type="number" class="form-control unit-quantity-2" id="unit_quantity_2_${expenseIndex}" name="expenses[${expenseIndex}][unit_quantity_2]">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="total_cost_${expenseIndex}">รวมค่าใช้จ่าย:</label>
                                    <input type="number" class="form-control total-cost" id="total_cost_${expenseIndex}" name="expenses[${expenseIndex}][total_cost]" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 mb-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="note_${expenseIndex}">หมายเหตุ:</label>
                                    <textarea class="form-control" id="note_${expenseIndex}" name="expenses[${expenseIndex}][note]"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <button type="button" class="btn btn-danger remove-expense-btn btn-sm"><i class="ri-delete-bin-6-line"></i> ลบรายการ</button>
                        </div>
                        <hr>
                    </div>
                `;
                    $('#expense-sections').append(expenseHtml);
                    expenseIndex++;
                }

                $(document).on('change', '.expense-type', function() {
                    var $section = $(this).closest('.expense-section');
                    var expenseType = $(this).val();
                    var $expenseItemSelect = $section.find('.expense-item');

                    $.ajax({
                        url: 'get_expense_items',
                        method: 'GET',
                        data: {
                            expense_type: expenseType
                        },
                        success: function(response) {
                            $expenseItemSelect.html(response);
                        }
                    });
                });

                $(document).on('change', '.expense-item', function() {
                    var $section = $(this).closest('.expense-section');
                    var expenseItem = $(this).val();
                    var $unitCostInput = $section.find('.unit-cost');
                    var $noteAlert = $section.find('.note');
                    var $unitQuantity2Container = $section.find('.unit-quantity-2-container');
                    var $unitQuantity2Input = $section.find('.unit-quantity-2');

                    $.ajax({
                        url: 'get_unit_cost',
                        method: 'GET',
                        data: {
                            expense_item: expenseItem
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            $unitCostInput.val(data.unit_cost).prop('readonly', !!data.unit_cost);

                            // ตรวจสอบ cost_type เพื่อตัดสินใจว่าแสดงฟิลด์ unit_quantity_2 หรือไม่
                            if (data.cost_type === 1) {
                                $unitQuantity2Container.show();
                            } else {
                                $unitQuantity2Container.hide();
                                $unitQuantity2Input.val(''); // รีเซ็ตค่าเมื่อถูกซ่อน
                            }

                            if (data.note) {
                                $noteAlert.text(data.note).show();
                            } else {
                                $noteAlert.hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                        }
                    });
                });

                $(document).on('input', '.unit-quantity, .unit-quantity-2, .unit-cost', function() {
                    var $section = $(this).closest('.expense-section');
                    var unitCost = parseFloat($section.find('.unit-cost').val()) || 0;
                    var unitQuantity = parseFloat($section.find('.unit-quantity').val()) || 0;
                    var unitQuantity2 = parseFloat($section.find('.unit-quantity-2').val()) || 1; // Default to 1 if hidden or empty

                    var totalCost;

                    // Check if `unit_quantity_2` is shown
                    if ($section.find('.unit-quantity-2-container').is(':visible')) {
                        totalCost = unitCost * unitQuantity * unitQuantity2;
                    } else {
                        totalCost = unitCost * unitQuantity;
                    }

                    $section.find('.total-cost').val(totalCost.toFixed(2));
                    updateTotalExpense(); // Updates overall total
                    updateSummaryTable(); // Updates the summary table
                });



                function updateTotalExpense() {
                    var totalExpense = 0;
                    $('.total-cost').each(function() {
                        totalExpense += parseFloat($(this).val()) || 0;
                    });
                    $('#totalExpense').val(totalExpense.toFixed(2));

                    var remainingBudget = parseFloat($('#remainingBudget').val().replace(/,/g, '')) || 0;
                    var remainingExpense = remainingBudget - totalExpense;

                    $('#remainingExpense').val(remainingExpense.toFixed(2));

                    var isOverBudget = remainingExpense < 0;

                    if (isOverBudget) {
                        $('#remainingExpense').addClass('over-budget');
                        $('#budgetAlert').show();
                        $('#addExpenseBtn').prop('disabled', true);
                    } else {
                        $('#remainingExpense').removeClass('over-budget');
                        $('#budgetAlert').hide();
                        $('#addExpenseBtn').prop('disabled', false);
                    }

                    $('#openModalBtn').prop('disabled', totalExpense === 0 || isOverBudget);
                }

                function updateSummaryTable() {
                    var summaryData = {}; // สร้าง object สำหรับเก็บข้อมูลสรุปค่าใช้จ่ายในแต่ละหมวด

                    // Loop ผ่านแต่ละ expense section
                    $('.expense-section').each(function() {
                        var expenseType = $(this).find('.expense-type option:selected').text(); // ดึงประเภทค่าใช้จ่าย
                        var expenseItem = $(this).find('.expense-item option:selected').text(); // ดึงรายการค่าใช้จ่าย
                        var unitCost = parseFloat($(this).find('.unit-cost').val()) || 0; // ดึงค่าใช้จ่ายต่อหน่วย
                        var unitQuantity = parseFloat($(this).find('.unit-quantity').val()) || 0; // ดึงจำนวนหน่วย
                        var unitQuantity2 = $(this).find('.unit-quantity-2-container').is(':visible') ?
                            parseFloat($(this).find('.unit-quantity-2').val()) || 1 : '-'; // ดึงหน่วยที่ 2 ถ้ามี
                        var totalCost = parseFloat($(this).find('.total-cost').val()) || 0; // คำนวณค่าใช้จ่ายรวม

                        // ถ้าไม่มีหมวดหมู่นั้นใน object summaryData ให้สร้างขึ้นมาใหม่
                        if (!summaryData[expenseType]) {
                            summaryData[expenseType] = {
                                items: [], // เก็บรายการค่าใช้จ่ายในหมวดนั้น
                                total: 0 // เก็บผลรวมค่าใช้จ่ายในหมวดนั้น
                            };
                        }

                        // เก็บข้อมูลค่าใช้จ่ายในรายการนั้นลงใน array
                        summaryData[expenseType].items.push({
                            expenseItem: expenseItem,
                            unitCost: unitCost.toFixed(2),
                            unitQuantity: unitQuantity,
                            unitQuantity2: unitQuantity2,
                            totalCost: totalCost.toFixed(2)
                        });

                        // รวมค่าใช้จ่ายของรายการนั้นเข้าไปในผลรวมของหมวดหมู่
                        summaryData[expenseType].total += totalCost;
                    });

                    // สร้าง HTML สำหรับแสดงผลในตาราง
                    var summaryHtml = '';

                    for (var type in summaryData) {
                        summaryHtml += `<tr><th colspan="5">${type}</th></tr>`; // แสดงชื่อหมวดหมู่
                        summaryData[type].items.forEach(function(item) {
                            summaryHtml += `
                        <tr>
                            <td></td>
                            <td>${item.expenseItem}</td>
                            <td>${item.unitCost}</td>
                            <td>${item.unitQuantity} ${item.unitQuantity2 !== '-' ? ` x ${item.unitQuantity2}` : ''}</td>
                            <td>${item.totalCost}</td>
                        </tr>
                    `;
                        });
                        summaryHtml += `
                    <tr>
                        <td colspan="4"><strong>รวมค่าใช้จ่ายทั้งหมดในหมวด</strong></td>
                        <td><strong>${summaryData[type].total.toFixed(2)}</strong></td>
                    </tr>
                `;
                    }

                    // แสดงผลในตาราง summary
                    $('#summaryTable tbody').html(summaryHtml);
                }


                function updateModalSummaryTable() {
                    var summaryHtml = $('#summaryTable tbody').html();
                    $('#modalSummaryTable tbody').html(summaryHtml);

                    // Update modal total expense
                    var totalExpense = $('#totalExpense').val();
                    $('#modalTotalExpense').val(totalExpense);
                }
            });
        </script>
</body>

</html>