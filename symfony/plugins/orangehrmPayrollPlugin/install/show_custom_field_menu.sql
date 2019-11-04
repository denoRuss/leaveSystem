-- this script is used to unhide custom field & PIM configuration menus. only for debugging purpose
UPDATE `ohrm_menu_item` SET  `status`= 1 WHERE ID IN (31,33);