
<?php
global $CFG,$DB;
require_once($CFG->libdir.'/completionlib.php');
require_once("$CFG->libdir/gradelib.php");
require_once("$CFG->dirroot/lib/completionlib.php");
require_once($CFG->dirroot . '/grade/querylib.php');

//This function will return number of users enrolled into this course.- paarameters with returning function.


function all_enrolled_usersdata($courseid)
{
	global $DB,$CFG;
	$allenrolleduser=enrol_get_course_users($courseid);
	$listofusers =[];
	foreach ($allenrolleduser as $user) {
		$listofusers[] = $user->id;
	}
	sort($listofusers);
	return  $listofusers;
}
function get_enroled_userdata($courseid)
{
	global $DB,$CFG;
	$allenrolleduser= enrol_get_course_users($courseid);

	foreach ($allenrolleduser as $user) {
		$listofusers[] = array($user->id,$user->uetimecreated);
	}
	if(!empty($listofusers)){
		sort($listofusers);
		return  $listofusers;
	}


}
//This function will return number of users enrolled into this course.- paarameters with returning function.
function all_enrolled_usersdata_date($courseid)
{
	global $DB,$CFG;
	$allenrolleduser=enrol_get_course_users($courseid);
	$listofusers =[];
	foreach ($allenrolleduser as $user) {
		$listofusers[] = $user->uetimecreated;
	}
	sort($listofusers);
	return  $listofusers;
}
//defining function,here passing the userid.
function user_course_count($userid)
{
	global $DB;
	$course=enrol_get_users_courses($userid);
	return count($course);
}
//here return the no of the counting badge.
function user_badge_count($userid)
{
	global $DB;
	$badge=$DB->get_records_sql("SELECT * FROM {badge_issued} WHERE userid=".$userid."");
	return count($badge);
}
// this function is user certificate.
function user_course_certificate($userid)
{
	global $DB;
	$certificate=$DB->get_records_sql("SELECT * FROM {simplecertificate_issues} WHERE userid=".$userid."");
	return count($certificate);
}

//This function is used to completion of course
function user_course_completion($userid)
{
	global $DB;
	$ret=0;
	$completion=$DB->get_records_sql("SELECT * FROM {course_completions} WHERE userid=".$userid." AND timecompleted IS NOT NULL");
	$ret = count($completion);
	return  $ret;
}

 //This function is used to detail of enrolled course
function get_course_enrolled_info($userid)
{
	global $DB;
	// here getting enrolement time.  
	$information=$DB->get_records_sql("SELECT timecreated FROM {user_enrolments} WHERE userid=".$userid."");
	$abc=[];
	//here getting single value.
	foreach($information as $info){
		$singleinfo=$info->timecreated ;
		$abc[]=date("m",$singleinfo);
	}
	// this function is used to count the array value.
	$months = array_count_values($abc);
	$montharray=[];
	$marray = array('Jan','Feb','Mar','Apl','May','Jun','Jul','Aug','Sep','Act','Nov','Dec');
	for ($i=01; $i < 13; $i++) { 
		if($i <= 9){
			$i = '0'.$i;
		}
  		// this function is used to check whether a specified key is present in an array or not. 
		if (array_key_exists($i,$months)){
			$montharray[$marray[$i-1]] = $months[$i];
		}else{
			$montharray[$marray[$i-1]] = 0;
		}
	}
	return $montharray;
}
//this function is used to completion of course.
function get_course_completion($userid)
{
	global $DB;
	// here getting enrolement time.  
	$information=$DB->get_records_sql("SELECT id,timecompleted FROM {course_completions} WHERE userid=".$userid." AND timecompleted IS NOT NULL");
	$abc=[];
	//here getting single value.
	foreach($information as $info){
		$singleinfo=$info->timecompleted ;
		$abc[]=date("m",$singleinfo);
	}
	// this function is used to count the array value.
	$months = array_count_values($abc);
	$montharray=[];
	$marray = array('Jan','Feb','Mar','Apl','May','Jun','Jul','Aug','Sep','Act','Nov','Dec');
	for ($i=01; $i < 13; $i++) { 
		if($i <= 9){
			$i = '0'.$i;
		}
  		// this function is used to check whether a specified key is present in an array or not. 
		if (array_key_exists($i,$months)){
			$montharray[$marray[$i-1]] = $months[$i];
		}else{
			$montharray[$marray[$i-1]] = 0;
		}
	}
	return $montharray;
}

