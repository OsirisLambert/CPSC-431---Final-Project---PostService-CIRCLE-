<?php
/*
// server information
$servername = "mariadb";
$username = "cs431s44";
$password = "gengo9Ni";
$dbname = "cs431s44";
*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proj1";

//start of the session
session_start();

// set up connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

// POST variable from html form
$signal = "sign_up";
$sign_up_account = isset($_POST['sign_up_account']) ? $_POST['sign_up_account'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$sign_up_password = isset($_POST['sign_up_password']) ? $_POST['sign_up_password'] : '';
// Password Encryption
$hash = password_hash($sign_up_password, PASSWORD_DEFAULT);
// Store to DB
$sql = "INSERT INTO ACCOUNTS(Email, UserName, PasswordHash) VALUES ('$sign_up_account', '$username', '$hash');";
if ($conn->query($sql) === TRUE) {
	// Session information to next page
	$_SESSION['sign_up_account'] = $sign_up_account;
	$_SESSION['signal'] = $signal;
	// Direct to profile page
	header("Location: profile.php?sign_up_account=".$_SESSION['sign_up_account']."&signal=".$_SESSION['signal']);
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}
?>