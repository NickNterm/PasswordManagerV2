<?php
session_start();
include 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_COOKIE["UserToken"];
if ($token != null) {
    $_SESSION["token"] = $token;
}
if ($_SESSION["token"] == null) {
    setcookie("UserToken", "");
    header("Location: login.php");
} else {
    $token = $_SESSION["token"];
}
$recordArrayList;
$sql = "SELECT * FROM data WHERE token = '$token';";
$result = $conn->query($sql1);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $element = new record;
        $element->platform = $row["platform"];
        $element->username = $row["username"];
        $element->hashedpassword = $row["password"];
        $element->hint = $row["hint"];
        array_push($recordArrayList, $element);
    }
}else{
    setcookie("UserToken", "");
    header("Location: login.php");  
}
echo $recordArrayList;

class record
{
    var string $platform;
    var string $username;
    var string $hashedpassword;
    var string $hint;
}