// this function is used to 
function filter_ajax_html_otput($userid){
	if(!empty($userid)){
		global $DB;
	//get all the required data here.
		$coursecount = user_course_count($userid);
		$badgecount= user_badge_count($userid);
		$certificatecount=user_course_certificate($userid);
		$compltioncount=user_course_completion($userid);
	//creating html part to return.
		$html='';
		$html .= html_writer::start_div('row');
		$html .='<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-primary mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-warning text-center">
		<i class="fa fa-check-square-o" aria-hidden="true"></i>   
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('enrolledcourse','local_deptrpts').'</p>
		<h3>'.$coursecount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-success mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-success">
		<i class="fa fa-certificate" aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('badgesearned','local_deptrpts').'</p>
		<h3>'.$badgecount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-danger mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-danger text-center">
		<i class="fa fa-list" aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('coursecompleted','local_deptrpts').'</p>
		<h3>'.$compltioncount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-info mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-info text-center">
		<i class="fa fa-address-card-o " aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('certificateearned','local_deptrpts').'</p>
		<h3>'.$certificatecount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>';                   
		$infoenroll = get_course_enrolled_info($userid);
		$count = 1;
		$menrol = '';
		foreach ($infoenroll as $mkey => $mvalue) {
			if($count == 1){
				$menrol = $mvalue;
			}else{
				$menrol =$menrol.','.$mvalue;
			}
			$count++;
		}
		$completion=get_course_completion($userid);
		$temp=1;
		$mcomplete='';
		foreach ($completion as $ckey => $cvalue) {
			if($temp == 1){
				$mcomplete = $cvalue;
			}else{
				$mcomplete =$mcomplete.','.$cvalue;
			}
			$temp++;
		}
		$html .= html_writer::end_div();
		$html .= html_writer::start_div('row');
		$html .= html_writer::start_div('col-md-6');
		$html .= html_writer::start_div('card pb-3 m-1');
		$html .= html_writer::start_tag('canvas', array('id'=>'firstchart'));
		$html .= html_writer::end_tag('canvas');
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$html .= html_writer::start_div('col-md-6');
		$html .= html_writer::start_div('card pb-3 m-1');
		$html .= html_writer::start_tag('canvas', array('id'=>'secondchart'));
		$html .= html_writer::end_tag('canvas');
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$ycomplete=get_user_yearly_completion($userid);
		$temp1=1;
		$ydata='';
		$ylabel='';
		foreach ($ycomplete as $ykey => $yvalue) {
			if($temp1 == 1){
				$ydata = $yvalue;
				$ylabel = "'".$ykey."'";
			}else{
				$ydata = $ydata.','.$yvalue;
				$ylabel = $ylabel.','."'".$ykey."'";
			}
			$temp1++;
		}
		$yenrol=get_enrolled_course_yearly($userid);
		$temp2=1;
		$crdata='';
		$crlabel='';
		foreach ($yenrol as $crkey => $crvalue) {
			if($temp2 == 1){
				$crdata = $crvalue;
				$crlabel = "'".$crkey."'";

			}else{
				$crdata = $crdata.','.$crvalue;
				$crlabel = $crlabel.','."'".$crkey."'";
			}
			$temp2++;
		}
		$html .= html_writer::start_div('row');
		$html .= html_writer::start_div('col-md-6');
		$html .= html_writer::start_div('card pb-3 m-1');
		$html .= html_writer::start_tag('canvas', array('id'=>'thirdchart'));
		$html .= html_writer::end_tag('canvas');
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$html .= html_writer::start_div('col-md-6');
		$html .= html_writer::start_div('card pb-3 m-1');
		$html .= html_writer::start_tag('canvas', array('id'=>'fourthchart'));
		$html .= html_writer::end_tag('canvas');
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$html .="<script>
		var ctx = document.getElementById('firstchart').getContext('2d');
		var firstchart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['jan','feb','mar','apr','may','jun','jul','aug','sep','act','nov','dec'],
				datasets: [{
					label: 'Monthly Enrolled Courses',
					data: [".$menrol."],
					backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
					],
					borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)',
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
					],
					borderWidth: 1
					}]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
								}]
							}
						}

						});</script>
						<script>
						var ctx1 = document.getElementById('secondchart').getContext('2d');
						var secondchart = new Chart(ctx1, {
							type: 'bar',
							data: {
								labels: ['jan','feb','mar','apr','may','jun','jul','aug','sep','act','nov','dec'],
								datasets: [{
									label: 'Monthly completed course',
									data: [".$mcomplete."],
									backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)',
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
									],
									borderColor: [
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)',
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
									],
									borderWidth: 1
									}]
									},
									options: {
										scales: {
											yAxes: [{
												ticks: {
													beginAtZero: true
												}
												}]
											}
										}
										});</script>
										<script>
										var ctx2 = document.getElementById('thirdchart').getContext('2d');
										var thirdchart = new Chart(ctx2, {
											type: 'bar',
											data: {
												labels: [".$ylabel."],
												datasets: [{
													label: 'Yearly Course Completion',
													data: [".$ydata."],
													backgroundColor: [
													'rgba(255, 99, 132, 0.2)',
													'rgba(54, 162, 235, 0.2)',
													'rgba(255, 206, 86, 0.2)',
													'rgba(75, 192, 192, 0.2)',
													'rgba(153, 102, 255, 0.2)',
													'rgba(255, 159, 64, 0.2)',
													'rgba(255, 99, 132, 0.2)',
													'rgba(54, 162, 235, 0.2)',
													'rgba(255, 206, 86, 0.2)',
													'rgba(75, 192, 192, 0.2)',
													'rgba(153, 102, 255, 0.2)',
													'rgba(255, 159, 64, 0.2)'
													],
													borderColor: [
													'rgba(255, 99, 132, 1)',
													'rgba(54, 162, 235, 1)',
													'rgba(255, 206, 86, 1)',
													'rgba(75, 192, 192, 1)',
													'rgba(153, 102, 255, 1)',
													'rgba(255, 159, 64, 1)',
													'rgba(255, 99, 132, 1)',
													'rgba(54, 162, 235, 1)',
													'rgba(255, 206, 86, 1)',
													'rgba(75, 192, 192, 1)',
													'rgba(153, 102, 255, 1)',
													'rgba(255, 159, 64, 1)'
													],
													borderWidth: 1
													}]
													},
													options: {
														scales: {
															yAxes: [{
																ticks: {
																	beginAtZero: true
																}
																}]
															}
														}
														});</script>
														<script>
														var ctx3 = document.getElementById('fourthchart').getContext('2d');
														var fourthchart = new Chart(ctx3, {
															type: 'bar',
															data: {
																labels: [".$crlabel."],
																datasets: [{
																	label: 'Yearly Enrolled courses',
																	data: [".$crdata."],
																	backgroundColor: [
																	'rgba(255, 99, 132, 0.2)',
																	'rgba(54, 162, 235, 0.2)',
																	'rgba(255, 206, 86, 0.2)',
																	'rgba(75, 192, 192, 0.2)',
																	'rgba(153, 102, 255, 0.2)',
																	'rgba(255, 159, 64, 0.2)',
																	'rgba(255, 99, 132, 0.2)',
																	'rgba(54, 162, 235, 0.2)',
																	'rgba(255, 206, 86, 0.2)',
																	'rgba(75, 192, 192, 0.2)',
																	'rgba(153, 102, 255, 0.2)',
																	'rgba(255, 159, 64, 0.2)'
																	],
																	borderColor: [
																	'rgba(255, 99, 132, 1)',
																	'rgba(54, 162, 235, 1)',
																	'rgba(255, 206, 86, 1)',
																	'rgba(75, 192, 192, 1)',
																	'rgba(153, 102, 255, 1)',
																	'rgba(255, 159, 64, 1)',
																	'rgba(255, 99, 132, 1)',
																	'rgba(54, 162, 235, 1)',
																	'rgba(255, 206, 86, 1)',
																	'rgba(75, 192, 192, 1)',
																	'rgba(153, 102, 255, 1)',
																	'rgba(255, 159, 64, 1)'
																	],
																	borderWidth: 1
																	}]
																	},
																	options: {
																		scales: {
																			yAxes: [{
																				ticks: {
																					beginAtZero: true
																				}
																				}]
																			}
																		}
																	});</script>";




																	return $html;



																}
															}


//this function is used to year wise course completion data.
function get_user_yearly_completion($userid){
	global $DB;
	$year=$DB->get_records_sql("SELECT id, timecompleted FROM {course_completions} WHERE userid=".$userid." AND timecompleted IS NOT NULL");
	$emptyarray=[];
	foreach ($year as  $yearcompleted) {
		$singleyear=$yearcompleted->timecompleted;
		$emptyarray[] = date('Y',$singleyear);
	}
	$years = array_count_values($emptyarray);
	return $years;
}

//this function is used to month wise course completion data.
function get_enrolled_course_yearly($userid){
	global $DB;
	$enrolled=$DB->get_records_sql("SELECT timecreated FROM {user_enrolments} WHERE userid=".$userid."");
	$enroledarray=[];
	foreach ($enrolled as $courseenrolled) {
		$single=$courseenrolled->timecreated;
		$enrolledarray[]= date('Y',$single);
	}
	$years = array_count_values($enrolledarray);
	return $years;
}

// this function will give to last user logged info.
function get_last_userlogged_info($userid){

	global $DB;
	$loggedin=$DB->get_record_sql("SELECT timecreated FROM {logstore_standard_log} WHERE action='loggedin' AND userid=".$userid." ORDER BY timecreated DESC LIMIT 1");
	$data=$loggedin->timecreated;
	return $data;	
}

//this function will give last course completion.
function get_last_course_completed($userid){
	global $DB;
	$lastcourse=$DB->get_record_sql("SELECT course FROM {course_completions} WHERE timecompleted IS NOT NULL GROUP BY timecompleted DESC LIMIT 1");

	$courses=$lastcourse->course;
	return $courses;
}

