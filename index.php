<?php

//dependencies
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->dirroot . '/grade/querylib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

include('webworkcsv.php');
global $DB;

$id = required_param('id', PARAM_INT);
$wbwkcsv = new WebworkCsv($id);

$wbwkcsv->setup_page_and_access();

//$wbwkcsv->extract_term_and_crn();

//render
echo $OUTPUT->header();
//echo '<pre>';
  //$wbwkcsv->display_record_preview();
$wbwkcsv->build_student_records_stream_content();
echo '<hr>';
echo '<h2>Verify grade data</h2>';
echo '<p>The table below is for data verification only. The actual csv feilds will be: <code>Term Code,CRN,Student ID,Course,Final Grade</code></p>';

if ( $wbwkcsv->errorText !== 'No errors detected.' ){
  echo $wbwkcsv->errorText;
} else {
  // echo '<table style="width:50%;">';
  // $wbwkcsv->display_record_preview();
  // echo '</table>';
  $wbwkcsv->render_csv_download_link();
}


echo $OUTPUT->footer();
