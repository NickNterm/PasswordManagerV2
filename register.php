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
$pass2 = $_POST['PasswordRepeat'];
settype($pass2, "string");
$pass2 = str_replace("'", "\"", $pass2);
if ($user != null) {
    $sql = "SELECT * FROM login WHERE username = '$user';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = "";
        $nameError = $passwordError = "was-validated";
    } else {
        if ($pass == $pass2 && $pass != null) {
            $salt = generateRandomString(10);
            $token = generateRandomString(12);
            $hashedpass = hash('sha256', $salt . $pass, false);
            $sql = "INSERT INTO login (username, password, salt, token)  VALUES ('$user', '$hashedpass', '$salt', '$token')";
            if ($conn->query($sql) === TRUE) {
                header('Location: login.php');
            }
        }else{
            $passwordError = "was-validated";
        }
    }
}



function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

<link rel="stylesheet" href="register_style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<html>

<body>
    <form class="form-signin needs-validation" action="" method="post">
        <div>
            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center">Register</h1>
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
            <div class="form-floating <?php echo $passwordError ?>">
                <input type="password" class="form-control" id="floatingPasswordRepeat" name="PasswordRepeat" placeholder="Repeat" required>
                <label for="floatingPasswordRepeat">Repeat</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">Register</button>
    </form>
</body>

</html>