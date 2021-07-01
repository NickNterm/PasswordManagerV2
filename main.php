<?php
session_start();
include 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_COOKIE["UserToken"];
echo "session: " . $_SESSION["token"] . " token: " . $token;
if ($_SESSION["token"] != null) {
    $token = $_SESSION["token"];
}

if ($token === null) {
    setcookie("UserToken", "");
    echo "error 1";
    #header("Location: login.php");
}
$recordArrayList = [];
$sql = "SELECT * FROM data WHERE token = '$token';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $element = new record;
        $element->platform = $row["platform"];
        $element->username = $row["username"];
        $element->hashedpassword = $row["password"];
        $element->moreinfo = $row["more_info"];
        $element->hint = $row["hint"];
        echo "<br> element: " . json_encode($element);
        array_push($recordArrayList, $element);
    }
} else {
    setcookie("UserToken", "");
    echo "error 2";
    #header("Location: login.php");  
}
echo "<br> list: " . json_encode($recordArrayList);

class record
{
    var string $platform;
    var string $username;
    var string $hashedpassword;
    var string $moreinfo;
    var string $hint;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="main_style.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://kit.fontawesome.com/93cf06ec80.js" crossorigin="anonymous"></script>
<html>

<body>
    <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="popover" data-bs-trigger="focus" title="Dismissible popover" data-bs-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="mainButton">Launch static backdrop modal</button>
    <?php
    for ($x = 0; $x < sizeof($recordArrayList); $x++) {
        echo "<button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#staticBackdrop\" onclick=\"changeModal(this.id)\" id=\"$x\">Button $x</button>";
    }
    ?>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">

                    <span class="input-group-text" id="modalUsername">Name</span>
                    <div class="input-group mb-3">
                        <input type="Password" class="form-control" placeholder="Password" aria-label="Password" id="passwordInput">
                        <a tabindex="0" class="btn btn-outline-secondary" id="hintButton" role="button" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="right" title="Dismissible popover" data-bs-container="div" data-bs-content="content" style="padding-top: 6px; padding-bottom: 6px;"><i class="fas fa-user-secret" style="font-size:25px; vertical-align: middle;"></i></a>
                    </div>
                    <div class="alert alert-secondary mb-3 pt-2 pb-2" id="modalMoreInfo" role="alert">
                    <strong>More Info: </strong> A simple secondary alert—check it out!
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    var list = <?php echo json_encode($recordArrayList) ?>;

    function changeModal(id) {
        document.getElementById('modalTitle').innerText = list[id].platform.toString();
        document.getElementById('modalUsername').innerText = list[id].username.toString();
        document.getElementById('modalMoreInfo').innerHTML = "<strong>More Info: </strong>" + list[id].moreinfo.toString();
        //document.getElementById('hintButton').content[0].innerHTML = list[id].hint.toString();
    }
    var test = "nikolas";
</script>

</html>