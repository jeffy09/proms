<?php
session_start();
require_once 'server/database.php';
require_once 'server/functions.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูล project_id จาก URL


if (!isset($_GET['id'])) {
    header('Location: manage_project');
    exit;
}
$encoded_id = htmlspecialchars(strip_tags($_GET['id']));
$project_id = decrypt(urldecode($encoded_id));


// ดึงข้อมูล project และ budget
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM project_requests WHERE id = :project_id";
$stmt = $db->prepare($query);
$stmt->execute([':project_id' => $project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

$department_id = $project['department_id'];

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .expense-section {
            margin-bottom: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Add Expense</h2>
        <div class="form-group">
            <label>งบประมาณทั้งหมด:</label>
            <input type="text" class="form-control" value="<?php echo number_format($total_budget, 2); ?>" readonly>
        </div>
        <div class="form-group">
            <label>งบประมาณคงเหลือที่ใช้ได้:</label>
            <input type="text" class="form-control" value="<?php echo number_format($remaining_budget, 2); ?>" readonly id="remainingBudget">
        </div>
        <form action="process_add_expense" method="POST" id="expenseForm">
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

            <input type="text" name="budget_id" value="<?php echo $id_budget; ?>">

            <div id="expense-sections">
                <!-- Sections for expenses will be added here dynamically -->
            </div>

            <button type="button" class="btn btn-secondary" id="addExpenseBtn">เพิ่มรายการ</button>
            <div class="form-group mt-4">
                <label>ค่าใช้จ่ายรวมทั้งหมด:</label>
                <input type="text" class="form-control" id="totalExpense" name="total_budget" readonly>
            </div>
            <div class="form-group">
                <label>ค่าใช้จ่ายที่เหลือ:</label>
                <input type="text" class="form-control" id="remainingExpense" name="remaining_budget" readonly>
            </div>
            <div class="alert alert-danger" id="budgetAlert" style="display: none;">
                ค่าใช้จ่ายทั้งหมดเกินจากงบประมาณที่ใช้ได้
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            var expenseIndex = 0;

            $('#addExpenseBtn').on('click', function() {
                addExpenseSection();
            });

            function addExpenseSection() {
                var expenseHtml = `
                    <div class="expense-section" id="expenseSection_${expenseIndex}">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expense_type_${expenseIndex}">ประเภทค่าใช้จ่าย:</label>
                                    <select class="form-control expense-type" id="expense_type_${expenseIndex}" name="expenses[${expenseIndex}][expense_type]" required>
                                        <option value="">เลือกประเภทค่าใช้จ่าย</option>
                                        <?php foreach ($expense_types as $type) : ?>
                                            <option value="<?php echo $type['expense_id']; ?>"><?php echo $type['expense_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expense_item_${expenseIndex}">รายการค่าใช้จ่าย:</label>
                                    <select class="form-control expense-item" id="expense_item_${expenseIndex}" name="expenses[${expenseIndex}][expense_item]" required>
                                        <option value="">เลือกรายการค่าใช้จ่าย</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="unit_cost_${expenseIndex}">ค่าใช้จ่ายต่อหน่วย:</label>
                                    <input type="number" class="form-control unit-cost" id="unit_cost_${expenseIndex}" name="expenses[${expenseIndex}][unit_cost]" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="unit_quantity_${expenseIndex}">จำนวนหน่วย:</label>
                                    <input type="number" class="form-control unit-quantity" id="unit_quantity_${expenseIndex}" name="expenses[${expenseIndex}][unit_quantity]" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="total_cost_${expenseIndex}">รวมค่าใช้จ่าย:</label>
                                    <input type="number" class="form-control total-cost" id="total_cost_${expenseIndex}" name="expenses[${expenseIndex}][total_cost]" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="note_${expenseIndex}">หมายเหตุ:</label>
                                    <textarea class="form-control" id="note_${expenseIndex}" name="expenses[${expenseIndex}][note]"></textarea>
                                </div>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-danger remove-expense-btn">ลบรายการ</button>
                                <hr>
                            </div>
                        </div>
                    </div>
                `;
                $('#expense-sections').append(expenseHtml);
                expenseIndex++;
            }

            $(document).on('change', '.expense-type', function() {
                var $section = $(this).closest('.expense-section');
                var expenseType = $(this).val();
                var $expenseItemSelect = $section.find('.expense-item');

                $.ajax({
                    url: 'get_expense_items',
                    method: 'GET',
                    data: {
                        expense_type: expenseType
                    },
                    success: function(response) {
                        $expenseItemSelect.html(response);
                    }
                });
            });

            $(document).on('change', '.expense-item', function() {
                var $section = $(this).closest('.expense-section');
                var expenseItem = $(this).val();
                var $unitCostInput = $section.find('.unit-cost');

                console.log("Selected expense item ID: ", expenseItem); // Log ค่าที่เลือก

                $.ajax({
                    url: 'get_unit_cost',
                    method: 'GET',
                    data: {
                        expense_item: expenseItem
                    },
                    success: function(response) {
                        console.log("Received unit cost: ", response); // Log ค่า unit cost ที่ได้รับ
                        $unitCostInput.val(response.trim()).prop('readonly', !!response.trim());
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            });

            $(document).on('input', '.unit-quantity, .unit-cost', function() {
                var $section = $(this).closest('.expense-section');
                var unitCost = parseFloat($section.find('.unit-cost').val()) || 0;
                var unitQuantity = parseFloat($section.find('.unit-quantity').val()) || 0;
                var totalCost = unitCost * unitQuantity;

                $section.find('.total-cost').val(totalCost.toFixed(2));
                updateTotalExpense();
            });

            $(document).on('click', '.remove-expense-btn', function() {
                $(this).closest('.expense-section').remove();
                updateTotalExpense();
            });

            function updateTotalExpense() {
                var totalExpense = 0;
                $('.total-cost').each(function() {
                    totalExpense += parseFloat($(this).val()) || 0;
                });
                $('#totalExpense').val(totalExpense.toFixed(2));

                var remainingBudget = parseFloat($('#remainingBudget').val().replace(/,/g, '')) || 0;
                var remainingExpense = remainingBudget - totalExpense;

                $('#remainingExpense').val(remainingExpense.toFixed(2));

                if (remainingExpense < 0) {
                    $('#budgetAlert').show();
                    $('#submitBtn').prop('disabled', true);
                    $('#addExpenseBtn').prop('disabled', true);
                } else {
                    $('#budgetAlert').hide();
                    $('#submitBtn').prop('disabled', false);
                    $('#addExpenseBtn').prop('disabled', false);
                }
            }
        });
    </script>
</body>

</html>