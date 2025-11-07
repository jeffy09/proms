<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $id = $_POST['id'];
    $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
    $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $role = htmlspecialchars(strip_tags($_POST['role']));
    $is_active = htmlspecialchars(strip_tags($_POST['is_active']));

    // อัพเดทข้อมูลผู้ใช้ในฐานข้อมูล
    $query = "UPDATE Users SET first_name = :first_name, last_name = :last_name, username = :username, role = :role, is_active = :is_active WHERE id = :id";
    $params = [
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':username' => $username,
        ':role' => $role,
        ':is_active' => $is_active,
        ':id' => $id
    ];

    $stmt = $database->secureQuery($query, $params);

    if ($stmt) {
        $response['status'] = 'success';
        $response['message'] = 'แก้ไขข้อมูลผู้ใช้สำเร็จ';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'เกิดข้อผิดพลาด: ไม่สามารถแก้ไขข้อมูลได้';
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                window.location.href = 'edit_user?id=<?php echo $id; ?>';
            });
        }
    });
</script>
