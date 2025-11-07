<?php
// function getEncryptionKeyFromDatabase()
// {
//     $database = new Database();
//     $db = $database->getConnection();

//     $query = "SELECT config_value FROM config WHERE config_key = :config_key";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':config_key', $config_key);
//     $config_key = 'encryption_key';
//     $stmt->execute();

//     $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     return $row['config_value'];
// }

// require_once 'database.php';

// function getEncryptionKey()
// {
//     static $encryption_key;
//     if (!$encryption_key) {
//         $encryption_key = getEncryptionKeyFromDatabase();
//     }
//     return $encryption_key;
// }

// function encrypt($data)
// {
//     $key = getEncryptionKey();
//     $method = 'aes-256-cbc';
//     $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
//     $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
//     return base64_encode($encrypted . '::' . $iv);
// }

// function decrypt($data)
// {
//     $key = getEncryptionKey();
//     $method = 'aes-256-cbc';
//     list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
//     return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
// }


// ฟังก์ชันดึงคีย์การเข้ารหัสจากไฟล์ config.php
function getEncryptionKey()
{
    static $encryption_key;
    if (!$encryption_key) {
        $config = include 'config.php'; // โหลดข้อมูลจากไฟล์ config.php
        $encryption_key = $config['encryption_key']; // ดึงคีย์การเข้ารหัสจาก config
    }
    return $encryption_key;
}

function encrypt($data)
{
    $key = getEncryptionKey();
    $method = 'aes-256-cbc';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data)
{
    $key = getEncryptionKey();
    $method = 'aes-256-cbc';
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
}


function encodeId($id)
{
    return base64_encode($id);
}

function decodeId($encodedId)
{
    return base64_decode($encodedId);
}

/** เปลี่ยนวันที่เป็นภาษาไทย */
function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    $strYearCut = substr($strYear, 2, 2);
    return "$strDay $strMonthThai $strYearCut - $strHour : $strMinute";
}

// ฟังก์ชันสำหรับดึงค่าจาก URL โดยไม่แสดง Part ใน URL
function getPath()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = trim($uri, '/');
    return $uri === '' ? 'home' : $uri;
}

// ฟังก์ชันสำหรับตรวจสอบสิทธิ์
function checkPermission($required_role)
{
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $required_role) {
        header("Location: unauthorized.php");
        exit;
    }
}

// ฟังก์ชันสำหรับดึงข้อมูลแผนก
function getDepartments()
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, department_name FROM department WHERE id NOT IN (3)";
    $stmt = $database->secureQuery($query);

    if ($stmt && $stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}

// ฟังก์ชันสำหรับดึงข้อมูลงบประมาณ
function getBudgets()
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT budget.id, budget.year, budget.budget, budget.remaining_budget, budget.project_name, budget.department_id, budget.funding, department.department_name, budget.budget_status 
              FROM budget 
              JOIN department ON budget.department_id = department.id";
    $stmt = $database->secureQuery($query);

    if ($stmt && $stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}



