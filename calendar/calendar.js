
var d=new Date();
var curyear= d.getFullYear();
var curmonth=d.getMonth();
var today=d.getFullYear()+"-"+pad(d.getMonth()+1)+"-"+pad(d.getDate());
//alert(document.getElementById("todayis"));
document.getElementById("todayis").innerHTML = "Today is "+today;
//alert(typeof document.getElementsByName("tagfilt")[0] );
if (typeof document.getElementsByName("tagfilt") !=="undefined") {
    var tagfilt = document.getElementsByName("tagfilt");
//    alert(tagfilt.length);
}



// draw calendar
function updatecalendar(year,month) {
   
    var firstday=new Date(year, month, 1);
    var lastday=new Date(year, month+1, 0); 
    var days=lastday.getDate();   //how many days in this month
    var fwday=firstday.getDay();    // what day of the first day
    if (fwday===0) { //it's Sunday
        fwday=fwday+7;
    }
//    checklog();
//    alert("status is "+status);
     
// show current year and month
    var curYM=year+"/"+(month+1);
    var elem = document.getElementById("curYM");
    if(typeof elem !== 'undefined' && elem !== null) {
        document.getElementById("curYM").innerHTML=curYM;
    }
    
// clear original calender    
    var tchild=document.getElementById("tbody").firstChild;
    if (typeof tchild !== 'undefined' && tchild !== null){
        while (tchild) {
            document.getElementById("tbody").removeChild(tchild);
            tchild=document.getElementById("tbody").firstChild;
        }
    }
    
    var weekcount=0;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "checklogin.php", true);
    xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
//		var status;
        if(!jsonData.status){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
//            status=true;
//            alert(jsonData.status);
            for (var i=1; i<days+fwday; i++) {
        // set weeks
 //       document.getElementById("test").innerHTML=i;
                if (i%7==1) {
                    weekcount+=1;
//                alert(weekcount);
                    var week=document.createElement("tr");
                    week.setAttribute("id", "week"+weekcount);
                    document.getElementById("tbody").appendChild(week);
                }
        
                if (i<fwday) {
                    var emptybox=document.createElement("td");
                    document.getElementById("week"+weekcount).appendChild(emptybox);
                }else{
                    var iday=i-fwday+1;
                    var daybox=document.createElement("td");
                    //code
                    var boxcont=document.createTextNode(iday);
                    daybox.appendChild(boxcont);
                    daybox.setAttribute("id", year+"-"+pad(month+1)+"-"+pad(iday));
                    daybox.setAttribute("iday", iday);
                    daybox.setAttribute("imonth", month+1);
                    daybox.setAttribute("iyear", year);
                    
                    if (today.localeCompare(year+"-"+pad(month+1)+"-"+pad(iday))===0) {
//                        alert("today="+today);
                        daybox.style.color = "red";
                    }
                    document.getElementById("week"+weekcount).appendChild(daybox);
                }
        
            }
		}else{
//            status=false;
//            alert(jsonData.status);
            for (var j=1; j<days+fwday; j++) {
        // set weeks
 //       document.getElementById("test").innerHTML=i;
                if (j%7==1) {
                    weekcount+=1;
//                alert(weekcount);
                    var lweek=document.createElement("tr");
                    lweek.setAttribute("id", "week"+weekcount);
                    document.getElementById("tbody").appendChild(lweek);
                }
        
                if (j<fwday) {
                    var lemptybox=document.createElement("td");
                    document.getElementById("week"+weekcount).appendChild(lemptybox);
                }else{
                    var liday=j-fwday+1;
                    var ldaybox=document.createElement("td");
                    //code
                    var btnlink=document.createElement("a");
                    btnlink.setAttribute("data-toggle","modal");
                    btnlink.setAttribute("data-target", "#addevent");
                    btnlink.setAttribute("href","#addevent");
                    btnlink.setAttribute("id", year+"-"+pad(month+1)+"-"+pad(liday));
                    btnlink.setAttribute("class", "addeventdate");
                    btnlink.setAttribute("data-id", year+"-"+pad(month+1)+"-"+pad(liday));
                    btnlink.setAttribute("iday", liday);
                    btnlink.setAttribute("imonth", month+1);
                    btnlink.setAttribute("iyear", year);
                    btnlink.innerHTML=liday;
                    if (today.localeCompare(year+"-"+pad(month+1)+"-"+pad(liday))===0) {
//                        alert("today="+today);
                        ldaybox.style.backgroundColor = "lightblue";
                    }
                    ldaybox.appendChild(btnlink);
                
                    document.getElementById("week"+weekcount).appendChild(ldaybox);
                }
        
            }
            
            showevent(year,month+1);
        }        
        
	}, false); // Bind the callback to the load event
	xmlHttp.send(null); // Send the data
    
}

