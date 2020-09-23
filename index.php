<?php
// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_deptrpts
 * @copyright  mallamma<mallamma@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('lib.php');
global $DB, $USER, $SESSION;  
require_login(true);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/deptrpts/index.php');
$title = get_string('title','local_deptrpts');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/custom.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/typeahead.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/chart.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/jquery.dataTables.min.js'),true);
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/deptrpts/jquery.dataTables.min.css'));
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/dataTables.buttons.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/buttons.print.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/buttons.colVis.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/buttons.flash.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/buttons.html5.min.js'),true);
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/deptrpts/css/buttons.dataTables.min.css'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/jszip.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/pdfmake.min.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/vfs_fonts.js'),true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/deptrpts/js/demo.js'));


echo $OUTPUT->header();

//here I am getting all dropdon data.
//Manju: creating the container here.
$html='';
$html .= html_writer::start_div('container-fluid');
$html .= html_writer::start_div('row');
$html .= html_writer::start_div('col-md-2 bg-dark text-white p-2 text-center', array('id'=>'leftsidefilter'));
$html .='<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne" >
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <i class="fa fa-bar-chart" aria-hidden="true"></i>&nbsp;
          '.get_string('site','local_deptrpts').'
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">';
//start date.
$html .= html_writer::start_div('form-group row text-center');
$html .= html_writer::start_tag('label', array('for'=>'example-date-input','class'=>'col-form-label'));
$html .= get_string('startdate','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('input', array('id'=>'start-date-input','class'=>'form-control','type'=>'date','onclick'=>'startdatedropdown();'));
$html .= html_writer::end_div();
$html .= html_writer::end_div();
//end date.
$html .= html_writer::start_div('form-group row');
$html .= html_writer::start_tag('label', array('for'=>'exmple-date-input','class'=>'col-form-label'));
$html .= get_string('enddate','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('input', array('id'=>'end-date-input','class'=>'form-control','type'=>'date','onclick'=>'enddatedropdown();'));
$html .= html_writer::end_div();
$html .= html_writer::end_div();
//creating category dropdown.
//get all the main categories present in site.
$categories=$DB->get_records_sql('SELECT id, name FROM {course_categories} WHERE visible = 1');
$html .= html_writer::start_div('form-group row');
$html .= html_writer::start_tag('label', array('for'=>'example-select-category','class'=>'col-form-label'));
$html .= get_string('selectcategory','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('select', array('id'=>'category-drop','class'=>'custom-select', 'onchange'=>'categorydropdown();'));
$html .= html_writer::start_tag('option');
$html .= get_string('selectcategory','local_deptrpts');
$html .= html_writer::end_tag('option');
foreach ($categories as $category) {
$html .= html_writer::start_tag('option',array('value'=>$category->id));
$html .= $category->name;
$html .= html_writer::end_tag('option');
}
$html .= html_writer::end_tag('select');
$html .= html_writer::end_div();
$html .= html_writer::end_div();
//creating category dropdown end.
//creating department dropdown.
//get all the unique departments from user table.
// $departments=$DB->get_records_sql('SELECT DISTINCT(department) FROM {user}');
// $html .= html_writer::start_div('form-group row');
// $html .= html_writer::start_tag('label', array('for'=>'example-select-department','class'=>'col-form-label'));
// $html .= get_string('selectdepartment','local_deptrpts');
// $html .= html_writer::end_tag('label');
// $html .= html_writer::start_div('col-12');
// $html .= html_writer::start_tag('select', array('id'=>'department-drop','class'=>'custom-select','onchange'=>'departmentdrop();'));
// $html .= html_writer::start_tag('option');
// $html .= get_string('selectdepartment','local_deptrpts');
// $html .= html_writer::end_tag('option');
// $count=1;
// foreach ($departments as $department) {
// 	if($department->department != '' ){
// 		$html .= html_writer::start_tag('option',array('value'=>$count));
// 		$html .= $department->department;
// 		$html .= html_writer::end_tag('option');
// 		$count++;
// 	}
// }
// $html .= html_writer::end_tag('select');
// $html .= html_writer::end_div();
// $html .= html_writer::end_div();

// //role dropdown.
// $roles=$DB->get_records_sql('SELECT id,shortname FROM {role}');
// $html .= html_writer::start_div('form-group row');
// $html .= html_writer::start_tag('label', array('for'=>'example-select-role','class'=>'col-form-label mdb-select md-form','searchable'=>'Search here..'));
// $html .= get_string('selectrole','local_deptrpts');
// $html .= html_writer::end_tag('label');
// $html .= html_writer::start_div('col-12');
// $html .= html_writer::start_tag('select', array('id'=>'role-drop','class'=>'custom-select','onchange'=>'roledropdown();'));
// $html .= html_writer::start_tag('option');
// $html .= get_string('selectrole','local_deptrpts');
// $html .= html_writer::end_tag('option');
// foreach ($roles as $rl) {
// $html .= html_writer::start_tag('option',array('value'=>$rl->id));
// $html .= $rl->shortname;
// $html .= html_writer::end_tag('option');
// }
// $html .= html_writer::end_tag('select');
// $html .= html_writer::end_div();
// $html .= html_writer::end_div();
//end here ..

//city dropdown.
$city=$DB->get_records_sql('SELECT  DISTINCT city FROM {user}');
$html .= html_writer::start_div('form-group row');
$html .= html_writer::start_tag('label', array('for'=>'example-select-role','class'=>'col-form-label mdb-select md-form','searchable'=>'Search here..'));
$html .= get_string('selectcity','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('select', array('id'=>'city-drop','class'=>'custom-select','onchange'=>'citydropdown();'));
$html .= html_writer::start_tag('option');
$html .= get_string('selectcity','local_deptrpts');
$html .= html_writer::end_tag('option');
foreach ($city as $ct) {
$html .= html_writer::start_tag('option',array('value'=>$ct->city));
$html .= $ct->city;
$html .= html_writer::end_tag('option');
}
$html .= html_writer::end_tag('select');
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html .='</div>
    </div>
  </div><div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
          '.get_string('user','local_deptrpts').'
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">';
// end here..
//-------------------------------------------
$html .= html_writer::start_div('form-group row');
$html .= html_writer::start_tag('label', array('for'=>'example-select-user','class'=>'col-form-label mdb-select md-form','searchable'=>'Search here..'));
$html .= get_string('selectuser','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('input', array('type'=>'text','name'=>'user','id'=>'usersearch','class'=>'typeahead', 'onchange'=>'userSelect();'));
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html .='</div>
    </div>

  </div>
<!-- course dropdown -->
<div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
          '.get_string('course','local_deptrpts').'
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">';
// end here..
//-------------------------------------------
$html .= html_writer::start_div('form-group row');
$html .= html_writer::start_tag('label', array('for'=>'example-select-course','class'=>'col-form-label mdb-select md-form','searchable'=>'Search here..'));
$html .= get_string('selectcourses','local_deptrpts');
$html .= html_writer::end_tag('label');
$html .= html_writer::start_div('col-12');
$html .= html_writer::start_tag('input', array('type'=>'text','name'=>'course','id'=>'searchcourse','class'=>'typeahead', 'onchange'=>'courseSelect();'));
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html .='</div>
    </div>

  </div>
  </div>';
$html .= html_writer::end_div();
$html .= html_writer::start_div('col-md-10 p-2',array('id'=>'rightsidesection'));
$html .=html_writer::start_div('row');
$html .=html_writer::start_div('col-md-12', array('id'=>'ajaxresult'));
$html .=html_writer::end_div();
$html .=html_writer::end_div();
$html .=html_writer::start_div('row');
$html .=html_writer::start_div('col-md-12');
$html .=get_userdatatable($stime=null,$etime=null,$category=null,$department=null,$role=null,$city=null);
$html .=html_writer::end_div();
$html .=html_writer::end_div();
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html.= "<script>
            $('#usertable').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'excel', 'pdf'
            ]
        });
          </script>";

echo $html;

echo $OUTPUT->footer();



