<?php

//$ADMIN->add('reports', new admin_externalpage('reportwebworkcsv', get_string('webworkcsv', 'report_webworkcsv'), "$CFG->wwwroot/report/webworkcsv/index.php"));

//BTW: args for settings maker thing are: ($name, $visiblename, $description, $defaultsetting, array of options in dropdown)
//U can use, e.g.: admin_setting_configmultiselect, admin_setting_configselect, admin_setting_configtext

$settings->add(
	new admin_setting_configselect(
		'report_webworkcsv/course_id_pattern', //name
		get_string('course_id_pattern', 'report_webworkcsv'), //visible name
		get_string('course_id_pattern_desc', 'report_webworkcsv'), //description
		'default', // string or int default settings
		array(
			'.' => '[CRN].[TERMCODE]',
			'-' => '[CRN]-[TERMCODE]'
		)
	)
);

$settings->add(
	new admin_setting_configtext(
		'report_webworkcsv/custom_student_id_field', //name
		get_string('bcsv_custom_student_id_field', 'report_webworkcsv'), //visible name
		get_string('bcsv_custom_student_id_field_desc', 'report_webworkcsv'),
		'empty')
);
