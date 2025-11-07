<?php
require_once 'server/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $budget_id = htmlspecialchars(strip_tags($_POST['id']));

    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM budget WHERE id = :id";
    $stmt = $db->prepare($query);

    if ($stmt->execute([':id' => $budget_id])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete record.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
