//function for user search.
function userSelect(){
	var userid = document.getElementById("usersearch").value;
    var startdate = document.getElementById("startdate-userselect").value;
    var enddate = document.getElementById("enddate-userselect").value;
    $.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",
		data: 'userid=' + userid + '&userstartdt=' + startdate + '&userenddt=' + enddate,        
        type: "POST",
        success: function (data) {
			$('#ajaxresult').html(data);
            $('#tablecard').show();
        }
    });	
}

$(document).ready(function(){
	var load = 1;
$.ajax({ 
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",
		data:'load='+load,
		type: "POST",
        success: function(data){
  			$('#ajaxresult').html(data);
	
        }
    });
});

//function for user search.
function courseSelect(){
    var courseid = document.getElementById("searchcourse").value;
    var startdate = document.getElementById("startdate_courseselect").value;
    var enddate = document.getElementById("enddate_courseselect").value;
    $.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",
        data: 'courseid=' + courseid + '&coursestartdt=' + coursestartdt + '&courseenddt=' + courseenddt,        
        type: "POST",
        success: function (data) {
             $('#ajaxresult').html(data);
             $('#tablecard').hide();            
        }
    }); 
 }

         
