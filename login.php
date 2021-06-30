<?php
include 'connection.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user = $_POST['Username'];
settype($user, "string");
$user = str_replace("'", "\"", $user);
$pass = $_POST['Password'];
settype($pass, "string");
$pass = str_replace("'", "\"", $pass);
echo("testt");
if ($user != null) {
    echo($user);
    $sql = "SELECT * FROM login WHERE username = '$user';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $salt = $row["salt"];
            echo($salt);
            if (hash('sha256', $salt . $pass, false) === $row["password"]) {
                $_SESSION['password'] = $pass;
                $_SESSION['username'] = $user;
                $_SESSION['salt'] = $salt;
                header("Location: logedin");
            }
        }
    }
}

?>

<link rel="stylesheet" href="login_style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<html>

<body>
    <form class="form-signin" action="" method="post">
        <div>
            <h1 class="h3 mb-3 fw-normal">Welcome</h1>
        </div>
        <hr>
        <div class="d-grid mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingUsername" name="Username" placeholder="Name">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="Username" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">Login</button>
        </div>
    </form>
</body>

</html>