-- change theme to kpk01
UPDATE `setting` SET `setting_value` = 'a:2:{s:5:\"theme\";s:6:\"custom\";s:3:\"css\";s:25:\"template/custom/style.css\";}' WHERE `setting`.`setting_id` = 3;


-- add menu to module
INSERT INTO `mst_module` (`module_id`, `module_name`, `module_path`, `module_desc`) VALUES
(9, 'publikasi_lokal', 'korupsi', 'Katalog induk Konten Lokal Korupsi'),
(13, 'reporting_local', 'reporting_local', 'modul reporting khusus konten lokal'),
(14, 'newsletter', 'newsletter', 'module newsletter');

-- set menu admin
-- copy folder 
-- slim8/admin/modules/korupsi          to slim9/admin/modules/korupsi
-- slim8/admin/modules/newsletter       to slim9/admin/modules/newsletter
-- slim8/admin/modules/reporting_local  to slim9/admin/modules/reporting_local
