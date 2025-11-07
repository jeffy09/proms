<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';



// ดึงข้อมูลจากฟอร์ม
$project_number = $_POST['project_number'];
$project_year = $_POST['project_year'] - 543;
$project_name = $_POST['project_name'];
$main_strategy = $_POST['main_strategy'];
$sub_strategy = $_POST['sub_strategy'];
$department_id = $_POST['department_id']; // ใช้ department_id จาก session
$sub_department = $_POST['sub_department'];
$budget_id = $_POST['budget_id'];
$funding_source = $_POST['funding_source'];
$allocated_budget = $_POST['allocated_budget'];
$project_manager = $_POST['project_manager'];
$manager_tel = $_POST['manager_tel'];
$project_type = $_POST['project_type'];
$mission_alignment = $_POST['mission_alignment'];
$rationale = $_POST['rationale'];
$objectives = $_POST['objectives'];
$outcomes = $_POST['outcomes'];
$impacts = $_POST['impacts'];
$implementation = $_POST['implementation'];
$primary_target_group = $_POST['primary_target_group'];
$primary_target_group_quantity = $_POST['primary_target_group_quantity'];
$start_month = $_POST['start_month'];
$end_month = $_POST['end_month'];
$project_dates = $_POST['project_dates'];
$location = $_POST['location'];
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// เพิ่มข้อมูลลงในฐานข้อมูล
$database = new Database();
$db = $database->getConnection();
$db->beginTransaction();

try {
    // Insert ข้อมูลไปยังตาราง project_requests
    $query = "INSERT INTO project_requests (project_number, project_year, project_name, main_strategy, sub_strategy, department_id, sub_department_id, budget_id, funding_source, allocated_budget, project_type, mission_alignment, rationale, objectives, outcomes, impacts, implementation, primary_target_group, primary_target_group_quantity, start_month, end_month, project_dates, location, created_at, updated_at, project_manager, manager_tel) 
              VALUES (:project_number, :project_year, :project_name, :main_strategy, :sub_strategy, :department_id, :sub_department, :budget_id, :funding_source, :allocated_budget, :project_type, :mission_alignment, :rationale, :objectives, :outcomes, :impacts, :implementation, :primary_target_group, :primary_target_group_quantity, :start_month, :end_month, :project_dates, :location, :created_at, :updated_at, :project_manager, :manager_tel)";

    $params = [
        ':project_number' => $project_number,
        ':project_year' => $project_year,
        ':project_name' => $project_name,
        ':main_strategy' => $main_strategy,
        ':sub_strategy' => $sub_strategy,
        ':department_id' => $department_id,
        ':sub_department' => $sub_department,
        ':budget_id' => $budget_id,
        ':funding_source' => $funding_source,
        ':allocated_budget' => $allocated_budget,
        ':project_type' => $project_type,
        ':mission_alignment' => $mission_alignment,
        ':rationale' => $rationale,
        ':objectives' => $objectives,
        ':outcomes' => $outcomes,
        ':impacts' => $impacts,
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

    // Update ข้อมูลไปยังตาราง budget
    $budget_id = $_POST['budget_id']; // ดึงค่า budget_id จากฟอร์มหรือจากแหล่งข้อมูลอื่น
    $budget_status = $_POST['budget_status']; // ดึงค่า budget_status จากฟอร์มหรือจากการคำนวณ

    $query_update_budget = "UPDATE budget SET budget_status = :budget_status WHERE id = :budget_id";
    $params_update_budget = [
        ':budget_status' => $budget_status,
        ':budget_id' => $budget_id
    ];

    $stmt_update_budget = $db->prepare($query_update_budget);
    $stmt_update_budget->execute($params_update_budget);

    // ถ้าทุกอย่างสำเร็จให้ commit
    $db->commit();

    $response['status'] = 'success';
    $response['message'] = 'เพิ่มค่าโครงการสำเร็จและอัปเดตสถานะงบประมาณสำเร็จ';

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