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

// start of the session
session_start();

// set up connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

$signal = isset($_POST['signal']) ? $_POST['signal'] : '';
$account = isset($_POST['account']) ? $_POST['account'] : '';
$userName = "";
?>
<!DOCTYPE html>
<html>

<head>
    <title>CIRCLE COMMUNITIES</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="header">
        <?php 
            echo "<h4>CIRCLE Communities</h4>";
            if($signal == "guest"){
                echo "<h5><a href='index.html'> Login / Sign up </a></h5>";
            }
            else{
                // Access Account information from DB
                $sql = "SELECT UserName From ACCOUNTS WHERE Email='$account'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $userName = $row['UserName'];
                }
                else{
                    echo '<script language="javascript">';
                    echo 'if(confirm("Cannot Find Your Login Information, Automatically Log Out")) window.location.href="index.html"';
                    echo '</script>';
                }
                // Session information to next page
                $_SESSION['login_account'] = $account;
                $_SESSION['signal'] = "login";
                echo "<h4><a href='profile.php?login_account=". $_SESSION['login_account'] . "&signal=" . $_SESSION['signal'] . "'>$userName</a></h4>";
                echo "<h6><a href='index.html'> Log out </a></h6>";
            }
        ?>  
    </div>

    <div class="display_posts">
        <?php
            $query = "SELECT * FROM POSTS ORDER BY PostTime DESC;";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($title,$message, $postTime, $author);
            echo " <fieldset>";
            echo "<legend>Posts</legend>";
            while($stmt->fetch()){
                // get icon and user name
                $icon_path = "";
                $image_root = "uploads/";
                $sql = "SELECT Path, UserName From ACCOUNTS WHERE Email='$author'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $icon_path = $row['Path'];
                    $userName = $row['UserName'];
                }
                else{
                    echo '<script language="javascript">';
                    echo 'if(confirm("Cannot Find Icon path, Automatically Log Out")) window.location.href="index.html"';
                    echo '</script>';
                }
                $icon_path = $image_root . basename($icon_path);

                // Display posts
                echo "----------------------------------------------------------------<br>";
                echo "---$title<br>";
                echo "--- by <img src= '$icon_path' width=10px />$userName at $postTime <br>";
                echo "$message <br>";
                echo "----------------------------------------------------------------<br>";
            }
            echo "</fieldset>";
        ?>
    </div>




</body>

</html>