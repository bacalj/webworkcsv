<?php

class WebworkCsv {

	public function __construct($id){
		global $DB;
		$this->courseobj = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
		$this->coursecontext = context_course::instance($this->courseobj->id);
		$this->userlist = get_enrolled_users($this->coursecontext, '');
		$this->shortname = $this->courseobj->shortname;
		$this->errorText = 'No errors detected.';
		$this->studentlist = get_role_users(5, $this->coursecontext);
		//extract section number from shortname
		$this->course_section_num = substr($this->shortname, 8, 2);
		// echo '<pre>';
		// 	print_r($this->shortname);
		// echo '</pre>';
	}

	function setup_page_and_access(){
		global $PAGE;
		require_login($this->courseobj);
		$PAGE->set_url('/report/webworkcsv/index.php', array('id' => $this->courseobj->id));
		$returnurl = new moodle_url('/course/view.php', array('id' => $this->courseobj->id));
		require_capability('report/webworkcsv:view', $this->coursecontext);
		$PAGE->set_title($this->courseobj->shortname .': '. get_string('webworkcsv' , 'report_webworkcsv'));
		$PAGE->set_heading($this->courseobj->fullname . ': Download export for WebWork');
	}

	function build_student_records_stream_content(){
		//doing this so we can create the student_id%2Clast_name%2C sort of thing
		$srcstrings = array(
			'student_id',
			'last_name',
			'first_name',
			'status',
			'comment',
			'section',
			'recitation',
			'email_address',
			'user_id',
			'password',
			'permission'
		);


		$concatForCsv = implode('%2C', $srcstrings ) . '%0A';
		$this->students_list = array($concatForCsv);

		foreach ( $this->studentlist as $person ) {

			//each row in csv is going to be a record array
			$student_record = array();

			//if the username is an email address we will just want the name part
			if (strpos($person->username, '@') !== FALSE ){
				$username_arr = explode('@', $person->username);
				$username_substring = $username_arr[0];
			}

			//if not then we'll keep the usual username
			else {
				$username_substring = $person->username;
			}

			//print_r($username_substring);


			//load the values into the array
			array_push($student_record, $person->idnumber);
			array_push($student_record, $person->lastname);
			array_push($student_record, $person->firstname);
			array_push($student_record, NULL);
			array_push($student_record, NULL);
			//array_push($student_record, $this->courseobj->shortname);
			array_push($student_record, $this->course_section_num);
			array_push($student_record, NULL);
			array_push($student_record, $person->email);
			array_push($student_record, $username_substring);
			array_push($student_record, NULL);
			array_push($student_record, NULL);

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
		$file_name = 'webwork_' . $this->courseobj->shortname . '_' . date("Y_m_d_His") . '.lst';
		$close_csv_link = '" download="'. $file_name .'">Download .lst for WebWork</a>';
		echo $open_csv_link . $streamer . $records_as_string . $close_csv_link;
	}
}
