<!-- Modal -->



<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editExpenseModalLabel">แก้ไขรายการค่าใช้จ่าย</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm">
                    <input type="hidden" id="editExpenseId" name="expense_id">
                    <input type="hidden" class="form-control" id="editBudgetId" name="budget_id" value="<?php echo $budget_id ?>">
                    <div class="form-group mt-1">
                        <label for="editExpenseType">ประเภทค่าใช้จ่าย:</label>
                        <select class="form-control" id="editExpenseType" name="expense_type">
                            <option value="">เลือกประเภทค่าใช้จ่าย</option>
                            <?php foreach ($expense_types as $type) : ?>
                                <option value="<?php echo $type['id']; ?>"><?php echo $type['expense_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mt-1">
                        <label for="editExpenseItem">รายการค่าใช้จ่าย:</label>
                        <select class="form-control" id="editExpenseItem" name="expense_item">
                            <option value="">เลือกรายการค่าใช้จ่าย</option>
                        </select>
                    </div>
                    <div class="form-group mt-1">
                        <label for="editUnitCost">ค่าใช้จ่ายต่อหน่วย:</label>
                        <input type="number" class="form-control" id="editUnitCost" name="unit_cost" step="1">
                    </div>
                    <div class="form-group mt-1">
                        <label for="editUnitQuantity">จำนวนหน่วย:</label>
                        <input type="number" class="form-control" id="editUnitQuantity" name="unit_quantity">
                    </div>
                    <div class="form-group mt-1" id="editUnitQuantity2Group">
                        <label for="editUnitQuantity_2">จำนวนหน่วยที่สอง:</label>
                        <input type="number" class="form-control" id="editUnitQuantity_2" name="unit_quantity_2">
                    </div>
                    <div class="form-group mt-1">
                        <label for="editTotalCost">รวมค่าใช้จ่าย:</label>
                        <input type="text" class="form-control" id="editTotalCost" readonly>
                    </div>
                    <div class="form-group mt-1">
                        <label for="editUsedBudget">รวมค่าใช้จ่ายในโครงการทั้งหมด:</label>
                        <input type="text" class="form-control" id="editUsedBudget" readonly>
                    </div>
                    <div class="form-group mt-1">
                        <label for="editRemainingBudget">ค่าใช้จ่ายที่เหลือ:</label>
                        <input type="hidden" class="form-control" id="editRemainingBudget" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">บันทึก</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<div class="modal fade" id="sentcommentModal" tabindex="-1" aria-labelledby="sentcommentModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sentcommentModalLabel">เพิ่มข้อคิดเห็น</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sentcommentModalform">
                    <div class="form-group mt-1">
                        <label for="editUnitCost">กรอกข้อคิดเห็น</label>
                        <textarea type="text" class="form-control" id="comment" name="comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ส่งข้อคิดเห็น</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="seecommentModal" tabindex="-1" aria-labelledby="seecommentModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="seecommentModalLabel">ข้อคิดเห็นจ้าผู้ตรวจสอบ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a><?php echo htmlspecialchars($project_details['comment']); ?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="approveProjectModal" tabindex="-1" aria-labelledby="approveProjectModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="approveProjectModalLabel">ปรับสถานะ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจว่าต้องการปรับสถานะเป็น ผ่านการตรวจสอบแล้ว ?</p>
                <form id="approveProjectForm">
                    <button type="submit" class="btn btn-success mt-1">ยืนยัน</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ยกเลิก</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="sentverifyModal" tabindex="-1" aria-labelledby="sentverifyModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sentverifyModalLabel">ส่งตรวจโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการส่งตรวจโครงการ ใช่หรือไม่ ?</p>
                <form id="sentverifyProjectForm">
                    <button type="submit" class="btn btn-success mt-1">ยืนยัน</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ยกเลิก</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="resentModal" tabindex="-1" aria-labelledby="resentModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="resentModalLabel">ส่งตรวจโครงการอีกครั้ง</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจว่าต้องการส่งตรวจโครงการอีกครั้ง ใช่หรือไม่ ?</p>
                <form id="resentProjectForm">
                    <button type="submit" class="btn btn-success mt-1">ยืนยัน</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ยกเลิก</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="field_name" name="field_name">
                <input type="hidden" id="project_id" name="project_id">

                <div id="editInputField">
                    <label for="mainwayInput">แก้ไขวิถีหลัก</label>
                    <input type="text" class="form-control" id="mainwayInput">
                </div>
                <div id="editSelectField" style="display:none;">
                    <label for="subwaySelect">แก้ไขวิถีย่อย</label>
                    <select class="form-control" id="subwaySelect">
                        <option value="ปรับรื้อ สู่ Digital University">ปรับรื้อ สู่ Digital University</option>
                        <option value="2">ตัวเลือก 2</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveChanges">บันทึกการเปลี่ยนแปลง</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

</div>
<!-- container -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ตรวจสอบข้อมูล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalForm">
                    <div class="mb-3">
                        <h6 id="modalName"></h6>
                        <div class="alert alert-primary" role="alert" id="modalName">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modalValue" class="form-label">ค่า</label>
                        <input type="text" class="form-control" id="modalValue">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                <button type="button" class="btn btn-primary" id="saveChanges">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </div>
    </div>
</div>


<!--Edit Modal -->
<!-- Modal สำหรับแก้ไขวิถีหลัก -->
<div class="modal fade" id="editmainwayModal" tabindex="-1" aria-labelledby="editmainwayModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editmainwayModalLabel">แก้ไขวิถีหลัก</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editprojectTypeForm">
                    <input type="hidden" id="field_type" name="field_type" value="main_strategy">
                    <div class="form-group mt-1">
                        <div>
                            <label for="mainway_id">วิถีหลัก</label>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo ($project_details['main_strategy']); ?>"><?php echo htmlspecialchars($project_details['mainway_name']); ?></option>
                                <?php foreach ($main_ways as $main_way) : ?>
                                    <option value="<?php echo $main_way['id']; ?>"><?php echo htmlspecialchars($main_way['mainway_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Modal สำหรับแก้ไขวิถีย่อย -->
<div class="modal fade" id="editsubwayModal" tabindex="-1" aria-labelledby="editsubwayModalFormLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editmainwayModalLabel">แก้ไขวิถีย่อย</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editprojectTypeForm">
                    <input type="hidden" id="field_type" name="field_type" value="sub_strategy">
                    <div class="form-group mt-1">
                        <div>
                            <label for="subway_id">วิถีย่อย</label>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo ($project_details['sub_strategy']); ?>"><?php echo htmlspecialchars($project_details['subway_name']); ?></option>
                                <?php
                                if ($mainway_id = $project_details['main_strategy']) {
                                    $sub_ways = getSubWays($mainway_id);
                                }
                                ?>
                                <?php foreach ($sub_ways as $sub_way) : ?>
                                    <option value="<?php echo $sub_way['id']; ?>"><?php echo htmlspecialchars($sub_way['subway_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Modal สำหรับแก้ไขประเภทโครงการ -->
<div class="modal fade" id="editprojectfundingModal" tabindex="-1" aria-labelledby="editprojectfundingModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editprojectfundingModalLabel">แก้ไขแหล่งเงินงบประมาณ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editprojectTypeForm">
                    <input type="hidden" id="field_type" name="field_type" value="funding_source">
                    <div class="form-group mt-1">
                        <div>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo htmlspecialchars($project_details['funding_source']); ?>"><?php echo htmlspecialchars($project_details['funding_source']); ?></option>
                                <option value="เงินงบประมาณ">เงินงบประมาณ</option>
                                <option value="เงินรายได้">เงินรายได้</option>
                            </select><br>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขประเภทโครงการ -->
<div class="modal fade" id="editprojecttypeModal" tabindex="-1" aria-labelledby="editprojecttypeModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editprojecttypeModalLabel">แก้ไขประเภทโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editprojectTypeForm">
                    <input type="hidden" id="field_type" name="field_type" value="project_type">
                    <div class="form-group mt-1">
                        <div>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo htmlspecialchars($project_details['project_type']); ?>"><?php echo htmlspecialchars($project_details['project_type_name']); ?></option>
                                <option value="2">โครงการประจำ</option>
                                <option value="1">โครงการพัฒนา</option>
                            </select><br>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขความสอดคล้องพันธกิจของมหาวิทยาลัย -->
<div class="modal fade" id="editmissionalignmentModal" tabindex="-1" aria-labelledby="editmissionalignmentModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editmissionalignmentModalLabel">แก้ไขความสอดคล้องพันธกิจของมหาวิทยาลัย</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMissionAlignmentForm">
                    <input type="hidden" id="field_type" name="field_type" value="mission_alignment">
                    <div class="form-group mt-1">
                        <div>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo htmlspecialchars($project_details['mission_alignment']); ?>"><?php echo htmlspecialchars($project_details['mission_alignment']); ?></option>
                                <option value="การจัดการศึกษา">การจัดการศึกษา</option>
                                <option value="การวิจัย">การวิจัย</option>
                                <option value="การบริการวิชาการแก่สังคม">การบริการวิชาการแก่สังคม</option>
                                <option value="การทำนุบำรุงศิลปวัฒนธรรม">การทำนุบำรุงศิลปวัฒนธรรม</option>
                                <option value="ระบบการบริหารจัดการ">ระบบการบริหารจัดการ</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขหลักการและเหตุผล -->
<div class="modal fade" id="editrationaleModal" tabindex="-1" aria-labelledby="editrationaleModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editrationaleModalLabel">แก้ไขหลักการและเหตุผล</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRationaleForm">
                    <input type="hidden" id="field_type" name="field_type" value="rationale">
                    <div class="form-group mt-1">
                        <div>
                            <textarea style="width:100%" rows="15" class="form-control" id="field_value" name="field_value" required><?php echo ($project_details['rationale']); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขวัตถุประสงค์ -->
<div class="modal fade" id="editobjectivesModal" tabindex="-1" aria-labelledby="editobjectivesModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editobjectivesModalLabel">แก้ไขวัตถุประสงค์</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editObjectivesForm">
                    <input type="hidden" id="field_type" name="field_type" value="objectives">
                    <div class="form-group mt-1">
                        <div>
                            <textarea style="width:100%" rows="15" class="form-control" id="field_value" name="field_value" required><?php echo (htmlspecialchars($project_details['objectives'])); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขผลลัพธ์ -->
<div class="modal fade" id="editoutcomesModal" tabindex="-1" aria-labelledby="editoutcomesModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editoutcomesModalLabel">แก้ไขผลลัพธ์</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOutcomesForm">
                    <input type="hidden" id="field_type" name="field_type" value="outcomes">
                    <div class="form-group mt-1">
                        <div>
                            <textarea style="width:100%" rows="15" class="form-control" id="field_value" name="field_value" required><?php echo (htmlspecialchars($project_details['outcomes'])); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขผลกระทบ -->
<div class="modal fade" id="editimpactsModal" tabindex="-1" aria-labelledby="editimpactsModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editimpactsModalLabel">ผลกระทบ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editImpactsForm">
                    <input type="hidden" id="field_type" name="field_type" value="impacts">
                    <div class="form-group mt-1">
                        <div>
                            <textarea style="width:100%" rows="15" class="form-control" id="field_value" name="field_value" required><?php echo (htmlspecialchars($project_details['impacts'])); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขการดำเนินโครงการ -->
<div class="modal fade" id="editimplementationModal" tabindex="-1" aria-labelledby="editimplementationModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editimpactsModalLabel">การดำเนินโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editImplementationForm">
                    <input type="hidden" id="field_type" name="field_type" value="implementation">
                    <div class="form-group mt-1">
                        <div>
                            <textarea style="width:100%" rows="15" class="form-control" id="field_value" name="field_value" required><?php echo (htmlspecialchars($project_details['implementation'])); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขกลุ่มเป้าหมาย -->
<div class="modal fade" id="editprimarytargetgroupModal" tabindex="-1" aria-labelledby="editprimarytargetgroupModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editprimarytargetgroupModalLabel">แก้ไขกลุ่มเป้าหมาย</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPrimarytargetgroupForm">
                    <input type="hidden" id="field_type" name="field_type" value="primary_target_group">
                    <div class="form-group mt-1">
                        <div>
                            <input class="form-control" id="field_value" name="field_value" required value="<?php echo htmlspecialchars($project_details['primary_target_group']); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขจำนวนกลุ่มเป้าหมาย -->
<div class="modal fade" id="editprimarytargetgroupquantityModal" tabindex="-1" aria-labelledby="editprimarytargetgroupquantityModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editprimarytargetgroupquantityModalLabel">จำนวนกลุ่มเป้าหมาย</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPrimarytargetgroupquantityForm">
                    <input type="hidden" id="field_type" name="field_type" value="primary_target_group_quantity">
                    <div class="form-group mt-1">
                        <div>
                            <input type="number" class="form-control" id="field_value" name="field_value" required value="<?php echo htmlspecialchars($project_details['primary_target_group_quantity']); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขเดือนที่เริ่มจัดโครงการ -->
<div class="modal fade" id="editstartmonthModal" tabindex="-1" aria-labelledby="editstartmonthModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editstartmonthModalLabel">แก้ไขเดือนที่เริ่มจัดโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStartmonthModalForm">
                    <input type="hidden" id="field_type" name="field_type" value="start_month">
                    <div class="form-group mt-1">
                        <div>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo htmlspecialchars($project_details['start_month']); ?>"><?php echo htmlspecialchars($project_details['start_month']); ?></option>
                                <option value="มกราคม">มกราคม</option>
                                <option value="กุมภาพันธ์">กุมภาพันธ์</option>
                                <option value="มีนาคม">มีนาคม</option>
                                <option value="เมษายน">เมษายน</option>
                                <option value="พฤษภาคม">พฤษภาคม</option>
                                <option value="มิถุนายน">มิถุนายน</option>
                                <option value="กรกฎาคม">กรกฎาคม</option>
                                <option value="สิงหาคม">สิงหาคม</option>
                                <option value="กันยายน">กันยายน</option>
                                <option value="ตุลาคม">ตุลาคม</option>
                                <option value="พฤศจิกายน">พฤศจิกายน</option>
                                <option value="ธันวาคม">ธันวาคม</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแก้ไขเดือนที่สิ้นสุดโครงการ -->
<div class="modal fade" id="editendmonthModal" tabindex="-1" aria-labelledby="editendmonthModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editendmonthModalLabel">แก้ไขเดือนที่เริ่มจัดโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEndmonthModalForm">
                    <input type="hidden" id="field_type" name="field_type" value="end_month">
                    <div class="form-group mt-1">
                        <div>
                            <select class="form-select" id="field_value" name="field_value" required>
                                <option value="<?php echo htmlspecialchars($project_details['end_month']); ?>"><?php echo htmlspecialchars($project_details['end_month']); ?></option>
                                <option value="มกราคม">มกราคม</option>
                                <option value="กุมภาพันธ์">กุมภาพันธ์</option>
                                <option value="มีนาคม">มีนาคม</option>
                                <option value="เมษายน">เมษายน</option>
                                <option value="พฤษภาคม">พฤษภาคม</option>
                                <option value="มิถุนายน">มิถุนายน</option>
                                <option value="กรกฎาคม">กรกฎาคม</option>
                                <option value="สิงหาคม">สิงหาคม</option>
                                <option value="กันยายน">กันยายน</option>
                                <option value="ตุลาคม">ตุลาคม</option>
                                <option value="พฤศจิกายน">พฤศจิกายน</option>
                                <option value="ธันวาคม">ธันวาคม</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขวันที่จัดโครงการ -->
<div class="modal fade" id="editprojectdatesModal" tabindex="-1" aria-labelledby="editprojectdatesModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editprojectdatesModalLabel">แก้ไขเดือนที่เริ่มจัดโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProjectdatesModalForm">
                    <input type="hidden" id="field_type" name="field_type" value="project_dates">
                    <div class="form-group mt-1">
                        <div>
                            <label for="project_dates">วันที่จัดโครงการเดิม</label>
                            <a><?php echo htmlspecialchars($project_details['project_dates']); ?></a>
                            <label class="mt-2" for="project_dates">เลือกวันที่จัดโครงการใหม่</label>
                            <input type="text" class="form-control" id="multiple-datepicker" name="project_dates" placeholder="Select Date" data-provide="datepicker" data-date-multidate="true" data-date-container="#datepicker3">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขสถานที่จัดโครงการ -->
<div class="modal fade" id="editlocationModal" tabindex="-1" aria-labelledby="editlocationModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editlocationModalLabel">แก้ไขเดือนที่เริ่มจัดโครงการ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLocationModalForm">
                    <input type="hidden" id="field_type" name="field_type" value="location">
                    <div class="form-group mt-1">
                        <div>
                            <textarea class="form-control" id="field_value" name="field_value" required><?php echo nl2br(htmlspecialchars($project_details['location'])); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">ยืนยันการแก้ไข</button>
                    <button type="button" class="btn btn-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </form>
            </div>
        </div>
    </div>
</div>