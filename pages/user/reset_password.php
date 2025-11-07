<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'server/database.php';

function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = htmlspecialchars(strip_tags($_POST['id']));
    $new_password = generateRandomPassword();
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE Users SET password = :new_password WHERE id = :id";
    $params = [
        ':new_password' => $hashed_password,
        ':id' => $user_id
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt) {
        echo json_encode(['success' => true, 'new_password' => $new_password]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
