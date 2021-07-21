<?php
include('database_connection.php');
session_start();
$message = '';

if(isset($_SESSION['user_id'])) {
	header('location:index.php');
}

if(isset($_POST["login"])) {
	if(isset($_POST['username']) != "") {
		$query = "SELECT * FROM login WHERE username = :username";
		$statement = $connect->prepare($query);
		$statement->execute(array(':username' => $_POST["username"]));
		$count = $statement->rowCount();
			if($count > 0) {
					$result = $statement->fetchAll();
				foreach($result as $row){
					if($_POST["password"] == $row["password"]) {
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['username'] = $row['username'];
						$sub_query = "INSERT INTO login_details (user_id) VALUES ('".$row['user_id']."')";
						$statement = $connect->prepare($sub_query);
						$statement->execute();
						$_SESSION['login_details_id'] = $connect->lastInsertId();
						header("location:index.php");
						sleep(2); // to show gif loading icon
					} else {
						$message = "<label>Wrong Password</label>";
					}
				}
			} else {
				$message = "<label>Wrong Username</labe>";
			}
	} else {
		$message = "<label>Enter Username</labe>";
	}
}
?>

<html>
	<head>
		<title>Vashishth Patel</title>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<!-- <link rel="icon" type="image/png" href="images/me.jpg"/> -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<link rel="stylesheet" href="css/style.css">
		<style type="text/css">
			.profile-img{
				border-radius: 10%;
			}
			body{
				
				background-color: lightyellow;
				
			}

			.login-title{
				background-color: lightgray;
			}
		</style>
	</head>
	
<body>
	<br><br><br>
	<center><font face="verdana" size="4">Created By vashishth</font></center>
	<center><font face="verdana" size="4">Follow me on <a href="https://www.instagram.com/vashishthchaudhary/">instagram</a></font></center>
	<center><font face="verdana" size="4">Follow me on <a href="https://www.linkedin.com/in/vashishth-patel-312a52204">linkedin</a></font></center>
<br><br><br><br>
<center><h1>Login</h1></center><br><br><br>
	<div>
		<div>
			<div class="col-sm-2 col-md-4 col-md-offset-4">
				<div>
					<form method="POST" class="form-signin">
						
					<input type="text" name="username" class="form-control" placeholder="Username"  autofocus autocomplete="off"><br>
					<input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
					<button class="btn btn-lg btn-primary btn-block" id="login" name="login" type="submit">
						Sign in</button>
					
					<label class="checkbox pull-left">
						<input type="checkbox" value="remember-me">
						Remember me
					</label>
					<a href="http://vashishth.epizy.com/" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
					</form>
					<center><font size="4"><p class="text-danger font"><?php echo $message; ?></p></font></center>
				</div>
			</div>
		</div>
	</div>
	<br/>
</body>
<script>
		$(function(){
			$("#login").click(function(){
				$(this).after("<br /><br /><center><img src='images/prijava.gif' width='25px' alt='loading' />").fadeIn();   // loader icon
			});
		});
</script>  
</html>
