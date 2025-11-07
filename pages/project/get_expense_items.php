<?php
require_once 'server/database.php';

$expense_type = $_GET['expense_type'];

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM expense_items WHERE expense_id = :expense_id";
$stmt = $db->prepare($query);
$stmt->execute([':expense_id' => $expense_type]);

$options = '<option value="">เลือกรายการค่าใช้จ่าย</option>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options .= '<option value="' . $row['id'] . '">' . $row['expense_item_name'] . '</option>';
}

echo $options;
?>
