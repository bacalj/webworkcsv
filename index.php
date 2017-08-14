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
$bcsv = new webworkcsv($id);

$bcsv->setup_page_and_access();

$bcsv->extract_term_and_crn();

//render
echo $OUTPUT->header();
//echo '<pre>';
  //$bcsv->display_record_preview();
$bcsv->build_student_records_stream_content();
echo '<hr>';
echo '<h2>Verify grade data</h2>';
echo '<p>The table below is for data verification only. The actual csv feilds will be: <code>Term Code,CRN,Student ID,Course,Final Grade</code></p>';

if ( $bcsv->errorText !== 'No errors detected.' ){
  echo $bcsv->errorText;
} else {
  echo '<table style="width:50%;">';
  $bcsv->display_record_preview();
  echo '</table>';
  $bcsv->render_csv_download_link();
}


echo $OUTPUT->footer();
