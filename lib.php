<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library functions for the webworkcsv report.
 *
 * @package   report_webworkcsv
 * @copyright 2016 Joe Bacal, Smith College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//we need this function as moodle will construct a call to it when we build results page
function report_webworkcsv_extend_navigation_course($navigation, $course, $context) {
  if (has_capability('report/webworkcsv:view', $context)) {
    $url = new moodle_url('/report/webworkcsv/index.php', array('id'=>$course->id));
    $navigation->add(get_string('pluginname', 'report_webworkcsv'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
  }
}
