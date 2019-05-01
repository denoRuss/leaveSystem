<?php use_javascript(plugin_web_path('orangehrmPayrollPlugin', 'js/viewEmployeeSalaryPaymentSuccess.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmPayrollPlugin', 'css/viewEmployeeSalaryPaymentSuccess.css')); ?>


<div id="saveHobTitle" class="box">

    <div class="head">
        <h1 id="saveHobTitleHeading"><?php echo __($title); ?></h1>
    </div>

    <div class="inner">

        <?php include_partial('global/flash_messages'); ?>

        <form id="frmEmployeeSalaryPayment" method="post" action="<?php echo url_for('admin/saveEmployeeSalaryPayment'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['id']->render(); ?>
            <fieldset>
                <ol>
                    <li class="long">
                        <label for="employee_salary_payment_employee_name"><?php echo $form['employee_name']->renderLabel() ?></label>
                        <?php echo $form['employee_name']->render(array("class" => "")); ?>
                    </li>

                    <li class="long">
                        <label for="employee_salary_payment_monthly_basic"><?php echo $form['monthly_basic']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_other_allowance"><?php echo $form['other_allowance']->renderLabel() ?></label>
                        <?php echo $form['other_allowance']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_monthly_basic_tax"><?php echo $form['monthly_basic_tax']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic_tax']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_monthly_nopay_leave"><?php echo $form['monthly_nopay_leave']->renderLabel() ?></label>
                        <?php echo $form['monthly_nopay_leave']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_monthly_epf_deduction"><?php echo $form['monthly_epf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_epf_deduction']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_monthly_etf_deduction"><?php echo $form['monthly_etf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_etf_deduction']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_year"><?php echo $form['year']->renderLabel() ?></label>
                        <?php echo $form['year']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="employee_salary_payment_month"><?php echo $form['month']->renderLabel() ?></label>
                        <?php echo $form['month']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label id="payemnt_error"></label>

                    </li>

                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

            </fieldset>
            <div class="formbuttons">
                <?php if ($editable): ?>
                    <input type="submit" class="plainbtn" id="btnSave" value="<?php echo 'Make Payment'; ?>" />
                    <input type="button" class="reset" id="btnCancel" value="<?php echo 'Cancel'; ?>" />
                <?php else: ?>
                    <input type="button" class="reset" id="btnCancel" value="<?php echo 'Back'; ?>" />
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php include_component('core', 'ohrmList'); ?>

<script type="text/javascript">
    var editable = <?php echo ($editable) ? 'true' : 'false'; ?>;
    var empNumber = <?php echo $empNumber ?>;

    var lang_Required = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var lang_AlreadyExists = '<?php echo __js(ValidationMessages::ALREADY_EXISTS); ?>';
    var lang_LengthExceeded_60 = '<?php echo __js(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 60)); ?>';

    var url_employeeSalaryList = '<?php echo url_for('admin/employeeSalaryList'); ?>';
    var url_CheckEmployeeSalaryPaymentExist = '<?php echo url_for('admin/checkEmployeeSalaryPaymentExistAjax'); ?>';
    var url_getNopayLeaveBalanceAjax = '<?php echo url_for('admin/getNopayLeaveBalanceAjax'); ?>';
    var lang_salaryShouldBeNumeric = '<?php echo __js("Should be a positive number"); ?>';
    var lang_alreadypaid = '<?php echo __js("Already paid for selected Month"); ?>';
</script>