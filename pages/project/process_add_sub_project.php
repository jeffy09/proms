<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';



// ดึงข้อมูลจากฟอร์ม
$project_id = $_POST['project_id'];
$sub_project_num = $_POST['sub_project_num'];
$project_number = $_POST['project_number'];
$project_name = $_POST['project_name'];
$department_id = $_POST['department_id']; // ใช้ department_id จาก session
$sub_department_id = $_POST['sub_department_id'];
$rationale = $_POST['rationale'];
$objectives = $_POST['objectives'];
$implementation = $_POST['implementation'];
$primary_target_group = $_POST['primary_target_group'];
$primary_target_group_quantity = $_POST['primary_target_group_quantity'];
$start_month = $_POST['start_month'];
$end_month = $_POST['end_month'];
$project_dates = $_POST['project_dates'];
$location = $_POST['location'];
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');
$project_manager = $_POST['project_manager'];
$manager_tel = $_POST['manager_tel'];

// เพิ่มข้อมูลลงในฐานข้อมูล
$database = new Database();
$db = $database->getConnection();
$db->beginTransaction();

try {
    // Insert ข้อมูลไปยังตาราง project_requests
    $query = "INSERT INTO sub_project_requests (project_id, sub_project_num, project_number, project_name, department_id, sub_department_id, rationale, objectives, implementation, primary_target_group, primary_target_group_quantity, start_month, end_month, project_dates, location, created_at, updated_at, project_manager, manager_tel) 
              VALUES (:project_id, :sub_project_num, :project_number, :project_name, :department_id, :sub_department_id, :rationale, :objectives, :implementation, :primary_target_group, :primary_target_group_quantity, :start_month, :end_month, :project_dates, :location, :created_at, :updated_at, :project_manager, :manager_tel)";

    $params = [
        ':project_id' => $project_id,
        ':sub_project_num' => $sub_project_num,
        ':project_number' => $project_number,
        ':project_name' => $project_name,
        ':department_id' => $department_id,
        ':sub_department_id' => $sub_department_id,
        ':rationale' => $rationale,
        ':objectives' => $objectives,
        ':implementation' => $implementation,
        ':primary_target_group' => $primary_target_group,
        ':primary_target_group_quantity' => $primary_target_group_quantity,
        ':start_month' => $start_month,
        ':end_month' => $end_month,
        ':project_dates' => $project_dates,
        ':location' => $location,
        ':created_at' => $created_at,
        ':updated_at' => $updated_at,
        ':project_manager' => $project_manager,
        ':manager_tel' => $manager_tel
    ];

    $stmt = $db->prepare($query);
    $stmt->execute($params);
// insert ข้อมูลไปยังตาราง budget
$project_year = $_POST['project_year'] - 543;
$project_number = $_POST['project_number'];
$project_name = $_POST['project_name'];
$department_id = $_POST['department_id'];

$query_insert_budget = "INSERT INTO budget (year, project_number, project_name, department_id) VALUE (:project_year, :project_number, :project_name, :department_id)";
$params_insert_budget = [
    ':project_year' => $project_year,
    ':project_number' => $project_number,
    ':project_name' => $project_name,
    ':department_id' => $department_id
];

$stmt_insert_budget = $db->prepare($query_insert_budget);
$stmt_insert_budget->execute($params_insert_budget);

$project_id = $_POST['project_id'];

$query_update_project = "UPDATE project_requests SET project_status = 6 WHERE id = :project_id";
$params_update_project = [
    ':project_id' => $project_id
];

$stmt_update_project = $db->prepare($query_update_project);
$stmt_update_project->execute($params_update_project);
    // ถ้าทุกอย่างสำเร็จให้ commit
    $db->commit();

    $response['status'] = 'success';
    $response['message'] = 'เพิ่มข้อมูลโครงการสำเร็จ';

} catch (Exception $e) {
    // ถ้ามีข้อผิดพลาดให้ rollback
    $db->rollBack();
    $response['status'] = 'error';
    $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Process Add Expense</title>
    <?php include('./partials/head.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
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
                    window.location.href = 'manage_projects';
                });
            } else if (response.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: response.message
                }).then(function() {
                    window.location.href = 'add_expense.php'; // หรือหน้าที่ต้องการกลับไป
                });
            }
        });
    </script>
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>