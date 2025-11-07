<?php
require_once 'server/database.php';

if (!isset($_POST['id'], $_POST['admin_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing project ID']);
    exit;
}

$project_id = $_POST['id'];
$adminName = $_POST['admin_name'];

$database = new Database();
$db = $database->getConnection();

try {
    $db->beginTransaction();
    $query = "UPDATE project_requests SET project_status = 5, admin_name = :admin_name WHERE id = :project_id";
    $stmt = $db->prepare($query);
    $params = [
        ':admin_name' => $adminName,
        ':project_id' => $project_id
    ];
    $stmt->execute($params);

    $db->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}
