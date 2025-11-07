<?php
require_once 'server/database.php';

$budget_id = isset($_POST['budget_id']) ? $_POST['budget_id'] : null;
$project_id = isset($_POST['project_id']) ? $_POST['project_id'] : null;
$total_cost = isset($_POST['total_cost']) ? $_POST['total_cost'] : null;
$id = isset($_POST['id']) ? $_POST['id'] : null;

if (!$budget_id || !$total_cost || !$id) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $db->beginTransaction();

    // Delete project expense
    $query = "DELETE FROM project_expense WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $id]);

    // Update remaining budget in budget table
    $query = "UPDATE budget 
    SET budget = budget - :total_cost 
    WHERE id = :budget_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':total_cost' => $total_cost,
        ':budget_id' => $budget_id
    ]);

    // Update used budget in the project_requests table
    $query = "UPDATE project_requests 
    SET used_budget = used_budget - :total_cost 
    WHERE id = :project_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':total_cost' => $total_cost,
        ':project_id' => $project_id
    ]);

    $db->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}
