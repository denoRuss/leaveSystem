<?php use_javascript(plugin_web_path('orangehrmPayrollPlugin', 'js/employeeSalary.js')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmPayrollPlugin', 'css/employeeSalarySuccess.css')); ?>

<div id="saveHobTitle" class="box">

    <div class="head">
        <h1 id="saveHobTitleHeading"><?php echo __($title); ?></h1>
    </div>

    <div class="inner">

        <?php include_partial('global/flash_messages'); ?>

        <form id="frmEmployeeSalaryRecord" method="post" action="<?php echo url_for('admin/saveEmployeeSalaryRecord'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['id']->render(); ?>
            <fieldset>
                <ol>
                    <li class="long">
                        <label for="employee_salary_record_employee_name"><?php echo $form['employee_name']->renderLabel() ?></label>
                        <?php echo $form['employee_name']->render(array("class" => "")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_record_monthly_basic"><?php echo $form['monthly_basic']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_record_other_allowance"><?php echo $form['other_allowance']->renderLabel() ?></label>
                        <?php echo $form['other_allowance']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_record_monthly_basic_tax"><?php echo $form['monthly_basic_tax']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic_tax']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label id="taxbracket_error"></label>

                    </li>
                    <li class="long">
                        <label for="employee_salary_record_monthly_nopay_leave"><?php echo $form['monthly_nopay_leave']->renderLabel() ?></label>
                        <?php echo $form['monthly_nopay_leave']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_record_monthly_epf_deduction"><?php echo $form['monthly_epf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_epf_deduction']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_record_monthly_etf_deduction"><?php echo $form['monthly_etf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_etf_deduction']->render(array("class" => "editable")); ?>
                    </li>


                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

            </fieldset>
            <div class="formbuttons">
                <?php if ($editable): ?>
                    <input type="submit" class="plainbtn" id="btnSave" value="<?php echo 'Edit'; ?>" />
                    <input type="button" class="reset hide" id="btnCancel" value="<?php echo 'Cancel'; ?>" />
                <?php else: ?>
                    <input type="button" class="reset" id="btnCancel" value="<?php echo 'Back'; ?>" />
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var editable = <?php echo ($editable) ? 'true' : 'false'; ?>;

    var edit = '<?php echo __js('Edit'); ?>';
    var save = '<?php echo __js('Save'); ?>';
    var lang_processing = '<?php echo __js('Processing'); ?>';

    var lang_Required = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var lang_AlreadyExists = '<?php echo __js(ValidationMessages::ALREADY_EXISTS); ?>';
    var lang_LengthExceeded_60 = '<?php echo __js(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 60)); ?>';
    var lang_invalidTaxbracket = '<?php echo __js('No Tax Bracket is defined for this range'); ?>';

    var url_employeeSalaryList = '<?php echo url_for('admin/makePayment'); ?>';
    var lang_salaryShouldBeNumeric = '<?php echo __js("Should be a positive number"); ?>';
    var salaryTypeList = <?php echo str_replace('&#039;', "'", $form->getSalaryTypeListAsJson()) ?>;
    var taxBracketList = <?php echo str_replace('&#039;', "'", $form->getTaxBracketListAsJson()) ?>;

    var EPF_Percentage = <?php echo $epfPercentage;?>;
    var ETF_Percentage = <?php echo $etfPercentage;?>;

</script>
