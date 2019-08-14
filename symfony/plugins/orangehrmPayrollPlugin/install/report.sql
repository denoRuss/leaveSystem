-- Adding Report screens
set @module_id := (SELECT id FROM ohrm_module WHERE name = 'admin');
set @admin_role_id := (SELECT id FROM ohrm_user_role WHERE name = 'Admin');


set @parent_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Payroll');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Report', null , @parent_menu_id, 2, '500', null, 1);

set @payroll_report_menu_id := (SELECT id FROM ohrm_menu_item WHERE menu_title = 'Report' AND parent_id = @parent_menu_id);


INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('EPF Report', @module_id , 'viewEpfReport');

set @view_epf_report_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewEpfReport');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('EPF Report', @view_epf_report_screen_id , @payroll_report_menu_id, 3, '100', null, 1);

INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_epf_report_screen_id, 1);


SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_epf_report_screen_id, 1);


-- ETP Report

INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('ETF Report', @module_id , 'viewEtfReport');

set @view_etf_report_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewEtfReport');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('ETF Report', @view_etf_report_screen_id , @payroll_report_menu_id, 3, '200', null, 1);

INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_etf_report_screen_id, 1);


SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_etf_report_screen_id, 1);



-- Banking Letter
INSERT INTO ohrm_screen (name, module_id, action_url) VALUES
('Banking Letter', @module_id , 'viewBankingLetter');

set @view_banking_letter_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'viewBankingLetter');

INSERT INTO ohrm_menu_item (menu_title, screen_id, parent_id, level, order_hint, url_extras, status) VALUES
('Banking Letter', @view_banking_letter_screen_id , @payroll_report_menu_id, 3, '300', null, 1);


INSERT INTO ohrm_user_role_screen (user_role_id,screen_id, can_read) VALUES
(@admin_role_id, @view_banking_letter_screen_id, 1);

SET @data_group_id := (SELECT id FROM ohrm_data_group WHERE name = 'Payroll');
INSERT INTO ohrm_data_group_screen (data_group_id, screen_id, permission) VALUES
  (@data_group_id, @view_banking_letter_screen_id, 1);