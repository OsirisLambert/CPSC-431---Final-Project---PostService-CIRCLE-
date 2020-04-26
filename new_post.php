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
// set up connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

$account = isset($_POST['account']) ? $_POST['account'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// avoid single quate for sql sytax error
$lastPos = 0;
while (($lastPos = strpos($message, "'", $lastPos))!== false) {
    $message = substr_replace($message,"'",$lastPos,0);
    $lastPos += 2; 
}

// Access Account information from DB
$sql = "INSERT INTO POSTS (Title, Message, Author) VALUES ('$title', '$message', '$account');";
if ($conn->query($sql) === TRUE) {
    $_SESSION['account'] = $account;
	$_SESSION['signal'] = "login";
	// Direct to profile page
	header("Location: profile.php?login_account=".$_SESSION['account']."&signal=".$_SESSION['signal']);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>