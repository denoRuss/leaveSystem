-- this script is used to hide custom field & PIM configuration menus. used to revert any changed done for debugging
UPDATE `ohrm_menu_item` SET  `status`= 0 WHERE ID IN (31,33);