function filter_course_result($startdate,$enddate,$category,$department,$role,$city){
	global $DB;
	$sql='';
	if(!empty($startdate)&& !empty($enddate)){
		$sql.="SELECT * FROM {course} WHERE timecreated BETWEEN ".$startdate." AND ".$enddate."";
	}
	if(!empty($category)){
		$sql.=" AND category=".$category." ";
	}
	$results = $DB->get_records_sql($sql);

	if(!empty($results)){
$rempty=[];//intializing array.
foreach ($results as $result) {
	$rempty[] = $result->id;
}
}
if(!empty($rempty)){

	return html_course_card_result($rempty); 
}
}	
//this function will give 
function get_course_from_category($stime=null,$etime=null,$categoryid=null,$department=null,$role=null,$city=null){
	global $DB,$CFG;
	require_once($CFG->libdir.'/completionlib.php');
	$sql='';
	$sql.="SELECT DISTINCT({course}.id) FROM {course} 
	LEFT JOIN {enrol} ON {course}.id = {enrol}.courseid
	LEFT JOIN {user_enrolments} ON {enrol}.id = {user_enrolments}.enrolid
	LEFT JOIN {user} ON {user_enrolments}.userid = {user}.id
	LEFT JOIN {role_assignments} ON {user}.id = {role_assignments}.userid
		WHERE {course}.visible = 1 AND {course}.id != 1";
	if(!empty($stime) && !empty($stime)){
		$sql.=" AND {course}.timecreated BETWEEN ".$stime." AND ".$etime." ";
	}
	if(!empty($categoryid)){
		$sql.=" AND {course}.category = ".$categoryid."";
	}
	if(!empty($department)){
		$sql.=" AND {user}.department = ".$department."";
	}
	if(!empty($role)){
		$sql.=" AND {role_assignments}.roleid = ".$role."";
	}
	if(!empty($city)){
		$sql.=" AND {user}.city = ".$city."";
	}
	$courses = $DB->get_records_sql($sql);

	//here i am getting all courses from this category.
	//here intializing counts of completion,enrolled,badges,certificates.  
	$totalcomplition=0;
	$totalenrolled=0;
	$totalbadges=0;
	$totalcertificates=0;
	if(!empty($courses)){

		foreach ($courses as $course) {
			
    	//here i am getting total user enroll into this course.
			$totalenrolled = $totalenrolled + count(all_enrolled_usersdata($course->id));
    	//here i am geetting total badge count for this category.
			//$allusers=all_enrolled_usersdata($course->id);
			$enrolled = $DB->get_records_sql("

			SELECT c.id, u.id

			FROM {course} c
			JOIN {context} ct ON c.id = ct.instanceid
			JOIN {role_assignments} ra ON ra.contextid = ct.id
			JOIN {user} u ON u.id = ra.userid
			JOIN {role} r ON r.id = ra.roleid

			where c.id = ".$course->id."");
			
    	//here i am getting single user using foreach loop.
			foreach ($enrolled as $user) {

    		//here getting complition status.
				$cinfo = new completion_info($course);

				$iscomplete = $cinfo->is_course_complete($user->id);
			//here check course is complete or not. 
				if(!empty($iscomplete)){
					$totalcomplition = $totalcomplition + 1;
				}
			     	//here i am getting all badge count related to this category.
				$sql='SELECT * FROM {badge_issued} JOIN {badge} ON {badge_issued}.badgeid={badge}.id WHERE {badge_issued}.userid='.$user->id.' AND {badge}.courseid='.$course->id.'';
				$badgecount = count($DB->get_records_sql($sql));
				$totalbadges = $totalbadges+$badgecount;

	    	// here i am getting all certificate count related to this category.
				$data='SELECT * FROM {simplecertificate} JOIN {simplecertificate_issues} ON {simplecertificate}.id={simplecertificate_issues}.certificateid WHERE {simplecertificate_issues}.userid='.$user->id.' AND {simplecertificate}.course='.$course->id.'';
				$certificatecount = count($DB->get_records_sql($data));
				$totalcertificates = $totalcertificates+$certificatecount;

			}


		}
		
	}
	$returnarray = array('enrolcount'=>$totalenrolled,'completioncount'=>$totalcomplition,'badgecount'=>$totalbadges,'certificatecount'=>$totalcertificates);
	return $returnarray ;
}

function rightside_ajax_output($stime=null,$etime=null,$category=null,$department=null,$role=null,$city=null){
	global $DB;
	$data=get_course_from_category($stime,$etime,$category,$department,$role,$city);
	$html='';
	$html .= html_writer::start_div('row');
	$html .='<div class="col-lg-3 col-sm-6">
	<div class="card text-white bg-primary mb-3 statcards">
	<div class="card-content">
	<div class="row">
	<div class="col-xs-5 ml-4">
	<div class="icon-big icon-warning text-center">
	<i class="fa fa-check-square-o" aria-hidden="true"></i>   
	</div>
	</div>
	<div class="col-xs-7 ml-4">
	<div class="numbers">
	<p>'.get_string('enrolledcourse','local_deptrpts').'</p>
	<h3>'.$data['enrolcount'].'</h3>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="col-lg-3 col-sm-6">
	<div class="card text-white bg-success mb-3 statcards">
	<div class="card-content">
	<div class="row">
	<div class="col-xs-5 ml-4">
	<div class="icon-big icon-success">
	<i class="fa fa-certificate" aria-hidden="true"></i>
	</div>
	</div>
	<div class="col-xs-7 ml-4">
	<div class="numbers">
	<p>'.get_string('badgesearned','local_deptrpts').'</p>
	<h3>'.$data['badgecount'].'</h3>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="col-lg-3 col-sm-6">
	<div class="card text-white bg-danger mb-3 statcards">
	<div class="card-content">
	<div class="row">
	<div class="col-xs-5 ml-4">
	<div class="icon-big icon-danger text-center">
	<i class="fa fa-list" aria-hidden="true"></i>
	</div>
	</div>
	<div class="col-xs-7 ml-4">
	<div class="numbers">
	<p>'.get_string('coursecompleted','local_deptrpts').'</p>
	<h3>'.$data['completioncount'].'</h3>
	</div>
	</div>
	</div>
	</div>

	</div>
	</div>
	<div class="col-lg-3 col-sm-6">
	<div class="card text-white bg-info mb-3 statcards">
	<div class="card-content">
	<div class="row">
	<div class="col-xs-5 ml-4">
	<div class="icon-big icon-info text-center">
	<i class="fa fa-address-card-o " aria-hidden="true"></i>
	</div>
	</div>
	<div class="col-xs-7 ml-4">
	<div class="numbers">
	<p>'.get_string('certificateearned','local_deptrpts').'</p>
	<h3>'.$data['certificatecount'].'</h3>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>';
	$html.= html_canavas_generater($stime,$etime,$category,$department,$role,$city);
	$html.= html_canavas_jscode_generater($stime,$etime,$category,$department,$role,$city);
	$html.= user_enrolled_yearlycomplitiongraph($stime,$etime,$category,$department,$role,$city);
	//$html.= get_userdatatable();
	return $html;	
}

function get_category_statistics($categoryid){
	global $DB;
	//here get all course from in this category.
	$data = course_completion_stats($start=null,$end=null,$categoryid,$department=null,$role=null,$city=null);


	return $data;
}

//This function ill return no of completed,inprogress, not started users of all courses present in particular category.
function course_completion_stats($start=null,$end=null,$categoryid=null,$department=null,$role=null,$city=null)
{
	global $DB;
	$courses=$DB->get_records('course', array('category'=>$categoryid));
	$categoryname=$DB->get_field('course_categories', 'name', array('id'=>$categoryid));
	$completed=0;
	$inprogress=0;
	$notstarted=0;
	foreach ($courses as $course) {
		$courseid=$course->id;
//manjunath: getting total count of users.
		$sql='';
		if(!empty($start)&& !empty($end)){
			$sql.=" AND c.timeenrolled between $start AND $end";
		}
		if(!empty($department)){
			$sql.=" AND u.department LIKE '%$department%' ";
		}
		if(!empty($role)){
			$sql.=" AND  ra.roleid LIKE '%$role%'";
		}
		if(!empty($city)){
			$sql .=" AND u.city LIKE '%$city%'";
		}

		$totalquery = "SELECT *
		FROM {course_completions} c
		INNER JOIN {user} u ON u.id = c.userid
		INNER JOIN {role_assignments} ra ON u.id = ra.userid
		WHERE (c.course = '$courseid')
		$sql";
		$records = count($DB->get_records_sql($totalquery));
		$totalcount = $records;
//manjunath: coures completed users count.
		$completedquery="SELECT DISTINCT(mcc.userid) FROM {course_completions} mcc 
		JOIN {course} mc ON mc.id = mcc.course
		JOIN {user} mu ON mcc.userid=mu.id
		RIGHT JOIN {role_assignments} mrc ON mrc.userid = mcc.userid
		WHERE mcc.course= ".$courseid." AND mcc.timecompleted is not null
		$sql
		";

		$completedrecords = count($DB->get_records_sql($completedquery));
		$completedcount = $completedrecords;

//manjunath: course in progress users count.
		$progressquery="SELECT DISTINCT(mcc.userid) FROM {course_completions} mcc 
		JOIN {course} mc ON mc.id = mcc.course
		JOIN {user} mu ON mcc.userid=mu.id
		RIGHT JOIN {role_assignments} mrc ON mrc.userid = mcc.userid
		WHERE mcc.course= ".$courseid." AND (mcc.timestarted != 0)
		AND (mcc.timecompleted is null)
		$sql
		";

		$progessrecords = count($DB->get_records_sql($progressquery));
		$progresscount = $progessrecords;

//manjunath: course not started users count
		$notstartedquery = "SELECT DISTINCT(mcc.userid) FROM {course_completions} mcc 
		JOIN {course} mc ON mc.id = mcc.course
		JOIN {user} mu ON mcc.userid=mu.id
		RIGHT JOIN {role_assignments} mrc ON mrc.userid = mcc.userid
		WHERE mcc.course= ".$courseid." AND (mcc.timestarted = 0)
		AND (mcc.timecompleted is null)
		$sql
		";

		$notstartedrecords = count($DB->get_records_sql($notstartedquery));
		$notstartedcount = $notstartedrecords;

		$completed = $completed + $completedcount;
		$inprogress= $inprogress + $progresscount;
		$notstarted=$notstarted + $notstartedcount;

	}
	return array($completed,$inprogress,$notstarted,$categoryname);

}

function html_canavas_generater($stime=null,$etime=null,$category=null,$department=null,$role=null,$city=null){
	global $DB;
	if(!empty($category)){
		$categories = $DB->get_records('course_categories',array('id'=>$category));
	}else{
		$categories = $DB->get_records('course_categories');
	}
	$emptyarray=[];
	foreach ($categories as $cat) {
		$category = course_completion_stats($start=null,$end=null,$cat->id,$department=null,$role=null,$city=null);
		if(!empty($category[0]) || !empty($category[1]) || !empty($category[2])){
			$emptyarray[]=$category;
		}
	}
	$ccount = count($emptyarray);
	$output='';
	$output .= html_writer::start_div('row',array('id'=>'categorycards'));
	for ($i=1; $i <=$ccount ; $i++) { 
		$html='';
		$html .= html_writer::start_div('col-md-6');
		$html .= html_writer::start_div('card pb-3 m-1');
		$html .= html_writer::start_tag('canvas', array('id'=>'piechart'.$i));
		$html .= html_writer::end_tag('canvas');
		$html .= html_writer::end_div();
		$html .= html_writer::end_div();
		$output.= $html;
	}
	$output.= html_writer::end_div();
	return $output;
}
function html_canavas_jscode_generater($stime=null,$etime=null,$categoryid=null,$department=null,$role=null,$city=null){
	global $DB;
	if(!empty($categoryid)){
		$category = 1;
		$categories = $DB->get_records('course_categories',array('id'=>$categoryid));
	}else{
		$category = count($DB->get_records('course_categories'));
		$categories = $DB->get_records('course_categories');
	}
	//here i am getting conuting of all categories.
	//here creating empty string.
	$output = '';
	//here getting all categories from course_categories.
	//creating empty array.
	$emptyarray = [];
	//here getting ingle category from categories using foreach loop.
	foreach ($categories as $cat) {
	//here calling of thi function and puhing the reult to empty array.
		$temp=course_completion_stats($start=null,$end=null,$cat->id,$department=null,$role=null,$city=null);
		if(!empty($temp[0]) || !empty($temp[1]) || !empty($temp[2])){
			$emptyarray[]=$temp;
		}
	}
	//here write all javacript code in side the empty string.
	$empty='';
	//here i am declaring counter. 
	$counter=1;
	foreach ($emptyarray as $dataarray) {
		//here generate javacript for piecharts.
		$data ='';
		$data .='<script>var ctx'.$counter.' = document.getElementById("piechart'.$counter.'").getContext("2d");

		var piechart'.$counter.' = new Chart(ctx'.$counter.', {
			type: "doughnut",
			options: {
				title: {
					display: true,
					text: "'.strtoupper($dataarray[3]).'"
				}
				},
				data: {
					labels: ["Completed", "Inprogress", "Notstarted"],
					datasets: [{
						backgroundColor: [
						"#2ecc71",
						"#3498db",
						"#95a5a6",
						"#9b59b6",
						"#f1c40f",
						"#e74c3c",
						"#34495e"
						],
						data: ['.$dataarray[0].','.$dataarray[1].','.$dataarray[2].']

						}]
					}
					});</script>
					';
					$empty.=$data;
//here increament counter.
					$counter++;
				}
	//here return the javascript code.
				return $empty;
			}

//get_yearwisegraph(7, $start_date=0, $end_date=0, $city=0, $institution=0, $department=0);
			function get_yearwisegraph($course_id=null, $start_date=null, $end_date=null, $city=null, $institution=null, $department=null){
				global $DB;
				$convdate=[];
				$allenrolldates = all_enrolled_usersdata_date($course_id);
				foreach ($allenrolldates as $condate) { 
					$convdate[]=date('Y', $condate);
				}
				$userenrolconvyear = array_count_values($convdate);
				$cmpletiondate=[];
				$completiondatequery = "SELECT id,timecompleted FROM {course_completions} WHERE course = $course_id AND timecompleted IS NOT NULL";
				$coursecompledate = $DB->get_records_sql($completiondatequery);
				foreach ($coursecompledate as $cdate) {
					$cmpletiondate[]=date('Y',$cdate->timecompleted);
				}
				$usercompleconvyear = array_count_values($cmpletiondate);
				$returnarray = array('enrolldata'=>$userenrolconvyear,
					'completiondata'=>$usercompleconvyear);
				return $returnarray;

			}
			function get_yearwisecategory_info($categoryid){
				global $DB;
	//here  getting all course from category.
				$courses=$DB->get_records('course', array('category'=>$categoryid));
				$enrolled=[];
				$complition=[];
				foreach ($courses as $course) {
					$data=$course->id;
					$result=get_yearwisegraph($data, $start_date=null, $end_date=null, $city=null, $institution=null, $department=null);
					foreach ($result as $rkey => $rvalue) {
						if($rkey=='enrolldata'){
							foreach ($rvalue as $rrkey => $rrvalue) {
								if(array_key_exists($rrkey, $enrolled)){
									$enrolled[$rrkey] += $rrvalue;
								}else{
									$enrolled[$rrkey]=$rrvalue;

								}

							}

						}
						if($rkey=='completiondata'){
							foreach ($rvalue as $ckey => $cvalue) {
								if(array_key_exists($ckey, $complition)){
									$complition[$ckey] += $cvalue;
								}else{

									$complition[$ckey]=$cvalue;
								}

							}
						}
					}
				}
				return array('enrol'=>$enrolled,'complete'=>$complition);
			}

//Mallamma: This function will return the html and script for yearwise enrolled and completed graph.
function user_enrolled_yearlycomplitiongraph($stime=null,$etime=null,$category=null,$department=null,$role=null,$city=null){
global $DB;
$html='';
//here i am checking category is empty or not..
if(!empty($category)){
$data = get_yearwisecategory_info($category);
$enrolllabel="";
$enrolldata="";
$counter=1;
if(!empty($data['enrol'])){
foreach ($data['enrol'] as $enkey => $envalue) {
if($counter==1){
$enrolllabel="'".$enkey."'";
$enrolldata=$envalue;
}else{
$enrolllabel=$enrolllabel.','."'".$enkey."'";
$erolldata=$erolldata.','.$envalue;
}
$counter++;
}
}

$completelabel="";
$completedata="";
$counter=1;
if(!empty($data['complete'])){
foreach ($data['complete'] as $cmkey => $cmvalue) {
if($counter==1){
$completelabel="'".$cmkey."'";
$completedata=$cmvalue;
}else{
$completelabel=$completelabel.','."'".$cmkey."'";
$completedata=$completedata.','.$cmvalue;
}
$counter++;
}
}

//$data = get_yearwisecategory_info($category);

$html.= html_writer::start_div('row');
$html.=html_writer::start_div('col-md-6');
$html .= html_writer::start_div('card pb-3 m-1');
$html.=html_writer::start_tag('canvas',array('id'=>'yearlyenrol'));
$html.=html_writer::end_tag('canvas');
$html.=html_writer::end_div();
$html.=html_writer::end_div();
$html.=html_writer::start_div('col-md-6');
$html .= html_writer::start_div('card pb-3 m-1');
$html.=html_writer::start_tag('canvas',array('id'=>'yearlycomplete'));
$html.=html_writer::end_tag('canvas');
$html .= html_writer::end_div();
$html.=html_writer::end_div();
$html.=html_writer::end_div();
$html.=html_writer::end_div();
//creating chaart data.
$html.="<script>
var ctx = document.getElementById('yearlyenrol').getContext('2d');
var yearlyenrol = new Chart(ctx, {
type: 'bar',
data: {
labels: [".$enrolllabel."],
datasets: [{
label: 'Yearly Enrolled Courses',
data: [".$enrolldata."],
backgroundColor: [
'rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(255, 159, 64, 0.2)',
'rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(255, 159, 64, 0.2)'
],
borderColor: [
'rgba(255, 99, 132, 1)',
'rgba(54, 162, 235, 1)',
'rgba(255, 206, 86, 1)',
'rgba(75, 192, 192, 1)',
'rgba(153, 102, 255, 1)',
'rgba(255, 159, 64, 1)',
'rgba(255, 99, 132, 1)',
'rgba(54, 162, 235, 1)',
'rgba(255, 206, 86, 1)',
'rgba(75, 192, 192, 1)',
'rgba(153, 102, 255, 1)',
'rgba(255, 159, 64, 1)'
],
borderWidth: 1
}]
},
options: {
scales: {
	yAxes: [{
		ticks: {
			beginAtZero: true
		}
		}]
	}
}

});</script>
<script>
var ctx1 = document.getElementById('yearlycomplete').getContext('2d');
var yearlycomplete = new Chart(ctx1, {
	type: 'bar',
	data: {
		labels: [".$completelabel."],
		datasets: [{
			label: 'Yearly Completed Courses',
			data: [".$completedata."],
			backgroundColor: [
			'rgba(255, 99, 132, 0.2)',
			'rgba(54, 162, 235, 0.2)',
			'rgba(255, 206, 86, 0.2)',
			'rgba(75, 192, 192, 0.2)',
			'rgba(153, 102, 255, 0.2)',
			'rgba(255, 159, 64, 0.2)',
			'rgba(255, 99, 132, 0.2)',
			'rgba(54, 162, 235, 0.2)',
			'rgba(255, 206, 86, 0.2)',
			'rgba(75, 192, 192, 0.2)',
			'rgba(153, 102, 255, 0.2)',
			'rgba(255, 159, 64, 0.2)'
			],
			borderColor: [
			'rgba(255, 99, 132, 1)',
			'rgba(54, 162, 235, 1)',
			'rgba(255, 206, 86, 1)',
			'rgba(75, 192, 192, 1)',
			'rgba(153, 102, 255, 1)',
			'rgba(255, 159, 64, 1)',
			'rgba(255, 99, 132, 1)',
			'rgba(54, 162, 235, 1)',
			'rgba(255, 206, 86, 1)',
			'rgba(75, 192, 192, 1)',
			'rgba(153, 102, 255, 1)',
			'rgba(255, 159, 64, 1)'
			],
			borderWidth: 1
			}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
						}]
					}
				}
			});</script>";
		}else{
			$categories=$DB->get_records('course_categories');
			$yenrol=[];
			$ycomplete=[];
			foreach ($categories as $category) {
				$temp= get_yearwisecategory_info($category->id);
				if(!empty($temp['enrol']) || !empty($temp['complete'])){
					foreach ($temp as $ykey => $yvalue) {
						if($ykey=='enrol'){
							foreach ($yvalue as  $yykey => $yyvalue) {
								if(array_key_exists($yykey, $yenrol)){
									$yenrol[$yykey] += $yyvalue;
								}else{
									$yenrol[$yykey]=$yyvalue;
								}
							}
						}
						if($ykey=='complete'){
							foreach ($yvalue as $cckey => $ccvalue) {
								if(array_key_exists($cckey, $ycomplete)){
									$ycomplete[$cckey] += $ccvalue;
								}else{

									$ycomplete[$cckey]=$ccvalue;
								}
							}
						}

					}
				}
			}
			$enrolllabel="";
			$enrolldata="";
			$counter=1;
			if(!empty($yenrol)){
				foreach ($yenrol as $yekey => $yevalue) {
					if($counter==1){
						$enrolllabel="'".$yekey."'";
						$enrolldata=$yevalue;
					}else{
						$enrolllabel=$enrolllabel.','."'".$yekey."'";
						$enrolldata=$erolldata;
					}
					$counter++;
				}
			}
			$completelabel="";
			$completedata="";
			$counter=1;
			if(!empty($ycomplete)){
				foreach ($ycomplete as $yckey => $ycvalue) {
					if($counter==1){
						$completelabel="'".$yckey."'";
						$completedata=$ycvalue;
					}else{

						$completelabel=$completelabel.','."'".$yckey."'";
						$completedata=$completedata;
					}
					$counter++;
				}
			}
//$data = get_yearwisecategory_info($category);
			$html='';
			$html.= html_writer::start_div('row');

			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'yearlyenrol'));
			$html.=html_writer::end_tag('canvas');
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'yearlycomplete'));
			$html.=html_writer::end_tag('canvas');
			$html.= html_writer::end_div();
			$html.=html_writer::end_div();

			$html.=html_writer::end_div();
