<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'server/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = htmlspecialchars(strip_tags($_POST['id']));

    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM Users WHERE id = :id";
    $params = [
        ':id' => $user_id
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
