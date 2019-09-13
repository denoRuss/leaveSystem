<div class="box searchForm toggableForm" id="employee-information">
    <div class="head">
        <h1><?php echo __("Employee ETF Report") ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>

        <form id="search_form" name="frmEmployeeSearch" method="post" action="<?php echo url_for('admin/viewEtfReport'); ?>">

            <fieldset>

                <ol>
                    <?php echo $form->render(); ?>
                </ol>

                <input type="hidden" name="hdnAction" id="hdnAction" value="download" />

                <p>
                    <input type="button" id="viewBtn" value="<?php echo __("View") ?>" name="view" />
                    <!--                    <input type="button" class="reset" id="resetBtn" value="--><?php //echo __("Reset") ?><!--" name="_reset" />-->
                    <input type="button" id="downloadBtn" value="<?php echo __("Download") ?>" name="download" />
                </p>

            </fieldset>

        </form>

    </div> <!-- inner -->

    <a href="#" class="toggle tiptip" title="<?php echo __(CommonMessages::TOGGABLE_DEFAULT_MESSAGE); ?>">&gt;</a>

</div> <!-- employee-information -->


<script>
    var etfGenerateUrl = '<?php echo $etfGenerateUrl; ?>';
    var lang_Required = '<?php echo __js(ValidationMessages::REQUIRED); ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_dateError = '<?php echo __("To date should be after from date") ?>';



    $(document).ready(function (e) {

        $("#viewBtn").click(function (e) {

            if($("#search_form").valid()){
                var fromDate = $("#calFromDate").val();
                var toDate = $("#calToDate").val();
                var checkNo = $("#report_checkNo").val();
                window.open(etfGenerateUrl+'?from='+fromDate+'&to='+toDate+'&checkNo='+checkNo);
            }

        });

        $("#downloadBtn").click(function (e) {

            $("#hdnAction").val('download');
            $('#search_form').submit();
        });

        var validator = $("#search_form").validate(
            {
                rules: {
                    'report[checkNo]': {
                        required: true,
                    },
                    'report[calFromDate]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        }
                    },
                    'report[calToDate]': {
                        required: true,
                        valid_date: function() {
                            return {
                                required: true,
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat
                            }
                        },
                        date_range: function() {
                            return {
                                format:datepickerDateFormat,
                                displayFormat:displayDateFormat,
                                fromDate:$("#calToDate").val()
                            }
                        }
                    }
                },
                messages: {
                    'report[checkNo]': {
                        required: lang_Required,
                    },
                    'report[calFromDate]': {
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate
                    },
                    'report[calToDate]':{
                        required:lang_invalidDate,
                        valid_date: lang_invalidDate ,
                        date_range: lang_dateError
                    }
                },
            }
        );
    });
</script>
