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
    <h1>แบบคำขออนุมัติโครงการ</h1>
    <h4 class="mt-1 fs-16 ellipsis"><b>1. ชื่อโครงการ :</b> <?php echo htmlspecialchars($project_details['project_name']); ?></h4>
    <h4 class="mt-1 fs-16 ellipsis"><b>2. ข้อมูลพื้นฐาน</b></h4>
    <table style="padding-left:15px">
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.1 ชื่อส่วนงานหลัก : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['department_name']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.2 ชื่อส่วนงานย่อยที่ขออนุมัติโครงการ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['sub_department_name']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.3 ชื่อผู้รับผิดชอบโครงการ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['project_manager']); ?> <b>โทร. </b><?php echo htmlspecialchars($project_details['manager_tel']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.4 ยุทธศาสตร์ชาติ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['national_strategy']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.5 ยุทธศาสตร์จัดสรร : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['allocation_strategy']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.6 แผนงาน/แผนงบประมาณ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['work_plan']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.7 เป้าหมายการให้บริการกระทรวง : </b></font>
                </th>
                <td style="width:80%">
                    <font size="12px"><?php echo nl2br($basic_information['ministry_service_goals']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.8 เป้าหมายการให้บริการหน่วยงาน : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['agency_service_goals']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.9 กลยุทธ์หน่วยงาน : </b></font>
                </th>
                <td style="width:120%">
                    <font size="12px"><?php echo nl2br($basic_information['agency_strategy']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.10 ผลผลิต/โครงการ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['product_project']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.11 อุดหนุนโครงการ : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($basic_information['support_project']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">
                    <font size="12px"><b> 2.12 ยุทธศาสตร์มหาวิทยาลัย : </b></font>
                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['mainway_name']); ?></font>
                </td>
            </tr>
            <tr>
                <th style="width:35%">

                </th>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['subway_name']); ?></font>
                </td>
            </tr>

    </table>
    <h4><b>3. หลักการและเหตุผล</b></h4>
    <table style="padding-left:15px">
        <tbody>
            <tr>
                <td>
                    <font size="12px"><?php echo htmlspecialchars($project_details['rationale']); ?></font>
                </td>
            </tr>
        </tbody>
    </table>
    <h4><b>4. วัตถุประสงค์</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td> <font size="12px"><?= nl2br($project_details['objectives']); ?></font></td>
        </tr>
    </table>
    <h4><b>5. กลุ่มเป้าหมาย และผู้มีส่วนได้ส่วนเสีย</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td> <font size="12px"><?= nl2br($project_details['primary_target_group']); ?></font></td>
        </tr>
    </table>
    <h4><b>6. การดำเนินโครงการ</b></h4>
    <table style="padding-left:15px">
        <tr>
            <td> <font size="12px"><?= nl2br($project_details['implementation']); ?></font></td>
        </tr>
    </table>

</body>

</html>