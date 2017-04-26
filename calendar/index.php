<!DOCTYPE html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- link rel="stylesheet" type="text/css" href="module2.css" /-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<title>Index</title>
<style>
	#main{
		text-align: center;
		
	}
	div#calendar{
		width: 100%;
		padding-left: 5%;
		padding-right:5%;
	}
	table{
		border-collapse: collapse;
		width: 100%;
	}
	th,td{
		width: 13%;
		height: 40px;
		text-align: left;
		margin-left: 5%;
		border-bottom: 1px solid #ddd;
	}
	td:hover{background-color:#f5f5f5}
	.floatpart{
		display: inline-block;
		margin: 10px;
	}
	#premonth,#nextmonth{
		float: left;
		width: 50px;
		border-radius: 50%;
		border: none;
		text-align: center;
		background-color: lightblue;
	}
	#nextmonth{
		float: right;
		width: 50px;
		border-radius: 50%;
		border: none;
		text-align: center;
		background-color: lightblue;
	}
	#todayis{
		text-align: left;
	
	}
	.radiobtn{
		width: 20px;
	}
	#right{
		text-align: right;
	}
	#logout_btn{
		float: right;
		margin-left: 10px;
	}
</style>
</head>
<body>
<div id="main">
<!-- CONTENT HERE -->
<div id="webtitle">
        <p><a href='index.php'><h1>BOOM CALENDAR</h1></a><br>
    </div>
    <div id="logform">
    <?php
        session_start();
		ini_set("session.cookie_httponly", 1);
        if(isset($_SESSION['username'])){
            printf("<br><div id='right'><p>Hi %s!",
				htmlspecialchars($_SESSION['username'])
			);
			echo "<button id='logout_btn' type='button'>Logout</button></p></div><br>";
			echo "	<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='fall' value='all' checked>all
					</label>
					<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='fhome' value='home'>Home
					</label>
					<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='fwork' value='work'>Work
					</label>
					<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='fstudy' value='study'>Study
					</label>
					<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='ffun' value='fun'>Fun
					</label>
					<label>
						<input class='radiobtn' type='radio' name='tagfilt' id='fother' value='other'>Other
					</label>";
//			echo "<a href='logout_ajax.php' id='logout_btn' >Logout</a>";
        }else{
            echo "<input type='text' id='username' placeholder='Username' />";
            echo "<input type='password' id='password' placeholder='Password' />";
            echo "<button id='login_btn' >Log In</button>";
            echo "<button id='register_btn'>Register</button>";
        }
    ?>
    </div>
	<div id="header">
		<div id="hiuser"></div>
		<div id="pagelink"></div>
	</div>
	
	<div id="calendar">
		<div id="todayis"></div>
        <div id="cahead">
            <button id="premonth" class="floatpart"><</button>
            <h3 id="curYM" class="floatpart"></h3>
            <button id="nextmonth" class="floatpart">></button>
        </div>
        <table id=cabody>
            <thead>
                <tr>
                <th>  Monday  </th>
                <th>  Tuesday  </th>
                <th>  Wednesday  </th>
                <th>  Thursday  </th>
                <th>  Friday  </th>
                <th>  Saturday  </th>
                <th>  Sunday  </th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
            </div>
        </table>
	</div>

<!-- add event modal: when click on the date#, means create event at that date -->
	<div class="modal fade" id="addevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel">
						new event
					</h4>
				</div>
				<div class="modal-body">
					<form role="form" id="addeform">
						<div class="form-group">
							<label for="edate">DATE(yyyy-mm-dd)</label>
							<input type="text" class="form-control" name="edate" id="edate">
							<br>
							<label>
								<input class="radiobtn" type="radio" name="etags" id="etaghome" value="home">Home
							</label>
							<label>
								<input class="radiobtn" type="radio" name="etags" id="etagwork" value="work">Work
							</label>
							<label>
								<input class="radiobtn" type="radio" name="etags" id="etagstudy" value="study">Study
							</label>
							<label>
								<input class="radiobtn" type="radio" name="etags" id="etagfun" value="fun">Fun
							</label>
							<label>
								<input class="radiobtn" type="radio" name="etags" id="etagother" value="other" checked>Other
							</label>
							
							<br><label for="etitle">TITLE</label>
							<input type="text" class="form-control" name="etitle" id="etitle"><br>
							<input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token'];?>" />
							<br>
							<select name="addetime" id="addetime">
								<option value="00,01">00:00 -- 01:00</option>
								<option value="01,02">01:00 -- 02:00</option>
								<option value="02,03">02:00 -- 03:00</option>
								<option value="03,04">03:00 -- 04:00</option>
								<option value="04,05">04:00 -- 05:00</option>
								<option value="05,06">05:00 -- 06:00</option>
								<option value="06,07">06:00 -- 07:00</option>
								<option value="07,08">07:00 -- 08:00</option>
								<option value="08,09">08:00 -- 09:00</option>
								<option value="09,10">09:00 -- 10:00</option>
								<option value="10,11">10:00 -- 11:00</option>
								<option value="11,12">11:00 -- 12:00</option>
								<option value="12,13">12:00 -- 13:00</option>
								<option value="13,14">13:00 -- 14:00</option>
								<option value="14,15">14:00 -- 15:00</option>
								<option value="15,16">15:00 -- 16:00</option>
								<option value="16,17">16:00 -- 17:00</option>
								<option value="17,18">17:00 -- 18:00</option>
								<option value="18,19">18:00 -- 19:00</option>
								<option value="19,20">19:00 -- 20:00</option>
								<option value="20,21">20:00 -- 21:00</option>
								<option value="21,22">21:00 -- 22:00</option>
								<option value="22,23">22:00 -- 23:00</option>
								<option value="23,24">23:00 -- 24:00</option>
							</select><br>
							<label for="edesc">DESCRIPTION</label><br>
							<textarea class="form-control" rows="2" form="addeform" name="edesc" id="edesc"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<!--button type="button" class="btn btn-default" data-dismiss="modal">关闭
					</button>-->
					<button type="button" class="btn btn-primary" id="addevent_btn" data-dismiss="modal">
						Add
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>

