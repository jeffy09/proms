<?php

require_once '../../../server/database.php';
require_once '../../../server/functions.php';

$database = new Database();
$db = $database->getConnection();
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $user_info = getUserInfo();
    $user_id = $user_info['id'];
    $department_id = $user_info['department_id'];
    $userrole = $user_info['role'];
    // $project_id = $_GET['project_id'];
    $project_details = getProjectDetails($project_id);
    $expenses = getProjectExpenses($project_id);
    $project_comment = getProjectComment($project_id);
    $main_ways = getMainWays();


    $query = "SELECT * FROM budget WHERE department_id = :department_id ORDER BY year DESC LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute([':department_id' => $department_id]);
    $budget = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_budget = $budget['id'];
    $total_budget = $budget['budget'];
    $remaining_budget = $budget['remaining_budget'] ?: $budget['budget'];

    $query = "SELECT * FROM expense_type";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $expense_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM basic_information WHERE id = '1'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $basic_information = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>


<head>
    <script src="../../../assets/js/config.js"></script>

    <!-- App css -->
    <link href="../../../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />


    <style>
        h1 {
            text-align: center;
        }


        div.c {
            margin-left: 5px;
        }

        table.c,
        th.c,
        td.c {
            border: 1px solid black;
        }

        left-1 {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    </br>
    <h4><b>7. เดือนที่เริ่ม - เดือนที่สิ้นสุด</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td>
                <font size="12px"><?= nl2br($project_details['start_month']); ?> - <?= nl2br($project_details['end_month']); ?></font>
            </td>
        </tr>
    </table>
    <h4><b>8. วันที่จัดโครงการ</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td>
                <font size="12px"><?= nl2br($project_details['project_dates']); ?></font>
            </td>
        </tr>
    </table>
    <h4><b>9. สถานที่จัดโครงการ</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td>
                <font size="12px"><?= nl2br($project_details['location']); ?></font>
            </td>
        </tr>
    </table>
    <h4><b>10. แหล่งเงินงบประมาณ</b> : <font size="12px"><?= nl2br($project_details['funding_source']); ?></font>
    </h4>
    <table style="padding-left:15px">
        <tr>
            <td>
                <font size="12px">งบประมาณที่ได้รับจัดสรร : <b><?php echo htmlspecialchars($project_details['used_budget']); ?></b> บาท</font>
            </td>
        </tr>
        <tr>
            <td>
                <font size="12px">งบประมาณของโครงการ : <b><?php echo htmlspecialchars($project_details['used_budget']); ?></b> บาท</font>
            </td>
        </tr>
    </table>
    <h4><b>11. เป้าหมาย ผลลัพธ์ และผลกระทบโครงการ</b> : <font size="12px"><?= nl2br($project_details['funding_source']); ?></font>
    </h4>
    <table style="padding-left:15px">
        <tr>
            <td>
                <font size="12px"><b>10.1 เป้าหมายโครงการ</b></font>
            </td>
        </tr>
    </table>
    <table class="c">
        <thead class="c">
            <tr>
                <th class="c" style="width:45%">ตัวชี้วัด</th>
                <th class="c" style="width:20%">หน่วยนับ</th>
                <th class="c" style="width:20%">ปี <?php echo htmlspecialchars($project_details['project_year'] + 542); ?> แผน (ผล)</th>
                <th class="c" style="width:20%">ปี <?php echo htmlspecialchars($project_details['project_year'] + 543); ?> แผน</th>
            </tr>
        </thead>
        <tbody class="c">
            <tr>
                <td class="c" style="width:45%"><b>เชิงปริมาณ</b>: จำนวนผู้เข้าร่วมโครงการ</td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
            </tr>
            <tr>
                <td class="c" style="width:45%"><b>เชิงคุณภาพ</b>: โครงการ/กิจกรรมที่บรรลุตามวัตถุประสงค์</td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
            </tr>
            <tr>
                <td class="c" style="width:45%"><b>เชิงเวลา</b>: โครงการ/กิจกรรมที่แล้วเสร็จตามระยะเวลาที่กำหนด</td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
            </tr>
            <tr>
                <td class="c" style="width:45%"><b>เชิงต้นทุน</b>: ค่าใช้จ่ายการผลิตตามงบประมาณที่ได้รับจัดสรร</td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
                <td class="c" style="width:20%"></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td>
                <font size="12px"><b>10.2 ผลลัพธ์ที่คาดว่าจะได้รับ</b></font>
            </td>
        </tr>
        <tr>
            <td>
                <font size="12px"><?php echo nl2br($project_details['outcomes']); ?></font>
            </td>
        </tr>
    </table>
</body>

</html>