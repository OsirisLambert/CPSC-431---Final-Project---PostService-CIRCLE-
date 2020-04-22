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

$title = isset($_POST['title']) ? $_POST['title'] : '';
$account = isset($_POST['account']) ? $_POST['account'] : '';
$sql = "DELETE FROM POSTS WHERE Title='$title' AND Author='$account';";
if ($conn->query($sql) === TRUE){
	$_SESSION['login_account'] = $account;
	$_SESSION['signal'] = 'login';
	// Direct to profile page
	header("Location: profile.php?login_account=".$_SESSION['login_account']."&signal=".$_SESSION['signal']);
}
else{
	echo '<script language="javascript">';
	echo 'if(confirm("Fail to delete, Please try again")) window.history.go(-1)';
	echo '</script>';
}
?>