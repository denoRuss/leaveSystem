<?php use_javascript(plugin_web_path('orangehrmPayrollPlugin', 'js/viewPayrollConfigurationSuccess.js')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmPayrollPlugin', 'css/viewPayrollConfigurationSuccess.css')); ?>

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

        <form id="frmPayrollConfiguration" method="post" action="<?php echo url_for('admin/savePayrollConfiguration'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <fieldset>
                <ol>

                    <li class="long">
                        <label for="payroll_configuration_epf_percentage"><?php echo $form['epf_percentage']->renderLabel() ?></label>
                        <?php echo $form['epf_percentage']->render(array("class" => "editable")); ?>
                    </li>
                    <li class="long">
                        <label for="payroll_configuration_etf_percentage"><?php echo $form['etf_percentage']->renderLabel() ?></label>
                        <?php echo $form['etf_percentage']->render(array("class" => "editable")); ?>
                    </li>
<!--                    <li class="long">-->
<!--                        <label for="tax_bracket_percentage">--><?php //echo $form['percentage']->renderLabel() ?><!--</label>-->
<!--                        --><?php //echo $form['percentage']->render(array("class" => "editable")); ?>
<!--                    </li>-->

                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

            </fieldset>
            <div class="formbuttons">
                <?php if ($showEditbutton): ?>
                    <input type="submit" class="plainbtn" id="btnSave" value="<?php echo 'Edit'; ?>" />
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

    var edit = '<?php echo __js('Edit'); ?>';
    var save = '<?php echo __js('Save'); ?>';
    var lang_processing = '<?php echo __js('Processing'); ?>';
    var lang_Required = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var lang_validSalaryRange = '<?php echo __js("Should be higher than Lower Boundry"); ?>';
    var lang_overlappingTaxBracket = '<?php echo __js("Overlapping with existing tax bracket"); ?>';
    var lang_AlreadyExists = '<?php echo __js(ValidationMessages::ALREADY_EXISTS); ?>';
    var lang_LengthExceeded_60 = '<?php echo __js(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 60)); ?>';

    var url_TaxBracketList = '<?php echo url_for('admin/viewTaxBracketList'); ?>';
    var url_CheckSalaryTypeNameNotExist = '<?php echo url_for('admin/checkSalaryTypetNameNotExistAjax'); ?>';
    var url_TaxBracketBoundsNotExist = '<?php echo url_for('admin/checkTaxBracketBoundsNotExistAjax'); ?>';
    var lang_salaryShouldBeNumeric = '<?php echo __js("Should be a positive number"); ?>';
</script>