//creating chaart data.
			$html.="<script>
			var ctx = document.getElementById('yearlyenrol').getContext('2d');
			var yearlyenrol = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: [".$enrolllabel."],
					datasets: [{
						label: 'Yearly Enrolled Courses',
						data: [".$enrolldata."],
						backgroundColor: [
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)'
						],
						borderColor: [
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)',
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
						],
						borderWidth: 1
						}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
									}]
								}
							}

							});</script>
							<script>
							var ctx1 = document.getElementById('yearlycomplete').getContext('2d');
							var yearlycomplete = new Chart(ctx1, {
								type: 'bar',
								data: {
									labels: [".$completelabel."],
									datasets: [{
										label: 'Yearly Completed Courses',
										data: [".$completedata."],
										backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)'
										],
										borderColor: [
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)',
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)'
										],
										borderWidth: 1
										}]
										},
										options: {
											scales: {
												yAxes: [{
													ticks: {
														beginAtZero: true
													}
													}]
												}
											}
										});</script>";

									}
									return $html;
								}



function get_userdatatable($stime=null,$etime=null,$category=null,$department=null,$role=null,$city=null){
	global $DB;
	//creating table here.
	$usertable = new \html_table();
	$usertable->id = "usertable";
	$usertable->head = array(get_string('serial','local_deptrpts'),get_string('fullname','local_deptrpts'),get_string('email','local_deptrpts'),get_string('coursename','local_deptrpts'),get_string('enrolmentdate','local_deptrpts'),get_string('completiondate','local_deptrpts'),get_string('completionstatus','local_deptrpts'),get_string('coursegrade','local_deptrpts'));
	
	//write all the logic for coding here.
	//here getting course catgeories.
	$categories=$DB->get_records('course_categories');
	$counter=1;
	//here getting from categories to category.
	foreach ($categories as $category) {
		//here getting course from categgory.
		$courses=$DB->get_records('course',array('category'=>$category->id));
		//here getting from courses to course. 
		foreach ($courses as $course) {
			//here gettiong how many users enrolled in this course.
				$enroled=get_enroled_userdata($course->id);

				//here checking enroled or not.
				if(!empty($enroled)){
					//here getting from enroled to key and value.

					foreach ($enroled as $enkey => $envalue) {
							
							//here getting  users from userid.
							$user=$DB->get_record('user',array('id'=>$envalue[0]));
							if(!empty($user)){
							//here covnverting unix date to normal date farmat.
							if(!empty($envalue[1])){
									$enroldate=date('d-m-Y', $envalue[1]);
								}else{
									$enroldate="-";
								}
							//here getting fullname
							$fullname=$user->firstname.' '.$user->lastname;
							//here getting emailid.

							$email=$user->email;
							//here getting coursename.
							$coursename=$course->fullname;
							//here i am finding course complition date.
							$completion=$DB->get_record_sql("SELECT timecompleted FROM {course_completions} WHERE course=".$course->id." AND userid=".$envalue[0]." AND timecompleted IS NOT NULL");
							if(!empty($completion)){
								$completiondate=date('d-m-Y',$completion->timecompleted);
								}else
								{
									$completiondate="-";
								}
								//here getting completions status
								// $status=course_status_check($envalue[0],$course->id);
								// $status="-";

								$cinfo = new completion_info($course);
								$iscomplete = $cinfo->is_course_complete($user->id);
								if(!empty($iscomplete)){

									$status=get_string('complet','local_deptrpts');
								}else
								{
									$status=get_string('notcomplete','local_deptrpts');
								}
								
								//here getting coursegrade.
								$grade = grade_get_course_grades($course->id, $envalue[0]);
								$grd = $grade->grades[$envalue[0]]; 
								$cgrade=$grd->str_grade;

								$usertable->data[]=array($counter,$fullname,$email,$coursename,$enroldate,$completiondate,$status,$cgrade);
								$counter++;
							}

						}
					}
				
				}
				
	 		}
	 	
	 		$html="";
	 		$html.=html_writer::start_div('row m-1');
	 		$html.=html_writer::start_div('col-md-12 p-2 card', array('id'=>'tablecard'));
	 		$html.=html_writer::table($usertable);
	 		$html.=html_writer::end_div();
	 		$html.=html_writer::end_div();
	 		
	 		return $html;

}
//------------------------------------------------
function course_filter_ajax_html_output($coursename){
	global $DB;
	if(!empty($coursename)){
	 $courseid = $DB->get_field('course','id',array('fullname'=>$coursename));
	 //$enrolluser=all_enrolled_usersdata($courseid);
	 	$enrolled = $DB->get_records_sql("

			SELECT c.id, u.id

			FROM {course} c
			JOIN {context} ct ON c.id = ct.instanceid
			JOIN {role_assignments} ra ON ra.contextid = ct.id
			JOIN {user} u ON u.id = ra.userid
			JOIN {role} r ON r.id = ra.roleid

			where c.id = ".$courseid."");
	 	$enrolluser = count($enrolled);
	 $badgecount= get_badges_earned($courseid);
	 $certificatecount =get_course_cerficatescount($courseid);
	 $complitioncount =course_complition_count($courseid);

	 	//creating html part to return.
		$html='';
		$html .= html_writer::start_div('row');
		$html .='<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-primary mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-warning text-center">
		<i class="fa fa-check-square-o" aria-hidden="true"></i>   
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('enrolledcourse','local_deptrpts').'</p>
		<h3>'.$enrolluser.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-success mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-success">
		<i class="fa fa-certificate" aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('badgesearned','local_deptrpts').'</p>
		<h3>'.$badgecount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-danger mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-danger text-center">
		<i class="fa fa-list" aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('coursecompleted','local_deptrpts').'</p>
		<h3>'.$complitioncount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-lg-3 col-sm-6">
		<div class="card text-white bg-info mb-3 userstats">
		<div class="card-content">
		<div class="row">
		<div class="col-xs-5 ml-4">
		<div class="icon-big icon-info text-center">
		<i class="fa fa-address-card-o " aria-hidden="true"></i>
		</div>
		</div>
		<div class="col-xs-7 ml-4">
		<div class="numbers">
		<p>'.get_string('certificateearned','local_deptrpts').'</p>
		<h3>'.$certificatecount.'</h3>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>'; 
		$html.=course_enrollandcompletion_monthly_data($courseid);
		$html.=yearwise_course_enrollment_data($courseid);
		$html.=coursetable_data($courseid);
		return $html;
	}

}

//---this function using for count badges..
function get_badges_earned($courseid){
	global $DB;
$badgeid=$DB->get_records_sql('SELECT id FROM {badge} WHERE courseid = "'.$courseid.'" ');
$totalbadge=0;
if(!empty($badgeid)){
foreach ($badgeid as $id => $badge) {
	$badgeissued=$DB->get_records_sql('SELECT * FROM {badge_issued} WHERE badgeid ="'.$id.'"');
	$totalbadge = $totalbadge+count($badgeissued);

	}	
}
return $totalbadge;
}

function get_course_cerficatescount($courseid){
	global $DB;
	$certificateid=$DB->get_records_sql('SELECT id FROM {simplecertificate} WHERE course = "'.$courseid.'"');
	$totalcertificate=0;
	if(!empty($certificateid)){
		foreach ($certificateid as $cid => $cvalue) {
				$cerficateissued=$DB->get_records_sql('SELECT * FROM {simplecertificate_issues} WHERE certificateid = "'.$cid.'"');
				$totalcertificate = $totalcertificate+count($cerficateissued);
		}
	}
	return $totalcertificate;

}

//course complition.
function course_complition_count($courseid){
	global $DB;
	$course=$DB->get_record('course', array('id'=>$courseid));
	$cinfo = new completion_info($course);
	//print_object($enrolled);die;
	$enrolled = $DB->get_records_sql("

			SELECT c.id, u.id

			FROM {course} c
			JOIN {context} ct ON c.id = ct.instanceid
			JOIN {role_assignments} ra ON ra.contextid = ct.id
			JOIN {user} u ON u.id = ra.userid
			JOIN {role} r ON r.id = ra.roleid

			where c.id = ".$courseid."");
	$totalcomplition=0;
	foreach($enrolled as $singleuser){
		$iscomplete = $cinfo->is_course_complete($singleuser->id);
				//here check course is complete or not. 
				if(!empty($iscomplete)){
					$totalcomplition = $totalcomplition + 1;
				}
	}
	return $totalcomplition;
}

function yearwise_course_enrollment_data($courseid){
		 global $DB;
		 $completiondata = $DB->get_records_sql('SELECT id,timecompleted FROM {course_completions} 	WHERE course = "'.$courseid.'" AND timecompleted is not null');
		$completiondate=[];
		 foreach ($completiondata as $comkey => $comvalue) {
		 	$completiondate[]=date('Y', $comvalue->timecompleted);
		 }
		 $completionyear = array_count_values($completiondate);
		 $complabel="";
		$compdata="";
	 	$counter=1;
		if(!empty($completionyear)){
		foreach ($completionyear as $cmkey => $cmvalue) {
		if($counter==1){
		$complabel="'".$cmkey."'";
		$compdata=$cmvalue;
		}else{
		$complabel=$enrolllabel.','."'".$cmkey."'";
		$compdata=$erolldata.','.$cmvalue;
		}
		$counter++;
		}
		}
		 $enrolldates=get_enroled_userdata($courseid);
	 		$convdate=[];
			foreach ($enrolldates as $condate) { 
				$convdate[]=date('Y', $condate[1]);

			}
			$userenrolconvyear = array_count_values($convdate);
			$counter=1;
			if(!empty($userenrolconvyear)){
			foreach ($userenrolconvyear as $enkey => $envalue) {
			if($counter==1){
			$enrolllabel="'".$enkey."'";
			$enrolldata=$envalue;
			}else{
			$enrolllabel=$enrolllabel.','."'".$enkey."'";
			$erolldata=$erolldata.','.$envalue;
			}
			$counter++;
			}
			}

			$html ='';
			$html.=html_writer::start_div('row');
			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'yearlyenrol'));
			$html.=html_writer::end_tag('canvas');
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'yearlycomplete'));
			$html.=html_writer::end_tag('canvas');
			$html.= html_writer::end_div();
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

			//creating chaart data.
			$html.="<script>
			var ctx = document.getElementById('yearlyenrol').getContext('2d');
			var yearlyenrol = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: [".$enrolllabel."],
					datasets: [{
						label: 'Yearly Enrolled Courses',
						data: [".$enrolldata."],
						backgroundColor: [
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)'
						],
						borderColor: [
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)',
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
						],
						borderWidth: 1
						}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
									}]
								}
							}

							});</script>							<script>
							var ctx1 = document.getElementById('yearlycomplete').getContext('2d');
							var yearlycomplete = new Chart(ctx1, {
								type: 'bar',
								data: {
									labels: [".$complabel."],
									datasets: [{
										label: 'Yearly Completed Courses',
										data: [".$compdata."],
										backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)'
										],
										borderColor: [
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)',
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)'
										],
										borderWidth: 1
										}]
										},
										options: {
											scales: {
												yAxes: [{
													ticks: {
														beginAtZero: true
													}
													}]
												}
											}
										});</script>";

							return $html;

}

