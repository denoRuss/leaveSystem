set @module_id := (SELECT id FROM ohrm_module WHERE name = 'admin');
set @admin_role_id := (SELECT id FROM ohrm_user_role WHERE name = 'Admin');




INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('Manage Salary Detail', @module_id , 'mangeSalaryDetail'),
('Employee Salary List', @module_id , 'employeeSalaryList'),
('Make Payment', @module_id , 'makePayment'),
('Generate Payslip', @module_id , 'generatePaySlip');


set @mange_salary_detail_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'mangeSalaryDetail');
set @employee_salary_list_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'employeeSalaryList');
set @make_payment_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'makePayment');
set @generate_payslip_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'generatePaySlip');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Payroll', @make_payment_screen_id , NULL, '1', '1200', NULL, '1');

set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Manage Salary Detail', @mange_salary_detail_screen_id , @parent_menu_id, 2, '100', null, 0),
('Employee Salary List', @employee_salary_list_screen_id, @parent_menu_id, 2, '200', null, 1),
('Make Payment', @make_payment_screen_id, @parent_menu_id, 2, '300', null, 1),
('Generate Payslip', @generate_payslip_screen_id, @parent_menu_id, 2, '400', null, 1);



INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @mange_salary_detail_screen_id, 1),
(@admin_role_id, @employee_salary_list_screen_id, 1),
(@admin_role_id, @make_payment_screen_id, 1),
(@admin_role_id, @generate_payslip_screen_id, 1);



INSERT INTO ohrm_data_group (name, description, can_read, can_create, can_update, can_delete) VALUES
  ('Payroll', 'Employee Payroll', 1, 1, 1, 1);

SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');

INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @mange_salary_detail_screen_id, 1),
  (@data_group_id, @employee_salary_list_screen_id, 1),
  (@data_group_id, @make_payment_screen_id, 1),
  (@data_group_id, @generate_payslip_screen_id, 1);

INSERT INTO ohrm_user_role_data_group (user_role_id, data_group_id, can_read, can_create, can_update, can_delete, self) VALUES
  (@admin_role_id, @data_group_id, 1, 1, 1, 1, 0);




-- Salary Related QUERY

CREATE TABLE IF NOT EXISTS `dk_salary_type` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL UNIQUE,
  `jobtitle_id` INT NOT NULL ,
  `monthly_basic` double default null,
  `other_allowance` double default null,
  `monthly_basic_tax` double default null,
  `monthly_nopay_leave` double default null,
  `monthly_epf_deduction` double default null,
  `monthly_etf_deduction` double default null,
  CONSTRAINT FOREIGN KEY (`jobtitle_id`) REFERENCES `ohrm_job_title` (`id`) ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE IF NOT EXISTS `dk_employee_salary` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `emp_number` INT(7) NOT NULL,
    `monthly_basic` double default null,
  `other_allowance` double default null,
  `monthly_basic_tax` double default null,
  `monthly_nopay_leave` double default null,
  `monthly_epf_deduction` double default null,
  `monthly_etf_deduction` double default null,
    CONSTRAINT FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS `dk_employee_salary_history` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `emp_number` INT(7) NOT NULL,
    `monthly_basic` double default null,
  `other_allowance` double default null,
  `monthly_basic_tax` double default null,
  `monthly_nopay_leave` double default null,
  `monthly_epf_deduction` double default null,
  `monthly_etf_deduction` double default null,
  `total_earning` double default null,
  `total_deduction` double default null,
  `total_netsalary` double default null,
  `month` INT default null,
  `year` INT default null,
    CONSTRAINT FOREIGN KEY (`emp_number`) REFERENCES `hs_hr_employee` (`emp_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE IF NOT EXISTS `dk_tax_bracket` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `lower_bound` double default null,
  `upper_bound` double default null,
  `percentage` double default null

) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


-- Adding Salary type screen
set @module_id := (SELECT id FROM ohrm_module WHERE name = 'admin');
set @admin_role_id := (SELECT id FROM ohrm_user_role WHERE name = 'Admin');



INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('Salary Type', @module_id , 'viewSalaryTypeList');

set @view_salary_type_list_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewSalaryTypeList');
set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Salary Types', @view_salary_type_list_screen_id , @parent_menu_id, 2, '100', null, 0);


INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_salary_type_list_screen_id, 1);



SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_salary_type_list_screen_id, 1);


-- Hide Unused menus
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`id` = 5;
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`id` = 7;
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`id` = 11;
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`id` = 13;
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`id` = 14;

UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`name` = 'leave';
UPDATE `ohrm_module` SET `status` = '0' WHERE `ohrm_module`.`name` = 'directory';



-- Adding Tax bracket screen
set @module_id := (SELECT id FROM ohrm_module WHERE name = 'admin');
set @admin_role_id := (SELECT id FROM ohrm_user_role WHERE name = 'Admin');



INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('Tax Brackets', @module_id , 'viewTaxBracketList');

set @view_tax_bracket_list_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewTaxBracketList');
set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Tax Brackets', @view_tax_bracket_list_screen_id , @parent_menu_id, 2, '100', null, 1);


INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_tax_bracket_list_screen_id, 1);



SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_tax_bracket_list_screen_id, 1);



-- Adding Configuration screen
set @module_id := (SELECT id FROM ohrm_module WHERE name = 'admin');
set @admin_role_id := (SELECT id FROM ohrm_user_role WHERE name = 'Admin');



INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('Configuration', @module_id , 'viewPayrollConfiguration');

set @view_payroll_configuration_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewPayrollConfiguration');
set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Settings', @view_payroll_configuration_screen_id , @parent_menu_id, 2, '100', null, 1);


INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_payroll_configuration_screen_id, 1);



SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_payroll_configuration_screen_id, 1);


--   Adding default payroll configuration values

INSERT INTO `hs_hr_config` (`key`, `value`) VALUES ('payroll.epf_percentage', '0');
INSERT INTO `hs_hr_config` (`key`, `value`) VALUES ('payroll.company_epf_percentage', '0');
INSERT INTO `hs_hr_config` (`key`, `value`) VALUES ('payroll.etf_percentage', '0');
INSERT INTO `hs_hr_config` (`key`, `value`) VALUES ('payroll.nopay_leave_type_id', -1);
INSERT INTO `hs_hr_config` (`key`, `value`) VALUES ('payroll.nopay_leave_deduction', 0);