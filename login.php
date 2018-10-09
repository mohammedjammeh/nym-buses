<?php
	$pageTitle = "NYM Buses Admin Area.";
	include("inc/header.php"); 

	//ADMIN LOGIN
	if ($_SERVER["REQUEST_METHOD"] == "POST") { 
		if(isset($_POST["login"])) { 
			$username = $_POST["username"];
			$password = $_POST["password"];

			//makes sure all of admin's login details are filled in
			if(!empty($username) && !empty($password)) {

				//gets the admin's login info from SQL database
				$sql = "SELECT username, password FROM admin";
				$result = mysqli_query($con, $sql);

				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
			        	$adminUsername = $row["username"];
			        	$adminPassword = $row["password"];
			        }
				}

				//compares login details from user and database
				if($username == $adminUsername && $password == $adminPassword) {
					$_SESSION['admin_id'] = $adminUsername;
					header('Location: admin.php');
				} else {
					$adminMessage = "Please enter the right log in details.";
				}


			} else {
				$adminMessage = "Please fill in all required fields to log in.";
			}
		}
	}


?>

<!-- ADMIN LOGIN PAGE -->
<div class="admin_login">
	<p>Please log in to be able to add services to NYM Buses Transport.</p>
	<form method="POST">
		<p><?php if (isset($adminMessage)) { echo $adminMessage; } ?></p> 
		<input type="text" name="username" value="" placeholder="Username..">
		<input type="password" name="password" value="" placeholder="Password..">
		<input type="submit" name="login" value="Log In">
	</form>
</div>

<?php include("inc/footer.php"); ?>