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


// Store image in /uploads and path in DB
$target_dir = "uploads/";
$path = isset($_FILES["fileToUpload"]["name"]) ? $_FILES["fileToUpload"]["name"] : '';
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
    // Delete old icon
    $old_path = "";
    $sql = "SELECT Path FROM ACCOUNTS WHERE Email='$account';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $old_path = $row['Path'];
    }
    if($old_path!="default_icon.png"){
        $old_path = $target_dir . basename($old_path);
        unlink($old_path);
    }
    
    // Update new icon
    $sql = "UPDATE ACCOUNTS SET Path='$path' WHERE Email='$account';";
    if($conn->query($sql)){
        // Session information to next page
		$_SESSION['account'] = $account;
		$_SESSION['signal'] = "login";
		// Direct to profile page
        header("Location: profile.php?login_account=".$_SESSION['account']."&signal=".$_SESSION['signal']);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo '<script language="javascript">';
	echo 'if(confirm("Fail to upload, Please try again")) window.history.go(-1)';
	echo '</script>';
}

?>