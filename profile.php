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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET information from SESSION
$signal = isset($_GET['signal']) ? $_GET['signal'] : '';
$userName = "";

// Check perivious page identity
if ($signal == "login") {
    $account = isset($_GET['login_account']) ? $_GET['login_account'] : '';
} elseif ($signal == "sign_up") {
    $account = isset($_GET['sign_up_account']) ? $_GET['sign_up_account'] : '';
} else {
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
    <!-- 引入 Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- 包括所有已编译的插件 -->
    <script src="js/bootstrap.js"></script>
</head>

<header>

    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" style="font-size: 2.5rem;">CIRCLE - Profile</a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <?php
                if ($signal == "guest") {
                    echo '<button class="btn btn-outline-light my-2 my-sm-0" type="button" href="index.html">Login/Signup</button>';
                } else {
                    // Access Account information from DB
                    $sql = "SELECT UserName From ACCOUNTS WHERE Email='$account'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $userName = $row['UserName'];
                    } else {
                        echo '<script language="javascript">';
                        echo 'if(confirm("Cannot Find Your Login Information, Automatically Log Out")) window.location.href="index.html"';
                        echo '</script>';
                    }
                    // Session information to next page
                    $_SESSION['login_account'] = $account;
                    $_SESSION['signal'] = "login";
                    echo "<a class='btn btn-outline-light my-2 my-sm-0' href='profile.php?login_account=" . $_SESSION['login_account'] . "&signal=" . $_SESSION['signal'] . "'>$userName</a>";
                    echo "<a class='btn btn-outline-light my-2 my-sm-0' href='index.html'> Log out </a>";
                }
                ?>
            </div>
        </nav>
    </div>
</header>

<body>
    <div class="body_godown">
        <div class="container">
            <div class="row">
                <div class="col">
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
                        } else {
                            echo '<script language="javascript">';
                            echo 'if(confirm("Cannot Find Icon path, Automatically Log Out")) window.location.href="index.html"';
                            echo '</script>';
                        }
                        $icon_path = $image_root . basename($icon_path);
                        echo "<label><h1>$userName</h1></label>";

                        echo "<form method='POST' action='change_icon.php' id='form' enctype='multipart/form-data'>";
                        echo "<img src='$icon_path' id='my_icon' width=150px style='cursor:pointer' />";
                        echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
                        ?>
                        <input type='file' accept='.png,.jpg,.jpeg,.gif' name='fileToUpload' id='fileToUpload' style='display:none''/>
        <script type=' text/javascript'> window.onload=function(){ var fileupload=document.getElementById('fileToUpload'); var image=document.getElementById('my_icon'); image.onclick=function(){ fileupload.click(); }; fileupload.onchange=function(){ document.getElementById("form").submit(); }; }; </script> </form> <br>
                        <?php
                        echo "<form  method='POST' action='posts.php' id='form' enctype='multipart/form-data'>";
                        echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
                        echo "<input type='text' id='signal' name='signal' value='login' style='display:none' />";
                        echo "<br>";
                        echo "<br>";
                        echo "<button class='btn btn-primary' type='submit'>View Communities</button>";
                        echo "</form>";
                        echo "<br>";
                        ?>
                    </div>
                </div>

                <div class="col">
                    <form method="POST" action="new_post.php">
                        <div class="form-group" style="width: 400px;">
                            <h3>New Post</h3>
                            <label for="login_account">Title:</label>
                            <input class="form-control" type="text" id="title" name="title" placeholder="Put Title Here..."><br>
                            <textarea class="form-control" rows="5" id="message" name="message" cols="50" placeholder="Write a post..."></textarea><br>
                            <?php
                            echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
                            ?>
                            <button class="btn btn-primary" type="submit" value="POST">POST</button>
                        </div>
                    </form>
                </div>
            </div>
            <br><br>
            <div class="row">
                </br></br></br>
                <div class="account_posts">
                    <?php
                    $query = "SELECT * FROM POSTS WHERE Author='$account' ORDER BY PostTime DESC;";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($title, $message, $postTime, $author);

                    echo "<h3>My Posts</h3>";
                    echo '<ul class="list-group-flush">';

                    while ($stmt->fetch()) {
                        echo '<li class="list-group-item">';
                        echo '<div class="card" style=" width: 50rem;">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title" style="color:#2699FB;font-size: 1.75em;" >' . $title . '</h5>';
<<<<<<< HEAD
<<<<<<< HEAD
                        echo "<h6 class='card-subtitle mb-2 text-muted'>by <img src= '$icon_path' width=30px />$userName at $postTime </h6>";
=======
=======
>>>>>>> parent of 62a760f... finish
                        echo "<form  method='POST' action='delete_post.php' id='form' enctype='multipart/form-data'>";
                        echo "<input type='text' name='title' id='title' value='$title' style='display:none' >";
                        echo "<input type='text' name='account' id='account' value='$account' style='display:none' >";
                        echo "<button class='btn btn-primary' type='submit' value='Delete'>Delete</button>";
                        echo "</form>";
                        echo "<h6 class='card-subtitle mb-2 text-muted'>by <img src= '$icon_path' width=30px />$userName at $postTime </h6>";
                        
<<<<<<< HEAD
>>>>>>> parent of 62a760f... finish
=======
>>>>>>> parent of 62a760f... finish
                        echo "<p class='card-text'>" . nl2br($message). "</p>";
                        echo '</li>';
                    }
                    echo "</ul>";
                    ?>
                </div>
            </div>
        </div>


</body>

</html>