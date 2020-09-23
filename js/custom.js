 $(document).ready(function () {
        $('#usersearch').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
        
    });
 

  $(document).ready(function () {
        $('#searchcourse').typeahead({
            source: function (query, result) {
                //alert(query);
                $.ajax({
                    url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",
                    data: 'coursename=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
                        result($.map(data, function (item) {
                            return item;
                        }));
                    }
                });
            }
        });
        
    });
 
//declare the function.
function startdatedropdown(){
	var startdate = document.getElementById("start-date-input").value;
	var enddate = document.getElementById("end-date-input").value;
	var category = document.getElementById("category-drop").value;
	//var department = document.getElementById("department-drop").value;
	//var role=document.getElementById("role-drop").value;
	var city=document.getElementById("city-drop").value;
	$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
             $('#ajaxresult').html(result);
             
             //alert(result);
           }
         });
}

 function enddatedropdown(){
 	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	//var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
	$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
            $('#ajaxresult').html(result);
            
	
             //alert(result);
           }
         });
 }

 function categorydropdown(){
 	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	//var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
            $('#ajaxresult').html(result);

             //alert(result);
           }
         });
 }

 function departmentdrop(){
 	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	///var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
             $('#ajaxresult').html(result);
            

             //alert(result);
           }
         });
 }

 function userdropdown(){
  	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	//var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
            $('#ajaxresult').html(result);
            
	
             //alert(result);
           }
         });
 }

 function roledropdown(){
 	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	//var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
            $('#ajaxresult').html(result);
            
	
             //alert(result);
           }
         });
 }

 function citydropdown(){
 	var startdate = document.getElementById("start-date-input").value;
 	var enddate = document.getElementById("end-date-input").value;
 	var category = document.getElementById("category-drop").value;
 	//var department = document.getElementById("department-drop").value;
 	//var role=document.getElementById("role-drop").value;
 	var city=document.getElementById("city-drop").value;
$.ajax({
        url: M.cfg.wwwroot+"/local/deptrpts/ajax.php",       
		data: {startdate:startdate, enddate:enddate, category:category},     
		type: "POST",
            success:function(result){
            $('#ajaxresult').html(result);

           

             //alert(result);
           }
         });
 }
