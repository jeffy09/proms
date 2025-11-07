<?php
require_once 'server/database.php';

if (!isset($_GET['expense_item'])) {
    echo 'Expense item not provided';
    exit;
}

$expense_item_id = $_GET['expense_item'];
error_log('Received expense item ID: ' . $expense_item_id); // Log ค่าที่ได้รับ

$database = new Database();
$db = $database->getConnection();

// ปรับ query ให้ดึงค่า cost_type เพิ่มเข้ามา
$query = "SELECT unit_cost, cost_type, note FROM expense_items WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $expense_item_id]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    error_log('Unit cost found: ' . $row['unit_cost']); // Log ค่า unit cost ที่พบ
    error_log('Cost type found: ' . $row['cost_type']); // Log ค่า cost type ที่พบ
    error_log('Note found: ' . $row['note']); // Log ค่า note ที่พบ

    // ส่งค่ากลับไปทั้ง unit_cost, cost_type และ note
    echo json_encode([
        'unit_cost' => trim($row['unit_cost']), // ใช้ trim เพื่อกำจัดช่องว่างและบรรทัดใหม่
        'cost_type' => (int)$row['cost_type'],  // ส่ง cost_type กลับไปให้ JavaScript
        'note' => trim($row['note'])
    ]);
} else {
    error_log('No data found for ID: ' . $expense_item_id); // Log กรณีไม่พบข้อมูล
    echo json_encode([
        'unit_cost' => '',
        'cost_type' => 0, // ถ้าไม่พบข้อมูล ให้ส่งค่า default เป็น 0 สำหรับ cost_type
        'note' => ''
    ]);
}
?>