//this function is used to get enrol and complete course.
function course_enrollandcompletion_monthly_data($courseid){
		global $DB;
		$completiondata=$DB->get_records_sql('SELECT id,timecompleted FROM {course_completions} 	WHERE course = "'.$courseid.'" AND timecompleted is not null');
		$completeddate=[];
		foreach ($completiondata as $ckey => $cvalue) {
				$completeddate[]=$cvalue->timecompleted;
				
		}

		$completearray=[];
		foreach ($completeddate as $singledate) {
				$completearray[]=date("m",$singledate);

		}
		$months = array_count_values($completearray);

		$montharray=[];
		$marray = array('Jan','Feb','Mar','Apl','May','Jun','Jul','Aug','Sep','Act','Nov','Dec');
		for ($i=01; $i < 13; $i++) { 
		if ($i <= 9) {
			$i = '0'.$i;
		}
		if(array_key_exists($i, $months)){
			$montharray[$marray[$i-1]] = $months[$i];
		}else{
			$montharray[$marray[$i-1]] = 0;
		}
		}
		//here getting enroll course data.
		$enrolldate=get_enroled_userdata($courseid);
		$emptyarray=[];
		foreach($enrolldate as $singledate){
				$enrol=$singledate[1];
				$emptyarray[]=date('m', $singledate[1]);
				//print_object($emptyarray);die;
		}
		$enrolcourse = array_count_values($emptyarray);
		$enrollarray=[];
		$enrolldate = array('Jan','Feb','Mar','Apl','May','Jun','Jul','Aug','Sep','Act','Nov','Dec');
		for($i=01; $i< 13; $i++){
			if($i <= 9){
				$i = '0'.$i;
			}
		if(array_key_exists($i, $enrolcourse)){
			$enrollarray[$enrolldate[$i-1]] = $enrolcourse[$i];
		}else{
			$enrollarray[$enrolldate[$i-1]] = 0;
		}
		}
		//--------
		$count =1;
		$menroll = '';
		foreach ($enrollarray as $mkey => $mvalue) {
				if($count == 1){
					$menroll = $mvalue;
				}else{
					$menroll = $menroll.','.$mvalue;
				}
				$count++;
		}
		$temp = 1;
		$mcomplete ='';
		foreach ($montharray as $ckey => $cvalue) {
			if($temp == 1){
				$mcomplete = $cvalue;
			}else{
				$mcomplete = $mcomplete.','.$cvalue;

			}
			$temp++;
		}

			$html ='';
			$html.=html_writer::start_div('row');
			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'monthlyenrol'));
			$html.=html_writer::end_tag('canvas');
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

			$html.=html_writer::start_div('col-md-6');
			$html.= html_writer::start_div('card pb-3 m-1');
			$html.=html_writer::start_tag('canvas',array('id'=>'monthlycompletion'));
			$html.=html_writer::end_tag('canvas');
			$html.= html_writer::end_div();
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

			$html .="<script>
		var ctx = document.getElementById('monthlyenrol').getContext('2d');
		var firstchart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['jan','feb','mar','apr','may','jun','jul','aug','sep','act','nov','dec'],
				datasets: [{
					label: 'Monthly Enrolled Courses',
					data: [".$menroll."],
					backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
					],
					borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)',
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
					],
					borderWidth: 1
					}]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
								}]
							}
						}

						});</script>
						<script>
						var ctx1 = document.getElementById('monthlycompletion').getContext('2d');
						var secondchart = new Chart(ctx1, {
							type: 'bar',
							data: {
								labels: ['jan','feb','mar','apr','may','jun','jul','aug','sep','act','nov','dec'],
								datasets: [{
									label: 'Monthly completed courses',
									data: [".$mcomplete."],
									backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)',
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
									],
									borderColor: [
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)',
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
									],
									borderWidth: 1
									}]
									},
									options: {
										scales: {
											yAxes: [{
												ticks: {
													beginAtZero: true
												}
												}]
											}
										}
										});</script>";
						return $html;

		}