function getUserInfo()
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $user_id = $_SESSION['user_id'];
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM Users WHERE id = :user_id";
    $params = [':user_id' => $user_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDepartmentName($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT department_name FROM department WHERE id = :department_id";
    $params = [':department_id' => $department_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['department_name'];
}

// ฟังก์ชันสำหรับดึงข้อมูลงบประมาณหน่วยงาน
function getBudgetsDep($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT budget.id, budget.year, budget.budget, budget.remaining_budget, budget.project_name, budget.department_id, budget.funding, department.department_name, budget.budget_status 
              FROM budget 
              JOIN department ON budget.department_id = department.id
              WHERE budget.department_id = :department_id";
    $params = [':department_id' => $department_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    if ($stmt && $stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}

function getBudgetInfoAdd($budget_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM budget WHERE id = :budget_id ORDER BY year DESC LIMIT 1";
    $params = [':budget_id' => $budget_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getBudgetInfo($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM budget WHERE department_id = :department_id ORDER BY year DESC LIMIT 1";
    $params = [':department_id' => $department_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getProjectRequests($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    if ($department_id == 999) {
        $query = "SELECT pr.id, pr.project_year, pr.project_name, pr.department_id, pr.project_status, pr.used_budget, pr.activity_type, d.department_name
                  FROM project_requests pr
                  JOIN department d ON pr.department_id = d.id";
        $stmt = $db->prepare($query);
    } else {
        $query = "SELECT pr.id, pr.project_year, pr.project_name, pr.department_id, pr.project_status, pr.used_budget, pr.activity_type, d.department_name
                  FROM project_requests pr
                  JOIN department d ON pr.department_id = d.id
                  WHERE pr.department_id = :department_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':department_id', $department_id);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSubProjectRequests($project_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT *
                  FROM sub_project_requests
                  WHERE project_id = :project_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':project_id', $project_id);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ฟังก์ชันสำหรับดึงจำนวนงบประมาณ
function getBudGetCount($department_id)
{
    $database = new Database();
    $db = $database->getConnection();
    if ($department_id == 999) {
        $query = "SELECT SUM(budget) as budget_count FROM budget";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    } else {
        $query = "SELECT SUM(budget) as budget_count FROM budget WHERE department_id = :department_id";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    return $stmt->fetch(PDO::FETCH_ASSOC)['budget_count'];
}

// ฟังก์ชันสำหรับดึงจำนวนงบประมาณที่ใช้
function getUsedBudGetCount($department_id)
{
    $database = new Database();
    $db = $database->getConnection();
    if ($department_id == 999) {
        $query = "SELECT SUM(used_budget) as usedbudget_count FROM project_requests";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    } else {
        $query = "SELECT SUM(used_budget) as usedbudget_count FROM project_requests WHERE department_id = :department_id";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    return $stmt->fetch(PDO::FETCH_ASSOC)['usedbudget_count'];
}

// ฟังก์ชันสำหรับดึงจำนวนงบประมาณที่ใช้ในโครงการย่อย
function getSubUsedBudGetCount($department_id)
{
    $database = new Database();
    $db = $database->getConnection();
    if ($department_id == 999) {
        $query = "SELECT SUM(used_budget) as usedbudget_count FROM project_requests";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    } else {
        $query = "SELECT SUM(used_budget) as usedbudget_count FROM project_requests WHERE department_id = :department_id";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    return $stmt->fetch(PDO::FETCH_ASSOC)['usedbudget_count'];
}


function getSubUsedBudGet($project_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT SUM(used_budget) as usedbudget_count FROM sub_project_requests WHERE project_id = :project_id";
    $params = [':project_id' => $project_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC)['usedbudget_count'];
}

// ฟังก์ชันสำหรับดึงจำนวนโครงการ
function getProjectCount($department_id)
{
    $database = new Database();
    $db = $database->getConnection();
    if ($department_id == 999) {
        $query = "SELECT COUNT(*) as project_count FROM project_requests";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    } else {
        $query = "SELECT COUNT(*) as project_count FROM project_requests WHERE department_id = :department_id";
        $params = [':department_id' => $department_id];
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    return $stmt->fetch(PDO::FETCH_ASSOC)['project_count'];
}

// ฟังก์ชันสำหรับดึงข้อมูลโครงการ
function getProjectDetail($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT project_name, used_budget, project_status FROM project_requests WHERE department_id = :department_id";
    $params = [':department_id' => $department_id];
    $stmt = $db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBudgetStatusText($status)
{
    switch ($status) {
        case 0:
            return '<span class="badge bg-secondary-subtle text-secondary rounded-pill">ยังไม่ได้เพิ่มข้อมูล</span>';
        case 1:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มค่าใช้จ่าย</span>';
        case 2:
            return '<span class="badge bg-info-subtle text-info rounded-pill">รอการตรวจสอบ</span>';
        case 3:
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">ต้องแก้ไข</span>';
        case 5:
            return '<span class="badge bg-primary-subtle text-primary rounded-pill">ผ่านการตรวยสอบแล้ว</span>';
        case 6:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มค่าใช้จ่าย</span>';
        default:
            return "ไม่ทราบสถานะ";
    }
}

function getStatusText($status)
{
    switch ($status) {
        case 0:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มกิจกรรมย่อย</span>';
        case 1:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มค่าใช้จ่าย</span>';
        case 2:
            return '<span class="badge bg-info-subtle text-info rounded-pill">รอการตรวจสอบ</span>';
        case 3:
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">ต้องแก้ไข</span>';
        case 5:
            return '<span class="badge bg-primary-subtle text-primary rounded-pill">ผ่านการตรวจสอบแล้ว</span>';
        case 6:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มค่าใช้จ่าย</span>';
        default:
            return "ไม่ทราบสถานะ";
    }
}
function getActivityType($status)
{
    switch ($status) {
        case 1:
            return '<span class="badge bg-pink-subtle text-pink rounded-pill">โครงการแบบเดี่ยว</span>';
        case 2:
            return '<span class="badge bg-purple-subtle text-purple rounded-pill">โครงการแบบมีกิจกรรมย่อย</span>';
        default:
            return "ไม่ทราบสถานะ";
    }
}
function getStatusText_sub($status)
{
    switch ($status) {
        case 0:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มกิจกรรมย่อย</span>';
        case 1:
            return '<span class="badge bg-success-subtle text-success rounded-pill">เพิ่มกิจกรรมย่อยแล้ว</span>';
        case 2:
            return '<span class="badge bg-info-subtle text-info rounded-pill">รอการตรวจสอบ</span>';
        case 3:
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">ต้องแก้ไข</span>';
        case 5:
            return '<span class="badge bg-primary-subtle text-primary rounded-pill">ผ่านการตรวจสอบแล้ว</span>';
        case 6:
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">ยังไม่ได้เพิ่มค่าใช้จ่าย</span>';
        default:
            return "ไม่ทราบสถานะ";
    }
}
function getStatusDetail($status)
{
    switch ($status) {
        case 0:
            return '<button type="button" class="btn btn-soft-danger">
                        <i class="ri-alert-fill me-1 fs-16 lh-1"></i>
                        ยังไม่ได้เพิ่มกิจกรรมย่อย
                    </button>';
        case 1:
            return '<button type="button" class="btn btn-soft-danger">
                        <i class="ri-alert-fill me-1 fs-16 lh-1"></i>
                        ยังไม่ได้เพิ่มค่าใช้จ่าย
                    </button>';
        case 2:
            return '<button type="button" class="btn btn-soft-info">
                        <i class="ri-time-line me-1 fs-16 lh-1"></i>
                        รอการตรวจสอบ
                    </button>';
        case 3:
            return '<button type="button" class="btn btn-soft-warning">
                        <i class="ri-spam-fill me-1 fs-16 lh-1"></i>
                        ต้องแก้ไขข้อมูล
                    </button>';
        case 5:
            return '<button type="button" class="btn btn-primary">
                                    <i class="ri-check-double-fill me-1 fs-16 lh-1"></i>
                                    โครงการผ่านการตรวจสอบแล้ว
                                </button>';
        case 6:
            return '<button type="button" class="btn btn-soft-danger">
                                                <i class="ri-alert-fill me-1 fs-16 lh-1"></i>
                                                ยังไม่ได้เพิ่มค่าใช้จ่าย
                                            </button>';
        default:
            return "";
    }
}

function getStatusDetail_sub($status)
{
    switch ($status) {
        case 0:
            return '<button type="button" class="btn btn-soft-danger">
                        <i class="ri-alert-fill me-1 fs-16 lh-1"></i>
                        ยังไม่เพิ่มกิจกรรมย่อย
                    </button>';
        case 1:
            return '<button type="button" class="btn btn-soft-warning">
                        <i class="ri-emotion-happy-line me-1 fs-16 lh-1"></i>
                        ยังไม่ได้ส่งตรวจ
                    </button>';
        case 2:
            return '<button type="button" class="btn btn-soft-info">
                        <i class="ri-time-line me-1 fs-16 lh-1"></i>
                        รอการตรวจสอบ
                    </button>';
        case 3:
            return '<button type="button" class="btn btn-soft-warning">
                        <i class="ri-spam-fill me-1 fs-16 lh-1"></i>
                        ต้องแก้ไขข้อมูล
                    </button>';
        case 5:
            return '<button type="button" class="btn btn-primary">
                                    <i class="ri-check-double-fill me-1 fs-16 lh-1"></i>
                                    โครงการผ่านการตรวจสอบแล้ว
                                </button>';
        case 6:
            return '<button type="button" class="btn btn-soft-danger">
                        <i class="ri-alert-fill me-1 fs-16 lh-1"></i>
                        ยังไม่ได้เพิ่มค่าใช้จ่าย
                    </button>';
        default:
            return "";
    }
}

// ฟังก์ชั่นสำหรับดึงข้อมูล SubDepartment
function getSubDepartment($department_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, sub_department_name FROM sub_department WHERE department_id = :department_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':department_id', $department_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ฟังก์ชั่นสำหรับดึงข้อมูล basic information
function getBasicInformation()
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM basic_information WHERE id = '1'";
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ฟังก์ชั่นสำหรับดึงข้อมูล main_way
function getMainWays()
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, mainway_name FROM main_way";
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ฟังก์ชั่นสำหรับดึงข้อมูล sub_way
function getSubWays($mainway_id)
{
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, subway_name FROM sub_way WHERE mainway_id = :mainway_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':mainway_id', $mainway_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



//Project_details Page


function getProjectDetails($project_id)
{
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT *
    FROM project_requests pr
    JOIN main_way mw ON pr.main_strategy = mw.id
    JOIN sub_way sw ON pr.sub_strategy = sw.id
    JOIN department d ON pr.department_id = d.id
    JOIN sub_department sd ON pr.department_id = sd.id
    JOIN project_type pt ON pr.project_type = pt.id
    WHERE pr.id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $project_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getProjectExpenses($project_id)
{
    $database = new Database();
    $db = $database->getConnection();
    $query = $db->prepare("
        SELECT et.expense_name AS type, ei.expense_item_name AS item, pe.unit_cost, pe.unit_quantity, pe.unit_quantity_2, pe.total_cost, pe.expense_id, pe.id, pe.expense_details
        FROM project_expense pe
        JOIN expense_items ei ON pe.expense_details = ei.id
        JOIN expense_type et ON ei.expense_id = et.id
        WHERE pe.project_id = ?
    ");
    $query->execute([$project_id]);
    $expenses = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['type']][] = $row;
    }
    return $expenses;
}



function getSubProjectExpenses($sub_project_id)
{
    $database = new Database();
    $db = $database->getConnection();
    $query = $db->prepare("
        SELECT et.expense_name AS type, ei.expense_item_name AS item, pe.unit_cost, pe.unit_quantity, pe.unit_quantity_2, pe.total_cost, pe.expense_id, pe.id, pe.expense_details
        FROM project_expense pe
        JOIN expense_items ei ON pe.expense_details = ei.id
        JOIN expense_type et ON ei.expense_id = et.id
        WHERE pe.sub_project_id = ?
    ");
    $query->execute([$sub_project_id]);
    $expenses = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['type']][] = $row;
    }
    return $expenses;
}

function getProjectComment($project_id)
{
    $database = new Database();
    $db = $database->getConnection();
    $query = "SELECT *
    FROM project_comment
    WHERE project_id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $project_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



function getStatusComment($status, $dataName = '', $dataValue = '', $userRole)
{
    switch ($status) {
        case 0:
            if ($userRole === 'Superadmin') {
                return '<span class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal" data-name="' . htmlspecialchars($dataName) . '" data-value="' . htmlspecialchars($dataValue) . '">
                        <i class="ri-time-line"></i> ตรวจสอบข้อมูล</span> ';
            } elseif ($userRole === 'Admin') {
                return '<span class="badge bg-info">
                        <i class="ri-time-line"> รอการตรวจสอบ</i>
                    </span>';
            } else {
                return '';
            }
        case 3:
            if ($userRole === 'Superadmin') {
                return '';
            } elseif ($userRole === 'Admin') {
                return '<a href="#" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#exampleModal" data-name="' . htmlspecialchars($dataName) . '" data-value="' . htmlspecialchars($dataValue) . '">
                        <i class="ri-pencil-fill"></i> แก้ไขข้อมูล</a>';
            } else {
                return '';
            }

        case 2:
            return '<button type="button" class="btn btn-soft-success">
                        <i class="ri-check-double-fill me-1 fs-16 lh-1"></i>
                        ผ่านการตรวจสอบแล้ว
                    </button>';

        case 5:
            return '';
        default:
            return "ไม่ทราบสถานะ";
    }
}


// ฟังก์ชันสำหรับนับจำนวนตาม ประเภทโครงการ
function countProjectByType($db, $project_type)
{
    $database = new Database();
    $db = $database->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) AS total FROM project_requests WHERE project_type = :project_type");
    $stmt->bindParam(':project_type', $project_type, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// ฟังก์ชันสำหรับนับจำนวนโครงการทั้งหมด
function countTotalProject($db)
{
    $database = new Database();
    $db = $database->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) AS total FROM project_requests");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// ฟังก์ชันสำหรับคำนวณเปอร์เซ็นต์ของแต่ละ ประเภทโครงการ
function calculatePercentage($total, $typeCount)
{
    if ($total > 0) {
        return ($typeCount / $total) * 100;
    } else {
        return 0;
    }
}
