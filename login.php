<?php

// server information
$servername = "mariadb";
$username = "cs431s44";
$password = "gengo9Ni";
$dbname = "cs431s44";

/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proj1";
*/

// start of the session
session_start();

// set up connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

// POST variable from html form
$signal = "login";
$login_account = isset($_POST['login_account']) ? $_POST['login_account'] : '';
$login_password = isset($_POST['login_password']) ? $_POST['login_password'] : '';
// Retrive Correct Password hash from DB
$sql = "SELECT PasswordHash FROM ACCOUNTS WHERE Email='$login_account'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	// Check Password 
	if(password_verify($login_password, $row['PasswordHash'])){
		// Session information to next page
		$_SESSION['login_account'] = $login_account;
		$_SESSION['signal'] = $signal;
		// Direct to profile page
		header("Location: profile.php?login_account=".$_SESSION['login_account']."&signal=".$_SESSION['signal']);
	} else {
		echo '<script language="javascript">';
		echo 'if(confirm("Invalid Password, Please try again")) window.history.go(-1)';
		echo '</script>';
	}
} 
// Acount does not exist
else {
	echo '<script language="javascript">';
	echo 'if(confirm("Account Does Not Exist, Please try again")) window.history.go(-1)';
	echo '</script>';
}
?>