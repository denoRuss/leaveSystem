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
('Payroll', NULL , NULL, '1', '1200', NULL, '1');

set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Manage Salary Detail', @mange_salary_detail_screen_id , @parent_menu_id, 2, '100', null, 1),
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
  (@admin_role_id, @data_group_id, 1, 1, 1, 1, 1);