//this function used to coursetable..

function coursetable_data($courseid){
		global $DB, $CFG;
		//$courseid=9;
		//here getting all users enrolled inside given course.
		$enrolusers=all_enrolled_usersdata($courseid);
		//print_object($enrolcourse);die;
		$activity=get_fast_modinfo($courseid);
		$activities=$activity->get_cms();
		//print_object($activities);die;
		$data=list_all_activities_grade($courseid,16);
		//print_object($data);die;

		//creating course table.
		//here I am getting all the course in a course.
		$activities=list_all_activities_grade($courseid);
		$activityname=[];
		$gradevalue=[];
		foreach ($activities as $akey => $avalue) {
				$activityname[]=$avalue['activityname'];
				$gradevalue[]=$avalue['gradeval'];


		 } 
		$usertable = new \html_table();
		$usertable->id = "coursetable";
		$header1=array(get_string('serial','local_deptrpts'),get_string('fullname','local_deptrpts'),get_string('email','local_deptrpts'));
		$header2=$activityname;
		$header3=array(get_string('enrolmentdate','local_deptrpts'),get_string('completiondate','local_deptrpts'),get_string('completionstatus','local_deptrpts'),get_string('coursegrade','local_deptrpts'));
		$tableheader =array_merge($header1,$header2,$header3);
		$usertable->head = $tableheader;
		$counter=1;
		$course=$DB->get_record('course',array('id'=>$courseid));
		$enroled=get_enroled_userdata($courseid);

				//here checking enroled or not.
				if(!empty($enroled)){
					//here getting from enroled to key and value.

					foreach ($enroled as $enkey => $envalue) {
							
							//here getting  users from userid.
							$user=$DB->get_record('user',array('id'=>$envalue[0]));
							if(!empty($user)){
							//here covnverting unix date to normal date farmat.
							if(!empty($envalue[1])){
									$enroldate=date('d-m-Y', $envalue[1]);
								}else{
									$enroldate="-";
								}
							//here getting fullname
							$fullname=$user->firstname.' '.$user->lastname;
							//here getting emailid.

							$email=$user->email;
							//here getting coursename.
							$coursename=$course->fullname;
							//here i am finding course complition date.
							$completion=$DB->get_record_sql("SELECT timecompleted FROM {course_completions} WHERE course=".$courseid." AND userid=".$envalue[0]." AND timecompleted IS NOT NULL");
							if(!empty($completion)){
								$completiondate=date('d-m-Y',$completion->timecompleted);
								}else
								{
									$completiondate="-";
								}
								//here getting completions status
								// $status=course_status_check($envalue[0],$course->id);
								// $status="-";
								$course=$DB->get_record('course',array('id'=>$courseid));
								$cinfo = new completion_info($course);
								$iscomplete = $cinfo->is_course_complete($user->id);
								if(!empty($iscomplete)){

									$status=get_string('complet','local_deptrpts');
								}else
								{
									$status=get_string('notcomplete','local_deptrpts');
								}
								//---
								$actgrades=list_all_activities_grade($courseid,$user->id);
								
								$gradevalue=[];
								foreach ($actgrades as $gkey => $gvalue) {
									if(!empty($gvalue['gradeval'])){
										$gradevalue[]=$gvalue['gradeval'];
									}else{
										$gradevalue[]='-';
									}

								 }
								//here getting coursegrade.
								$grade = grade_get_course_grades($courseid, $envalue[0]);
								$grd = $grade->grades[$envalue[0]];
								$cgrade=$grd->str_grade;
								$data1=array($counter,$fullname,$email);
								$data2=$gradevalue;
								
								$data3=array($enroldate,$completiondate,$status,$cgrade);
								$tabledata=array_merge($data1,$data2,$data3);
								//print_object($tabledata);
								$usertable->data[]=$tabledata;
								$counter++;
							}

						}
					}

					$html="";
			 		$html.=html_writer::start_div('row m-1');
			 		$html.=html_writer::start_div('col-md-12 p-2 card');
			 		$html.=html_writer::table($usertable);
			 		$html.=html_writer::end_div();
			 		$html.=html_writer::end_div();
			 		// $datatablelink=$CFG->wwwroot.'/local/deptrpts/js/jquery.dataTables.min.js';
			 		// $html.='<script scr="'.$datatablelink.'"></script>';
			 		// $html.= "<script>
		    // 				$('#coursetable').DataTable({
		    //         dom: 'lBfrtip',
		    //         buttons: [
		    //             'excel', 'pdf'
		    //         ]
		    //     	});
						// 	</script>";
			 		return $html;
							

	}

//$activity=list_all_activities_grade(9, 9);
//print_object($activity);die;
function list_all_activities_grade($courseid, $userid = null){
	global $DB,$CFG,$USER;
	if(is_null($userid)){
		$userid = $USER->id;
	}
	$course = $DB->get_record('course', array('id' => $courseid));
	$notgrade = [];
	$modinfo = get_fast_modinfo($course);
	$returnarray=[];
	foreach($modinfo->get_cms() as $cm) {
		$grades = grade_get_grades($courseid, 'mod', $cm->modname, $cm->instance,$userid);
		if(!empty($grades->items)){
			foreach ($grades->items as $gradekey => $gradevalue) {
				if($gradevalue->locked != 1){
				$activityname = $gradevalue->name;
				foreach ($gradevalue->grades as $gkey => $gvalue) {
					$activitygrade = $gvalue->grade;
				}
				$returnarray[] = array('activityname'=>$activityname,'gradeval'=>$activitygrade);
				}

			}
		}
	}
	return $returnarray;
}



