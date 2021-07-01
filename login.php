<?php
session_start();
if($_COOKIE["UserToken"] != null){
    $_SESSION["token"] = $_COOKIE["UserToken"];
    header("Location: main.php");
}
include 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$nameError = $passwordError = "";
$user = $_POST['Username'];
settype($user, "string");
$user = str_replace("'", "\"", $user);
$pass = $_POST['Password'];
settype($pass, "string");
$pass = str_replace("'", "\"", $pass);
if ($user != null) {
    $sql = "SELECT * FROM login WHERE username = '$user';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $salt = $row["salt"];
            if (hash('sha256', $salt . $pass, false) === $row["password"]) {
                $_SESSION["token"] = $row["token"];
                if ($_POST['rememberMeCheck'] == 1) {
                    setcookie("UserToken", $row["token"]);
                }else{
                    setcookie("UserToken", "");
                }
                header("Location: main.php");
            } else {
                $passwordError = "was-validated";
            }
        }
    } else {
        $user = "";
        $nameError = $passwordError = "was-validated";
    }
}

?>
<link rel="stylesheet" href="login_style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<html>

<body>
    <form class="form-signin needs-validation" action="" method="post">
        <div>
            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center">Welcome</h1>
        </div>
        <hr>
        <div class="d-grid mb-2">
            <div class="form-floating <?php echo $nameError ?>">
                <input type="text" class="form-control" id="floatingUsername" name="Username" placeholder="Name" value="<?php echo $user ?>" required>
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating <?php echo $passwordError ?>">
                <input type="password" class="form-control" id="floatingPassword" name="Password" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" value="1" name="rememberMeCheck" id="rememberMeCheck">
                <label class="form-check-label" for="rememberMeCheck">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">Login</button>
        </div>
    </form>

    <div class="form-signin mt-3 d-flex justify-content-center">
        <p class="mb-0">Don't have an account? <a href="register.php" class="link-primary">SignUp</a></p>
    </div>
</body>

</html>