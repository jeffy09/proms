<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'server/database.php';

// ตรวจสอบว่ามีการส่งข้อมูลหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
    $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = htmlspecialchars(strip_tags($_POST['role']));
    $department_id = htmlspecialchars(strip_tags($_POST['department_id']));

    // จัดการกับการอัพโหลดไฟล์รูปโปรไฟล์
    $profile_picture = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "pages/user/uploads/";
        $file_type = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_type, $allowed_types)) {
            $new_file_name = uniqid() . "." . $file_type;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Failed to upload file.";
                exit;
            }
        } else {
            echo "Invalid file type.";
            exit;
        }
    }

    $query = "INSERT INTO Users (first_name, last_name, username, password, profile_picture, role, department_id) 
              VALUES (:first_name, :last_name, :username, :password, :profile_picture, :role, :department_id)";

    $params = [
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':username' => $username,
        ':password' => $password,
        ':profile_picture' => $profile_picture,
        ':role' => $role,
        ':department_id' => $department_id
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt) {
        $response['status'] = 'success';
        $response['message'] = 'เพิ่มค่าใช้จ่ายสำเร็จ';
    } else {
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
                    window.location.href = 'manage_user';
                });
            } else if (response.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: response.message
                }).then(function() {
                    window.location.href = 'manage_user'; // หรือหน้าที่ต้องการกลับไป
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
