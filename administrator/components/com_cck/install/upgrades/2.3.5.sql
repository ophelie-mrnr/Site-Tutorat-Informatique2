
INSERT IGNORE INTO `#__cck_core_fields` (`id`, `title`, `name`, `folder`, `type`, `description`, `published`, `label`, `selectlabel`, `display`, `required`, `validation`, `defaultvalue`, `options`, `options2`, `minlength`, `maxlength`, `size`, `cols`, `rows`, `ordering`, `sorting`, `divider`, `bool`, `location`, `extended`, `style`, `script`, `bool2`, `bool3`, `bool4`, `bool5`, `bool6`, `bool7`, `bool8`, `css`, `attributes`, `storage`, `storage_location`, `storage_table`, `storage_field`, `storage_field2`, `storage_params`, `storages`, `checked_out`, `checked_out_time`) VALUES
(295, 'Core Module Style', 'core_module_style', 3, 'select_simple', '', 0, 'Style', 'Select', 3, '', '', '', 'None=none||Outline=outline||Rounded=rounded||Table=table||Xhtml=xhtml', '', 0, 255, 32, 0, 0, 0, 0, '', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', 'dev', '', '', 'style', '', '', '', 0, '0000-00-00 00:00:00');

UPDATE `#__cck_core_fields` SET `options` = 'Resized=optgroup||Crop=crop||Stretch=stretch||Resized Dynamic=optgroup||Max Fit=maxfit||Stretch=stretch_dynamic' WHERE `id` = 120;
UPDATE `#__cck_core_fields` SET `options` = 'No Process=0||Resized=optgroup||Crop=crop||Stretch=stretch||Resized Dynamic=optgroup||Max Fit=maxfit||Stretch=stretch_dynamic' WHERE `id` = 117;