<?php
include('database_connection.php');
session_start();
if(!isset($_SESSION['user_id'])){
	header("location:login.php");
}
?>

<html>  
    <head>  
        <title>vashishth121</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<!-- link rel="icon" type="image/png" href="images/me.jpg" /> -->
		<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
		<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
		<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
		<link rel="stylesheet" type="text/css" href="css/util.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<style type="text/css">
				body{
      			content: "";
      			/* background-image: url('images/me.jpg'); */
      			background-color: lightyellow;
				background-repeat: no-repeat;
				background-attachment: fixed;
  				background-position:  center top;
  				background-size: cover;
			      position: absolute;
			      top: 0px;
			      right: 0px;
			      bottom: 0px;
			      left: 0px;
			      
				}
		</style>
    </head>  
	<body>  
		<br>
		<center><h3><font face="verdana" size="4">Created By Vashishth121</font></h3><br/><center><font face="verdana" size="4">Follow me on <a href="https://www.instagram.com/vashishthchaudhary/">instagram</a></font></center>
	<center><font face="verdana" size="4">Follow me on <a href="https://www.linkedin.com/in/vashishth-patel-312a52204">linkedin</a></font></center>
				<font size="4">HI - <?php echo $_SESSION['username'];  ?></font>
				<div class="table100" style="height: 100px;width:400px;">
				
					<div class="table100-head">
						<table style="height: 100px;width:400px;">
							<thead>
								<tr class="row100 head" bgcolor="lightgray">
									<th><center><font size="4" face="verdana">Chat With Your Favourite</font></center></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="table100-body js-pscroll">
						<table>
							<tbody>
								<tr class="row100 body">
									<div id="user_details" style="background-color: lightgray;"></div>
									<div id="user_model_details"></div>
								</tr>
								<tr><font size="4" face="verdana"><a href="logout.php">Logout</a></font></tr>
							</tbody>
						</table>
					</div>
				</div>
</center>
	</body>
</html>  

<script>  
$(document).ready(function(){
 fetch_user();
 setInterval(function(){
	update_last_activity();
	fetch_user();
 }, 900); //set your refresh time according to your choice... for faster response...
 function fetch_user() {
	$.ajax({
		url:"fetch_user.php",
		method:"POST",
		success:function(data){
			$('#user_details').html(data);
		}
	})
 }
 function update_last_activity() {
	$.ajax({
		url:"update_last_activity.php",
		success:function(){}
	})
 }
function make_chat_dialog_box(to_user_id, to_user_name) {
	var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" style="background-color:hsl(350, 100%, 98%);" title="You have chat with '+to_user_name+'">';
	modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
	modal_content += '</div>';
	modal_content += '<div class="form-group">';
	modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control xd" style="font-family: verdana;font-size: 12px;"></textarea>';
	modal_content += '</div><div class="form-group" align="right">';
	modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat"><font size="3">Send</font></button></div></div>';
	$('#user_model_details').html(modal_content);
}
$(document).on('click', '.start_chat', function(){
	var to_user_id = $(this).data('touserid');
	var to_user_name = $(this).data('tousername');
	make_chat_dialog_box(to_user_id, to_user_name);
	
	$("#user_dialog_"+to_user_id).dialog({
		autoOpen:false,
		width:400
	});
	$('#user_dialog_'+to_user_id).dialog('open');
		setInterval(function(){ // set interval for take messages for every 1 seconds and show into chat 
			refreshMsg();
		}, 200); // <---- here 
function refreshMsg() {
	var hvatajPoruke = $.ajax({
		  url: "take_msg.php",
		  method: "POST",
		  data:{to_user_id:to_user_id},
		  success: function(data) {
			  $('#chat_history_'+to_user_id).html(data);
		  }
	 })
}
});
 //abort ajax call if user close chat :) 
$( ".selector" ).on( "dialogclose", function( event, ui ) {
		//alert("todo - later");
});
$(document).on('click', '.send_chat', function(){
	$.playSound("mp3/click.mp3") // here we set MP3 SOUND FOR "send" button click
	var to_user_id = $(this).attr('id');
	var chat_message = $('#chat_message_'+to_user_id).val();
	$.ajax({
			url:"insert_chat.php",
			method:"POST",
			data:{to_user_id:to_user_id, chat_message:chat_message},
		success:function(data){
			$('#chat_message_'+to_user_id).val('');
			$('#chat_history_'+to_user_id).html(data);
		}
	})
 });
 
});  
</script>
