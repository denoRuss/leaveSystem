-- hide "Admin -Pay Grades" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 8;

-- hide "Admin -Work Shifts" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 11;

-- hide "Admin -Licenses" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 19;

-- hide "Admin -Languages" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 20;

-- hide "Admin -Nationalities" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 22;

-- hide "Admin -Configuration" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id`  IN (23,24,25,27,28,94,95);

-- hide "Pim  -Configuration" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `id` IN (32,33,34,35,36);

-- hide "Pim  -Reports" menu
UPDATE `ohrm_menu_item` SET `status` = '0' WHERE `ohrm_menu_item`.`id` = 39;

-- hide "Pim  -Immigration" menu
UPDATE `ohrm_user_role_data_group` SET `can_read` =0 WHERE `data_group_id` IN ( 13,14,15);

-- hide "Pim  -Qualifications" menu
UPDATE `ohrm_user_role_data_group` SET `can_read` =0 WHERE `data_group_id` IN ( 29,30,31,32,33,34,35);

-- hide "Pim  -Memberships" menu
UPDATE `ohrm_user_role_data_group` SET `can_read` =0 WHERE `data_group_id` IN ( 36,37,38);

-- hide "Pim  -Dependents" menu
UPDATE `ohrm_user_role_data_group` SET `can_read` =0 WHERE `data_group_id` IN ( 10,11,12);


-- turn off password check
UPDATE `hs_hr_config` SET `value` = 'off' WHERE `hs_hr_config`.`key` = 'authentication.enforce_password_strength';
UPDATE `hs_hr_config` SET `value` = 'veryWeak' WHERE `hs_hr_config`.`key` = 'authentication.default_required_password_strength';