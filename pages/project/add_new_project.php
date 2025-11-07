<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// // แสดงข้อผิดพลาดใน PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ดึงข้อมูล user และ department
$user_info = getUserInfo();
if ($user_info === null) {
    // ถ้าไม่พบข้อมูล user ให้ redirect ไปที่หน้า login
    header("Location: login.php");
    exit;
}

$department_id = $user_info['department_id'];
$department_name = getDepartmentName($department_id);

// ดึงข้อมูล budget
$budget_info = getBudgetInfoAdd($budget_id);
$main_ways = getMainWays();
$sub_departments = getSubDepartment($department_id);

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
                                    <ol class="breadcrumb m-0">
                                        <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Velonic</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                        <li class="breadcrumb-item active">Welcome!</li> -->
                                    </ol>
                                </div>
                                <h4 class="page-title">เพิ่มโครงการแบบเดี่ยว</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="header-title">กรอกรายละเอียดโครงการ</h4>
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
                                    <form method="POST" action="process_add_new_project">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="department_id">หน่วยงาน:</label>
                                                <input class="form-control" type="text" id="department_name" name="department_name" value="<?php echo $department_name; ?>" readonly>
                                                <input class="form-control" type="hidden" id="department_id" name="department_id" value="<?php echo $department_id; ?>" readonly>
                                                <input class="form-control" type="hidden" id="project_status" name="project_status" value="1" readonly><br><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="mainway_id">ส่วนงานย่อย:</label>
                                                <select class="form-select" id="sub_department" name="sub_department" required>
                                                    <option value="">เลือกส่วนงานย่อย</option>
                                                    <?php foreach ($sub_departments as $sub_department) : ?>
                                                        <option value="<?php echo $sub_department['id']; ?>"><?php echo htmlspecialchars($sub_department['sub_department_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="project_manager">ชื่อผู้รับผิดชอบโครงการ :</label>
                                                <input class="form-control" type="text" id="project_manager" name="project_manager" value="<?php echo $_SESSION['user_firstname'], ' ', $_SESSION['user_lastname']; ?>" readonly><br>
                                            </div>
                                            <div class="col-3">
                                                <label for="manager_tel">เบอร์โทร:</label>
                                                <input class="form-control" type="text" id="manager_tel" name="manager_tel" value="<?php echo $_SESSION['user_tel']; ?>" readonly><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="project_number">เลขที่โครงการ:</label>
                                                <input class="form-control" type="text" id="project_number" name="project_number" value="MBU-2568-<?php echo $department_id; ?>-<?php echo date('dmHi'); ?>" readonly><br>
                                            </div>
                                            <div class="col-3">
                                                <label for="project_number">ปีงบประมาณ:</label>
                                                <input class="form-control" type="text" id="project_year" name="project_year" value="2568" readonly><br>
                                            </div>
                                        </div>
                                        <label for="project_name">ชื่อโครงการ (หลัก) :</label>
                                        <input class="form-control" type="text" id="project_name" name="project_name" value="" required><br>
                                        <label for="sub_project_name">ชื่อโครงการย่อย (ถ้ามี) :</label>
                                        <input class="form-control" type="text" id="sub_project_name" name="sub_project_name" value=""><br>
                                        <div class="row">
                                            <!-- <input class="form-control" type="hidden" id="budget_id" name="budget_id" value="<?php echo $budget_info['id']; ?>" readonly><br>
                                            <input class="form-control" type="hidden" id="budget_status" name="budget_status" value="1" readonly><br> -->
                                            <input class="form-control" type="hidden" id="activity_type" name="activity_type" value="1" readonly><br>
                                            
                                            <!-- <div class="col-3">
                                                <label for="funding_source">แหล่งเงิน:</label>
                                                <input class="form-control" type="text" id="funding_source" name="funding_source" value="<?php echo $budget_info['funding']; ?>"><br>
                                            </div>
                                            <div class="col-3">
                                                <label for="allocated_budget">งบประมาณของหน่วยงานทั้งหมด:</label>
                                                <input class="form-control" type="number" step="0.01" id="allocated_budget" name="allocated_budget" value="<?php echo $budget_info['budget']; ?>"><br>
                                            </div>
                                            <div class="col-3">
                                                <label for="allocated_budget">งบประมาณคงเหลือที่ใช้ได้:</label>
                                                <input class="form-control" type="number" id="" name="" value="<?php echo $budget_info['remaining_budget']; ?>" readonly><br>
                                            </div> -->
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="mainway_id">วิถีหลัก:</label>
                                                <select class="form-select" id="mainway_id" name="main_strategy" required>
                                                    <option value="">เลือกวิถีหลัก</option>
                                                    <?php foreach ($main_ways as $main_way) : ?>
                                                        <option value="<?php echo $main_way['id']; ?>"><?php echo htmlspecialchars($main_way['mainway_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="subway_id">วิถีย่อย:</label>
                                                <select class="form-select" id="subway_id" name="sub_strategy" required>
                                                    <option value="">เลือกวิถีย่อย</option>
                                                </select><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="project_type">ประเภทโครงการ:</label>
                                                <select class="form-select" id="project_type" name="project_type" required>
                                                    <option selected="">กรุณาเลือกประเภทโครงการ</option>
                                                    <option value="2">โครงการงานประจำ</option>
                                                    <option value="1">โครงการงานพัฒนา</option>
                                                </select><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="mission_alignment">ความสอดคล้องพันธกิจของมหาวิทยาลัย:</label>
                                                <select class="form-select" id="exampleFormControlSelect1" name="mission_alignment" aria-label="Default select example" required>
                                                    <option selected="">กรุณาเลือกความสอดคล้อง</option>
                                                    <option value="การจัดการศึกษา">การจัดการศึกษา</option>
                                                    <option value="การวิจัย">การวิจัย</option>
                                                    <option value="การบริการวิชาการแก่สังคม">การบริการวิชาการแก่สังคม</option>
                                                    <option value="การทำนุบำรุงศิลปวัฒนธรรม">การทำนุบำรุงศิลปวัฒนธรรม</option>
                                                    <option value="ระบบการบริหารจัดการ">ระบบการบริหารจัดการ</option>
                                                </select><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="rationale">หลักการและเหตุผล:</label>
                                                <textarea class="form-control" id="rationale" name="rationale" required></textarea><br>
                                            </div>
                                            <div class="col-12">
                                                <label for="objectives">วัตถุประสงค์:</label>
                                                <textarea class="form-control" id="objectives" name="objectives" required></textarea><br>
                                            </div>
                                            <div class="col-12">
                                                <label for="goals">เป้าหมายโครงการ</label><br>
                                                <div class="row mx-2 mt-2">
                                                    <label for="goals">เชิงปริมาณ : จำนวนผู้เข้าร่วมโครงการ (รูป/คน)</label>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2567 แผน (ผล)</label>
                                                        <input class="form-control" type="text" id="quantitative_goals_result" name="quantitative_goals_result" value=""><br>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2568 แผน</label>
                                                        <input class="form-control" type="text" id="quantitative_goals_plan" name="quantitative_goals_plan" value=""><br>
                                                    </div>
                                                </div>
                                                <div class="row mx-2 mt-2">
                                                    <label for="goals">เชิงคุณภาพ : โครงการกิจกรรมที่บรรลุผลตามวัตถุประสงค์ (ร้อยละ)</label>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2567 แผน (ผล)</label>
                                                        <input class="form-control" type="text" id="qualitative_goals_result" name="qualitative_goals_result" value=""><br>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2568 แผน</label>
                                                        <input class="form-control" type="text" id="qualitative_goals_plan" name="qualitative_goals_plan" value=""><br>
                                                    </div>
                                                </div>
                                                <div class="row mx-2 mt-2">
                                                    <label for="goals">เชิงเวลา : โครงการกิจกรรมที่แล้วเสร็จตามระยะเวลาที่กำหนด (ร้อยละ)</label>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2567 แผน (ผล)</label>
                                                        <input class="form-control" type="text" id="time_goals_result" name="time_goals_result" value=""><br>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2568 แผน</label>
                                                        <input class="form-control" type="text" id="time_goals_plan" name="time_goals_plan" value=""><br>
                                                    </div>
                                                </div>
                                                <div class="row mx-2 mt-2">
                                                    <label for="goals">เชิงต้นทุน : ค่าใช้จ่ายการผลิตตามงบประมาณที่ได้รับจัดสรร (บาท)</label>
                                                    <div class="col-4">
                                                        <label for="goals">ปี 2567 แผน (ผล)</label>
                                                        <input class="form-control" type="text" id="cost_goals_result" name="cost_goals_result" value=""><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="outcomes">ผลลัพธ์:</label>
                                                <textarea class="form-control" id="outcomes" name="outcomes" required></textarea><br>
                                            </div>
                                            <div class="col-12">
                                                <label for="impacts">ผลกระทบ:</label>
                                                <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" id="impacts" name="impacts" class="form-check-input" value="เชิงบวก" checked>
                                                        <label class="form-check-label" for="customRadio3">เชิงบวก</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" id="impacts" name="impacts" class="form-check-input" value="เชิงลบ">
                                                        <label class="form-check-label" for="customRadio4">เชิงลบ</label>
                                                    </div>
                                                </div>
                                                <textarea class="form-control" id="impacts_detail" name="impacts_detail" required></textarea><br>
                                            </div>
                                            <div class="col-12">
                                                <label for="impacts">การดำเนินโครงการ:</label>
                                                <textarea class="form-control" id="implementation" name="implementation" required></textarea><br>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                <label for="primary_target_group">กลุ่มเป้าหมาย:</label>
                                                <input class="form-control" id="primary_target_group" name="primary_target_group" required><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="primary_target_group_quantity">จำนวนกลุ่มเป้าหมาย:</label>
                                                <input data-toggle="touchspin" type="number" id="primary_target_group_quantity" name="primary_target_group_quantity" required value="0"><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="start_month">เดือนที่เริ่ม:</label>
                                                <select class="form-select" id="start_month" name="start_month" required>
                                                    <option value="มกราคม">มกราคม</option>
                                                    <option value="กุมภาพันธ์">กุมภาพันธ์</option>
                                                    <option value="มีนาคม">มีนาคม</option>
                                                    <option value="เมษายน">เมษายน</option>
                                                    <option value="พฤษภาคม">พฤษภาคม</option>
                                                    <option value="มิถุนายน">มิถุนายน</option>
                                                    <option value="กรกฎาคม">กรกฎาคม</option>
                                                    <option value="สิงหาคม">สิงหาคม</option>
                                                    <option value="กันยายน">กันยายน</option>
                                                    <option value="ตุลาคม">ตุลาคม</option>
                                                    <option value="พฤศจิกายน">พฤศจิกายน</option>
                                                    <option value="ธันวาคม">ธันวาคม</option>
                                                </select><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="end_month">เดือนที่สิ้นสุด:</label>
                                                <select class="form-select" id="end_month" name="end_month" required>
                                                    <option value="มกราคม">มกราคม</option>
                                                    <option value="กุมภาพันธ์">กุมภาพันธ์</option>
                                                    <option value="มีนาคม">มีนาคม</option>
                                                    <option value="เมษายน">เมษายน</option>
                                                    <option value="พฤษภาคม">พฤษภาคม</option>
                                                    <option value="มิถุนายน">มิถุนายน</option>
                                                    <option value="กรกฎาคม">กรกฎาคม</option>
                                                    <option value="สิงหาคม">สิงหาคม</option>
                                                    <option value="กันยายน">กันยายน</option>
                                                    <option value="ตุลาคม">ตุลาคม</option>
                                                    <option value="พฤศจิกายน">พฤศจิกายน</option>
                                                    <option value="ธันวาคม">ธันวาคม</option>
                                                </select><br>
                                            </div>
                                            <div class="col-4">
                                                <label for="project_dates">วันที่จัดโครงการตามแผน</label>
                                                <input type="text" class="form-control" id="multiple-datepicker" name="project_dates" placeholder="Select Date" data-provide="datepicker" data-date-multidate="true" data-date-container="#datepicker3">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="location">สถานที่จัดโครงการ:</label>
                                                <textarea class="form-control" id="location" name="location" required></textarea><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <button class="btn btn-info" type="submit">ส่งข้อมูล</button>
                                            </div>
                                        </div>
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
        </div>


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




        <script>
            $(document).ready(function() {
                $('#mainway_id').change(function() {
                    var mainway_id = $(this).val();
                    if (mainway_id) {
                        $.ajax({
                            type: 'POST',
                            url: 'get_subways',
                            data: {
                                mainway_id: mainway_id
                            },
                            success: function(response) {
                                $('#subway_id').html(response);
                            }
                        });
                    } else {
                        $('#subway_id').html('<option value="">เลือกวิถีย่อย</option>');
                    }
                });
            });
        </script>

</body>

</html>