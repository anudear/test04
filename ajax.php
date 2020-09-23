<?php
require_once('../../config.php');
require_once('lib.php');

// here fetch the data from post method that data storedin single variables..
$startdate = optional_param('startdate','',PARAM_RAW);
$enddate = optional_param('enddate','',PARAM_RAW);
$category = optional_param('category','',PARAM_INT);
$department = optional_param('department','',PARAM_RAW);
$role = optional_param('role','',PARAM_RAW);
$city = optional_param('city','',PARAM_RAW);
$userquery = optional_param('query','',PARAM_RAW);
$coursename = optional_param('coursename','',PARAM_RAW);
$courseid = optional_param('courseid','',PARAM_RAW);
$userid = optional_param('userid','',PARAM_RAW);
$load = optional_param('load','',PARAM_INT);
if(!empty($startdate)||!empty($enddate)||!empty($category)||!empty($department)||!empty($role)||!empty($city)){
	$stime=strtotime($startdate);
	$etime=strtotime($enddate);
	//echo filter_course_result($stime,$etime,$category,$department,$role,$city);
	echo rightside_ajax_output($stime,$etime,$category,$department,$role,$city);
	
}
//here check the data is present or not..
if(!empty($userquery)){
	$users=$DB->get_records_sql('SELECT id, firstname, lastname FROM {user} WHERE firstname LIKE "%'.$userquery.'%" OR lastname LIKE "%'.$userquery.'%" ');
	$userfield=[];
	foreach  ($users as $ur){
		$userfield[$ur->id]=$ur->firstname.' '.$ur->lastname;
	}
	echo json_encode($userfield);
}

//here i am creating course dropdown
if(!empty($coursename)){
	$courses=$DB->get_records_sql('SELECT id, fullname FROM {course} WHERE fullname LIKE "%'.$coursename.'%"');
	$coursefield=[];
	foreach  ($courses as $course){
		//print_object($course);die;
		$coursefield[$course->id]=$course->fullname;
		//print_object($coursefield);die;
	}
	echo json_encode($coursefield);
}

if(!empty($userid)){
	$allusers=$DB->get_records('user');
	foreach ($allusers as $singleuser) {
		$fullname=$singleuser->firstname.' '.$singleuser->lastname;
		if($fullname==$userid){
	    $userid=$singleuser->id;
                }
			}
		echo filter_ajax_html_otput($userid);
}

//--------------------------------

if(!empty($courseid)){
		echo course_filter_ajax_html_output($courseid);
	}

if(!empty($load)){
  	echo rightside_ajax_output(null,null,null,null,null,null);
//-------------------------------------------
}


