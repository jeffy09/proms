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
    $expenses = $_POST['expenses'];
    $budget_id = $_POST['budget_id'];
    $usedbudget = $_POST['usedbudget'];
    $total_budget = $_POST['total_all_budget'];
    $database = new Database();
    $db = $database->getConnection();
    $encoded_id = urlencode(encrypt($project_id));
    $addusedbudget = ($usedbudget + $total_budget);

    try {
        $db->beginTransaction();

        $totalUsedBudget = 0;

        foreach ($expenses as $expense) {
            $expense_type = $expense['expense_type'];
            $expense_item = $expense['expense_item'];
            $unit_cost = $expense['unit_cost'];
            $unit_quantity = $expense['unit_quantity'];
            $total_cost = $expense['total_cost'];
            $note = $expense['note'];

            $totalUsedBudget += $total_cost;

            $query = "INSERT INTO project_expense (project_id, expense_id, expense_details, unit_quantity, unit_cost, total_cost, note)
                      VALUES (:project_id, :expense_id, :expense_details, :unit_quantity, :unit_cost, :total_cost, :note)";
            $stmt = $db->prepare($query);

            $params = [
                ':project_id' => $project_id,
                ':expense_id' => $expense_type,
                ':expense_details' => $expense_item,
                ':unit_quantity' => $unit_quantity,
                ':unit_cost' => $unit_cost,
                ':total_cost' => $total_cost,
                ':note' => $note
            ];

            $stmt->execute($params);
        }


       
        $query = "UPDATE project_requests SET used_budget = :used_budget, cost_goals_plan = :used_budget WHERE id = :project_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':used_budget' => $total_budget, ':project_id' => $project_id]);

        $query = "UPDATE budget SET budget = :budget WHERE id = :budget_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':budget' => $total_budget, ':budget_id' => $budget_id]);

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
            var encodedId = <?php echo json_encode($encoded_id); ?>;

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    // Redirect ไปยังหน้าที่มี project_id ที่ถูกเข้ารหัส
                    window.location.href = 'project_details?project_id=' + encodedId;
                });
            } else if (response.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: response.message
                }).then(function() {
                    // Redirect กลับไปยังหน้าที่มี project_id ที่เข้ารหัส
                    window.location.href = 'project_details?project_id=' + encodedId;
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