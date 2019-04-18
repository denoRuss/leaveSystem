<?php include_stylesheets_for_form($form); ?>
<?php include_javascripts_for_form($form); ?>

<style>
    .radio_list li, .checkbox_list li{
        display: inline;
        list-style-type: none;
        padding-right: 20px;
        width: 180px !important;
    }
    
    label.formRadio, label.formCheckbox {       
        margin: 3px !important;      
    }

</style>
<div id="saveHobTitle" class="box">

    <div class="head">        
        <h1 id="saveHobTitleHeading"><?php echo __($title); ?></h1>
    </div>

    <div class="inner">       

        <?php include_partial('global/flash_messages'); ?>

        <form id="frmSalaryType" method="post" action="<?php echo url_for('admin/saveSalaryType'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['id']->render(); ?>
            <fieldset>
                <ol>
                    <li class="long">
                        <label for="salary_type_name"><?php echo $form['name']->renderLabel() ?></label>
                        <?php echo $form['name']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_monthly_basic"><?php echo $form['monthly_basic']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_other_allowance"><?php echo $form['other_allowance']->renderLabel() ?></label>
                        <?php echo $form['other_allowance']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_monthly_basic_tax"><?php echo $form['monthly_basic_tax']->renderLabel() ?></label>
                        <?php echo $form['monthly_basic_tax']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_monthly_nopay_leave"><?php echo $form['monthly_nopay_leave']->renderLabel() ?></label>
                        <?php echo $form['monthly_nopay_leave']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_monthly_epf_deduction"><?php echo $form['monthly_epf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_epf_deduction']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_monthly_etf_deduction"><?php echo $form['monthly_etf_deduction']->renderLabel() ?></label>
                        <?php echo $form['monthly_etf_deduction']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="salary_type_jobtitle_id"><?php echo $form['jobtitle_id']->renderLabel() ?></label>
                        <?php echo $form['jobtitle_id']->render(array("class" => "editable")); ?>
                    </li>

                    <li class="required new">
                                    <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                                </li> 
                </ol>
                 
            </fieldset>       
            <div class="formbuttons">
                <?php if ($editable): ?>
                    <input type="submit" class="plainbtn" id="btnSave" value="<?php echo 'Save'; ?>" />
                    <input type="button" class="reset" id="btnCancel" value="<?php echo 'Cancel'; ?>" />
                <?php else: ?>
                    <input type="button" class="reset" id="btnCancel" value="<?php echo 'Back'; ?>" />
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var editable = <?php echo ($editable) ? 'true' : 'false'; ?>;
    
    var lang_Required = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var lang_AlreadyExists = '<?php echo __js(ValidationMessages::ALREADY_EXISTS); ?>';
    var lang_LengthExceeded_60 = '<?php echo __js(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 60)); ?>';
    
    var url_SalaryComponentList = '<?php echo url_for('admin/viewSalaryTypeList'); ?>';
    var url_CheckSalaryComponentNameNotExist = '<?php echo url_for('admin/checkSalaryComponentNameNotExistAjax'); ?>';
</script>