<!-- edit event modal: when click on the event#, means edit this event -->	
	<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel2">
						Edit Event
					</h4>
				</div>
				<div class="modal-body">
					<form role="form" id="editeform">
						<div class="form-group">
							<label for="eetime">DATE(yyyy-mm-dd)</label>
							<input type="text" class="form-control" name="eetime" id="eetime"/>
							<br>
							<label>
								<input type="radio" name="eetags" id="eetaghome" value="home"/>Home
							</label>
							<label>
								<input type="radio" name="eetags" id="eetagwork" value="work"/>Work
							</label>
							<label>
								<input type="radio" name="eetags" id="eetagstudy" value="study"/>Study
							</label>
							<label>
								<input type="radio" name="eetags" id="eetagfun" value="fun"/>Fun
							</label>
							<label>
								<input type="radio" name="eetags" id="eetagother" value="other" checked/>Other
							</label>
							
							<br><label for="eetitle">TITLE</label>
							<input type="text" class="form-control" name="eetitle" id="eetitle"/><br>
							<input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token'];?>" />
							<br>
							<select name="eaddetime" id="eaddetime">
								<option value="00,01">00:00 -- 01:00</option>
								<option value="01,02">01:00 -- 02:00</option>
								<option value="02,03">02:00 -- 03:00</option>
								<option value="03,04">03:00 -- 04:00</option>
								<option value="04,05">04:00 -- 05:00</option>
								<option value="05,06">05:00 -- 06:00</option>
								<option value="06,07">06:00 -- 07:00</option>
								<option value="07,08">07:00 -- 08:00</option>
								<option value="08,09">08:00 -- 09:00</option>
								<option value="09,10">09:00 -- 10:00</option>
								<option value="10,11">10:00 -- 11:00</option>
								<option value="11,12">11:00 -- 12:00</option>
								<option value="12,13">12:00 -- 13:00</option>
								<option value="13,14">13:00 -- 14:00</option>
								<option value="14,15">14:00 -- 15:00</option>
								<option value="15,16">15:00 -- 16:00</option>
								<option value="16,17">16:00 -- 17:00</option>
								<option value="17,18">17:00 -- 18:00</option>
								<option value="18,19">18:00 -- 19:00</option>
								<option value="19,20">19:00 -- 20:00</option>
								<option value="20,21">20:00 -- 21:00</option>
								<option value="21,22">21:00 -- 22:00</option>
								<option value="22,23">22:00 -- 23:00</option>
								<option value="23,24">23:00 -- 24:00</option>
							</select><br>
							<input type="hidden" id="eeid" name="eeid"/>
							<label for="eedesc">DESCRIPTION</label><br>
							<textarea class="form-control" rows="2" form="editeform" name="eedesc" id="eedesc"></textarea>
						</div>
						<div class="email">
							<label for="sendto">Send to (email address)</label>
							<input type="text" class="form-control" name="sendto" id="sendto"/>
							</div>
					</form>
				</div>
				<div class="modal-footer">
					<!--button type="button" class="btn btn-default" data-dismiss="modal">关闭
					</button>-->
					<button type="button" class="btn btn-primary" id="sendemail" data-dismiss="modal">
						Send
					</button>
					<button type="button" class="btn btn-primary" id="editevent_btn" data-dismiss="modal">
						Edit
					</button>
					<button type="button" class="btn btn-primary" id="delevent_btn" data-dismiss="modal">
						Delete
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
	
</div>
<script type="text/javascript" src="calendar.js"></script>
</body>
</html>