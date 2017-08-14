<?php

class WebworkCsv {

	public function __construct($id){
		global $DB;
		$this->courseobj = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
		$this->coursecontext = context_course::instance($this->courseobj->id);
		$this->userlist = get_enrolled_users($this->coursecontext, '');
		$this->shortname = $this->courseobj->shortname;
		echo '<pre>';
		var_dump($this->shortname);
		echo '</pre>';
		$this->errorText = 'No errors detected.';
	}

	function setup_page_and_access(){
		global $PAGE;
		// echo '<pre>';
		// var_dump($this->courseobj);
		// echo '</pre>';
		require_login($this->courseobj);
		$PAGE->set_url('/report/webworkcsv/index.php', array('id' => $this->courseobj->id));
		$returnurl = new moodle_url('/course/view.php', array('id' => $this->courseobj->id));
		require_capability('report/webworkcsv:view', $this->coursecontext);
		$PAGE->set_title($this->courseobj->shortname .': '. get_string('webworkcsv' , 'report_webworkcsv'));
		$PAGE->set_heading($this->courseobj->fullname);
	}

	function build_student_records_stream_content(){
		$this->students_list = array('Term%20Code%2CCRN%2CStudent%20ID%2CCourse%2CFinal%20Grade%0A');

		foreach ( $this->userlist as $person ) {
			//each row in csv is going to be a record array
			$student_record = array();

			//get the students final grade
			$studentkey = $person->id;

			$final_grade_obj = grade_get_course_grades($this->courseobj->id, $studentkey);
			$final_grade_num = $final_grade_obj->grades[$studentkey]->grade;
			$final_grade_ltr = $final_grade_obj->grades[$studentkey]->str_grade;

			//build up the record
			//array_push($student_record, $this->crn);

			if ( $this->custom_student_id_field == '' ){
				array_push($student_record, $person->idnumber);
			}

			array_push($student_record, $this->courseobj->shortname);
			array_push($student_record, $final_grade_ltr);
			//var_dump($student_record);
			//compact each record into a comma-separated string
			$record_string = implode('%2C', $student_record);
			//put the record in the records list
			array_push($this->students_list, $record_string . '%0A');
		}
	}

	function render_csv_download_link(){
		$open_csv_link = '<hr><a class="btn" href="';
		$streamer = 'data:application/octet-stream,';
		$records_as_string = implode('', $this->students_list);
		$file_name = 'final_grades_' . $this->courseobj->shortname . '_' . date("Y_m_d_His") . '.csv';
		$close_csv_link = '" download="'. $file_name .'">Download CSV of Final Grades</a>';
		echo $open_csv_link . $streamer . $records_as_string . $close_csv_link;
	}
}
