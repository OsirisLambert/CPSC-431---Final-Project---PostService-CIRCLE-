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
// set up connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

// GET information from SESSION
$signal = isset($_GET['signal']) ? $_GET['signal'] : '';
$userName = "";

// Check perivious page identity
if($signal == "login"){
    $account = isset($_GET['login_account']) ? $_GET['login_account'] : '';
} 
elseif($signal == "sign_up"){
    $account = isset($_GET['sign_up_account']) ? $_GET['sign_up_account'] : '';
}
else{
    echo '<script language="javascript">';
	echo 'if(confirm("Cannot Find Your Profile, Automatically Log Out")) window.location.href="index.html"';
	echo '</script>';
}

// Access Account information from DB
$sql = "SELECT UserName From ACCOUNTS WHERE Email='$account'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userName = $row['UserName'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>CIRCLE Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="user_icon">
    <?php 
        // display user name and icon
        $icon_path = "";
        $image_root = "uploads/";
        $sql = "SELECT Path From ACCOUNTS WHERE Email='$account'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $icon_path = $row['Path'];
        }
        else{
            echo '<script language="javascript">';
            echo 'if(confirm("Cannot Find Icon path, Automatically Log Out")) window.location.href="index.html"';
            echo '</script>';
        }
        $icon_path = $image_root . basename($icon_path);
        echo "<h4>$userName</h4>";
        echo "<h6><a href='index.html'> Log out </a></h6>";
        echo "<form method='POST' action='change_icon.php' id='form' enctype='multipart/form-data'>";
        echo "<img src='$icon_path' id='my_icon' width=150px style='cursor:pointer' />";
        echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
    ?>  
        <input type='file' accept='.png,.jpg,.jpeg,.gif' name='fileToUpload' id='fileToUpload' style='display:none''/>
        <script type='text/javascript'>
            window.onload = function(){
                var fileupload = document.getElementById('fileToUpload');
                var image = document.getElementById('my_icon');
                image.onclick = function(){
                    fileupload.click();
                };
                fileupload.onchange = function(){
                    document.getElementById("form").submit();
                };
            };
        </script>
        </form>
        <br>
        <?php
            echo "<form  method='POST' action='posts.php' id='form' enctype='multipart/form-data'>";
            echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
            echo "<input type='text' id='signal' name='signal' value='login' style='display:none' />";
            echo "<input type='submit' value='View Communities'>";
            echo "</form>";
        ?>
    </div>

    <div class="new_post">
        <form method="POST" action="new_post.php">
            <fieldset>
            <legend>New Post</legend>
                <label for="login_account">Title:</label>
                <input type="text" id="title" name="title" placeholder="Put Title Here..."><br>
                <textarea rows="5" id="message" name="message" cols="50"  placeholder="Write a post..."></textarea><br>
                <?php 
                    echo "<input type='text' name='account' id='account' value='$account' style='display:none' >"; 
                ?>
                <input type="submit" value="POST">
            </fieldset>
        </form> 
    </div>

    <div class="account_posts">
        <?php
            $query = "SELECT * FROM POSTS WHERE Author='$account' ORDER BY PostTime DESC;";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($title,$message, $postTime, $author);
            echo " <fieldset>";
            echo "<legend>My Posts</legend>";
            while($stmt->fetch()){
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