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

if ($conn->connect_error) {
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
    <!-- import Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- include all js -->
    <script src="js/bootstrap.js"></script>
</head>

<header>

    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" style="font-size: 2.5rem;">CIRCLE - Communities</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <?php
                if ($signal == "guest") {
                    echo '<a class="btn btn-outline-light my-2 my-sm-0" type="button" href="index.html">Login/Signup</a>';
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
            <label>
                <h1>Posts</h1>
            </label>
                <?php
                $query = "SELECT * FROM POSTS ORDER BY PostTime DESC;";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($title, $message, $postTime, $author);

                //echo "<legend>Posts</legend>";
                echo '<ul class="list-group-flush">';
                while ($stmt->fetch()) {
                    // get icon and user name
                    $icon_path = "";
                    $image_root = "uploads/";
                    $sql = "SELECT Path, UserName From ACCOUNTS WHERE Email='$author'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $icon_path = $row['Path'];
                        $userName = $row['UserName'];
                    } else {
                        echo '<script language="javascript">';
                        echo 'if(confirm("Cannot Find Icon path, Automatically Log Out")) window.location.href="index.html"';
                        echo '</script>';
                    }
                    $icon_path = $image_root . basename($icon_path);

                    // Display posts
                    echo '<li class="list-group-item">';
                    echo '<div class="card" style=" width: 50rem;">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title" style="color:#2699FB;font-size: 1.75em;" >' . $title . '</h5>';
                    echo "<h6 class='card-subtitle mb-2 text-muted'>by <img src= '$icon_path' width=30px />$userName at $postTime </h6>";
                    echo "<p class='card-text'>" . nl2br($message) . "</p>";
                    echo '</li>';
                }
                echo '</ul>';
                ?>
        </div>
    </div>




</body>

</html>