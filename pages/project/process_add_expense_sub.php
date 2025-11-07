<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$response = [
    'status' => '',
    'message' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $main_project_id = $_POST['main_project_id'];
    $funding_source = $_POST['funding_source'];
    $expenses = $_POST['expenses'];
    $budget_id = $_POST['budget_id'];
    $database = new Database();
    $db = $database->getConnection();

    try {
        $db->beginTransaction();

        $totalUsedBudget = 0;

        foreach ($expenses as $expense) {
            $expense_type = $expense['expense_type'];
            $expense_item = $expense['expense_item'];
            $unit_cost = $expense['unit_cost'];
            $unit_quantity = $expense['unit_quantity'];

            // ตรวจสอบว่ามีค่า unit_quantity_2 ถูกส่งมาหรือไม่
            $unit_quantity_2 = isset($expense['unit_quantity_2']) && !empty($expense['unit_quantity_2']) ? $expense['unit_quantity_2'] : null;

            $total_cost = $expense['total_cost'];
            $note = $expense['note'];

            $totalUsedBudget += $total_cost;

            $query = "INSERT INTO project_expense (project_id, sub_project_id, expense_id, expense_details, unit_quantity, unit_quantity_2, unit_cost, total_cost, note)
                      VALUES (:main_project_id, :project_id, :expense_id, :expense_details, :unit_quantity, :unit_quantity_2, :unit_cost, :total_cost, :note)";
            $stmt = $db->prepare($query);

            $params = [
                ':main_project_id' => $main_project_id,
                ':project_id' => $project_id,
                ':expense_id' => $expense_type,
                ':expense_details' => $expense_item,
                ':unit_quantity' => $unit_quantity,
                ':unit_quantity_2' => $unit_quantity_2,
                ':unit_cost' => $unit_cost,
                ':total_cost' => $total_cost,
                ':note' => $note
            ];

            $stmt->execute($params);
        }

        $query = "INSERT INTO project_comment (project_id)
        VALUES (:project_id)";
        $stmt = $db->prepare($query);
        $params = [
            ':project_id' => $project_id
        ];

        $stmt->execute($params);

        $query = "UPDATE sub_project_requests SET budget_id = :budget_id, funding_source = :funding_source, used_budget = :used_budget, cost_goals_plan = :used_budget ,project_status = '2' WHERE id = :project_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':budget_id' => $budget_id, ':funding_source' => $funding_source, ':used_budget' => $totalUsedBudget, ':project_id' => $project_id]);

        $query = "UPDATE project_requests SET funding_source = :funding_source, project_status = '1' WHERE id = :main_project_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':funding_source' => $funding_source, ':main_project_id' => $main_project_id]);

        $query = "UPDATE budget SET budget = :budget, funding = :funding WHERE id = :budget_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':budget' => $totalUsedBudget, ':funding' => $funding_source, ':budget_id' => $budget_id]);

        $db->commit();

        $response['status'] = 'success';
        $response['message'] = 'เพิ่มค่าใช้จ่ายสำเร็จ';
    } catch (Exception $e) {
        $db->rollBack();
        $response['status'] = 'error';
        $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
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
                    window.location.href = 'manage_projects'; // หรือหน้าที่ต้องการกลับไป
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