// show event
//function showevent(year, month, filterkey="all") {
function showevent(year, month) {
//    alert("show event function");
            var filterkey="all";
            if (tagfilt[1].checked) {
                filterkey="home";
            }else if (tagfilt[2].checked) {
                filterkey="work";
            }else if (tagfilt[3].checked) {
                filterkey="study";
            }else if (tagfilt[4].checked) {
                filterkey="fun";
            }else if (tagfilt[5].checked) {
                filterkey="other";
            }
            //alert(filterkey);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    var dataString = "year=" + encodeURIComponent(year)+"&month=" + encodeURIComponent(month)+ "&fliter=" + encodeURIComponent(filterkey);
	xmlHttp.open("POST", "showevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
//        alert("here show event!"+ jsonData.length); //eid, etitle, etag, etime
		if(jsonData.length>0){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
//			alert(jsonData.length);
            for (var i=0; i<jsonData.length; i++) {
                var etime=jsonData[i].etime;
                var brtag = document.createElement("br");
                document.getElementById(etime).parentElement.appendChild(brtag);
                var eventbtn = document.createElement("a");
                eventbtn.setAttribute("class", "editeventdate");
                eventbtn.setAttribute("data-id", jsonData[i].eid);
                eventbtn.setAttribute("data-toggle","modal");
                eventbtn.setAttribute("data-target", "#editevent");
                eventbtn.setAttribute("href","#editevent");
                eventbtn.setAttribute("etag", jsonData[i].etag);
                eventbtn.setAttribute("data-etime",jsonData[i].etime);
                eventbtn.innerHTML=jsonData[i].etitle;
                document.getElementById(etime).parentElement.appendChild(eventbtn);
                
//                alert(eid+"--"+etag+"--"+etime+"--"+etitle+"--"+box);
//                var box=document.getElementById(etime);

            }
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}

// auto fill edit-event form
$(document).on("click", ".editeventdate", function(){
    var eid =  $(this).data('id');
//    alert("eid="+eid);
    var dataString = "eid="+ encodeURIComponent(eid);
    var xmlHttp = new XMLHttpRequest(); 
    xmlHttp.open("POST", "getdesc_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
//        alert("here show event!"+ jsonData.length); //eid, etitle, etag, etime
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
            $(".modal-body #eetitle").val(jsonData.etitle );
            $(".modal-body #eetime").val(jsonData.etime );
            $("input[type=radio][value="+jsonData.etag+"]").attr("checked",'checked' );
//            alert("editetime is "+jsonData.editetime);
            $("#eaddetime").val(jsonData.editetime);
//            alert("description="+jsonData.edesc);
            if (jsonData.edesc !== null) {
                $(".modal-body #eedesc").val( jsonData.edesc );
            }
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
    $(".modal-body #eeid").val( eid );
});

// turn 1 to 01
function pad(d) {
    return (d < 10) ? '0' + d.toString() : d.toString();
}

// last month
function premonth() {
    d= new Date(curyear,curmonth-1);
    curmonth=d.getMonth();
    curyear=d.getFullYear();
    updatecalendar(curyear,curmonth);
}

function nextmonth() {
    d= new Date(curyear,curmonth+1);
    curmonth=d.getMonth();
    curyear=d.getFullYear();
    updatecalendar(curyear,curmonth);
}

// register
function registerajax() {
    var username = document.getElementById("username").value; // Get the username from the form
	var password = document.getElementById("password").value; // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
 
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "register_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
//            document.getElementById("testout").innerHTML="You've been Registed!";
			alert("You've been Registed!");
//            state = true;
            location.reload();
		}else{
//            document.getElementById("testout").innerHTML=jsonData.message;
			alert("You were not registed.  "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}

// login
function loginajax() {
    var username = document.getElementById("username").value; // Get the username from the form
	var password = document.getElementById("password").value; // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
 
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "login_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You've been Logged In! ");
//            state = true;
//            alert(state);
            location.reload();
		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}

function logoutajax() {
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "logout_ajax.php", true);
    xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You've been Logged out!");
//            state = false;
//            alert(state);
            location.reload();
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(null); // Send the data
    
}


function addevent(){
//    alert("addevent function");
    var edate=document.getElementById("edate").value;
    var etitle=document.getElementById("etitle").value;
    var tags=document.getElementsByName("etags");
//    alert ("token is "+document.getElementById("token"));
    var token = document.getElementById("token").value;
    var etime = document.getElementById("addetime").value;
    var etags="other";
    if (tags[0].checked) {
        etags="home";
    }else if (tags[1].checked) {
        etags="work";
    }else if (tags[2].checked) {
        etags="study";
    }else if (tags[3].checked) {
        etags="fun";
    }else{
        etags="other";
    }
    
//    alert(etags);
    var edesc=document.getElementById("edesc").value;
//    alert(edesc);
    var dataString = "edate=" + encodeURIComponent(edate) + "&etime=" + encodeURIComponent(etime) + "&token=" + encodeURIComponent(token) + "&etitle=" + encodeURIComponent(etitle) + "&etags=" + encodeURIComponent(etags) + "&edesc=" + encodeURIComponent(edesc);
//    alert("dataString "+dataString);
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "addevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
//        alert(event.target.responseText);
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
//        alert("come here22");
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("add new event!");
            updatecalendar(curyear,curmonth);
//            alert(state);
            
		}else{
			alert(jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
    
}

// auto fill the date in add event form
$(document).on("click", ".addeventdate", function () {
     var autodate = $(this).data('id');
     $(".modal-body #edate").val( autodate );
     // As pointed out in comments, 
     // it is superfluous to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});

// edit event
function editevent() {
    var eetime=document.getElementById("eetime").value;
    var eetitle=document.getElementById("eetitle").value;
    var tags=document.getElementsByName("eetags");
    var eeid=document.getElementById("eeid").value;
    var token = document.getElementById("token").value;
    var addetime = document.getElementById("eaddetime").value;
 //   alert("new etime is "+ addetime);
    var eetags="other";
    if (tags[0].checked) {
        eetags="home";
    }else if (tags[1].checked) {
        eetags="work";
    }else if (tags[2].checked) {
        eetags="study";
    }else if (tags[3].checked) {
        eetags="fun";
    }else{
        eetags="other";
    }
//    alert(eetitle);
//    alert(etags);
    var eedesc=document.getElementById("eedesc").value;
//    alert(edesc);
    var dataString = "etime=" + encodeURIComponent(eetime) + "&addetime=" + encodeURIComponent(addetime) + "&token=" + encodeURIComponent(token) + "&etitle=" + encodeURIComponent(eetitle) + "&eid=" + encodeURIComponent(eeid) + "&etags=" + encodeURIComponent(eetags) + "&edesc=" + encodeURIComponent(eedesc);
//    alert (edate);
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "editevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
//       alert(event.target.responseText);
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
//        alert(event.target.responseText);
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("Edit success!");
            
            
		}else{
			alert(jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
    updatecalendar(curyear,curmonth);
}

function delevent() {
    var eeid=document.getElementById("eeid").value;
    var dataString = "eid=" + encodeURIComponent(eeid);
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "delevent_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            alert("Delete success!");
            
        }else{
            alert("Delete failed...");
        }
    }, false);
    xmlHttp.send(dataString);
    updatecalendar(curyear,curmonth);
}

function sendemail() {
    var eetime=document.getElementById("eetime").value;
    var eetitle=document.getElementById("eetitle").value;
    var tags=document.getElementsByName("eetags");
//    var eeid=document.getElementById("eeid").value;
    var eetags="other";
    if (tags[0].checked) {
        eetags="home";
    }else if (tags[1].checked) {
        eetags="work";
    }else if (tags[2].checked) {
        eetags="study";
    }else if (tags[3].checked) {
        eetags="fun";
    }else{
        eetags="other";
    }
    var eedesc=document.getElementById("eedesc").value;
    var address=document.getElementById("sendto").value;
    var etime=document.getElementById("eaddetime").value;
    var etsplit=etime.split(",");
    var timestr=etsplit[0]+":00--"+etsplit[1]+":00";
//    alert("timestr="+timestr);
    var body = "Title: "+ eetitle + "\n"+"Date: "+ eetime + "\n"+"Time: "+ timestr + "\n"+"Tag: "+ eetags + "\n"+"Description: "+ eedesc + "\n";
    var link = "mailto:"+address+"?subject="+eetitle+"&body="+encodeURIComponent(body);
    window.location.href = link;
}

document.addEventListener("DOMContentLoaded", updatecalendar(curyear,curmonth), false);
document.getElementById("premonth").addEventListener("click", premonth, false);
document.getElementById("nextmonth").addEventListener("click", nextmonth, false);
$("#login_btn").click(loginajax);
$("#editevent_btn").click(editevent);
$("#addevent_btn").click(addevent);
$("#sendemail").click(sendemail);
$("#delevent_btn").click(delevent);
$("#logout_btn").click(logoutajax);
$("#register_btn").click(registerajax);

//document.getElementById("login_btn").addEventListener("click", loginajax, false); // Bind the AJAX call to button click
//document.getElementById("register_btn").addEventListener("click", registerajax, false); // Bind the AJAX call to button click
//alert("ha" + tagfilt.length);
if (tagfilt.length){
    $("input[name=tagfilt]:radio").change(function () {
        updatecalendar(curyear,curmonth);
    });
}
//    alert("different" + tagfilt.length);
    

    

//document.getElementById("addevent_btn").addEventListener("click", addevent, false);
//"logout_btn").addEventListener("click", function(){alert("call logout!");}, false);
//$("#logform").on("click", "#logout_btn", logoutajax);