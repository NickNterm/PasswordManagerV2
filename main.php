<?php
session_start();
include 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_COOKIE["UserToken"];
#echo "session: " . $_SESSION["token"] . " token: " . $token;
if ($_SESSION["token"] != null) {
    $token = $_SESSION["token"];
}

if ($token === null) {
    setcookie("UserToken", "", time() + (10 * 365 * 24 * 60 * 60));
    header("Location: login.php");
}
$recordArrayList = [];
$sql = "SELECT * FROM data WHERE token = '$token';";
$result = $conn->query($sql);
$sqlCheck = "SELECT * FROM login WHERE token = '$token';";
$checkResult = $conn->query($sqlCheck);
if ($checkResult->num_rows > 0) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $element = new record;
            $element->platform = $row["platform"];
            $element->username = $row["username"];
            $element->hashedpassword = $row["password"];
            $element->moreinfo = $row["more_info"];
            $element->hint = $row["hint"];
            array_push($recordArrayList, $element);
        }
    }
} else {
    setcookie("UserToken", "", time() + (10 * 365 * 24 * 60 * 60));
    header("Location: login.php");
}

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
<meta name="viewport" content="width=device-width, initial-scale=1" />
<script src="https://kit.fontawesome.com/93cf06ec80.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<html>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top"> Bootstrap
            </a>
            <form class="d-flex mb-0">
                <input class="form-control me-2" type="search" placeholder="Search" onkeyup="searchFunction()" id="searchInput" aria-label="Search">
            </form>
        </div>
    </nav>
    <div class="container-fluid =x-3 " id="ElementDiv">
        <div class="row">
            <?php
            for ($x = 0; $x < sizeof($recordArrayList); $x++) {
                echo "
                    <div class=\"col-md-4 col-sm-1 col-lg-2 p-2\">
                            <div class=\"card border-secondary\" style=\"height:200px;\">
                                <div class=\"card-body p-0\">
                                    <h5 class=\"card-header text-truncate\">" . $recordArrayList[$x]->platform . "</h5>
                                    <p class=\"card-text m-2\"style=\"overflow-y: auto; height:85px;\"><strong>More Info: </strong>" . $recordArrayList[$x]->moreinfo . "</p>
                                    <div class=\"d-grid gap-2\" >
                                        <button type=\"button\" class=\"btn btn-outline-secondary btn-lg mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#checkModal\" onclick=\"changeModal(this.id)\" id=\"$x\">Check</button>
                                    </div>
                                </div>
                            </div>
                    </div>";
            }
            ?>
            <div class="col-md-4 col-sm-1 col-lg-2 p-2">
                <div class="d-grid gap-2" style="height:200px;">
                    <button class="btn btn-outline-secondary"><i class="fas fa-plus fa-5x"></i></button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="checkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CheckModalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">
                    <span class="input-group-text" id="CheckModalUsername">Name</span>
                    <div class="input-group mb-3">
                        <input type="Password" class="form-control" placeholder="Password" aria-label="Password" id="CheckPasswordInput">
                    </div>
                    <div class="alert alert-secondary mb-3 pt-2 pb-2" id="modalMoreInfo" role="alert">
                        <strong>More Info: </strong> A simple secondary alert—check it out!
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button role="button" class="btn btn-outline-secondary text-center" onclick="checkPassword()" id="checkButton">Check</button>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddModalTitle">Add a new Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">
                <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Password" aria-label="Password" id="AddPasswordInput1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="Password" class="form-control" placeholder="Password" aria-label="Password" id="AddPasswordInput1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="Password" class="form-control" placeholder="Password" aria-label="Password" id="AddPasswordInput1">
                    </div>
                    <div class="alert alert-secondary mb-3 pt-2 pb-2" id="modalMoreInfo" role="alert">
                        <strong>More Info: </strong> A simple secondary alert—check it out!
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button role="button" class="btn btn-outline-secondary text-center" onclick="checkPassword()" id="checkButton">Check</button>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function searchFunction() {
        var txtValue;
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var div = document.getElementById("ElementDiv");
        var buttons = div.getElementsByTagName("button");
        for (i = 0; i < buttons.length; i++) {
            txtValue = buttons[i].textContent || buttons[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                buttons[i].style.display = "";
            } else {
                buttons[i].style.display = "none";
            }
        }
    }

    function checkPassword() {
        if (document.getElementById('CheckPasswordInput').value == list[selectedID].hashedpassword.toString()) {
            document.getElementById('checkButton').innerText = "Correct";
            document.getElementById('checkButton').classList.add('btn-success');
            document.getElementById('checkButton').classList.remove('btn-outline-secondary');
            document.getElementById('checkButton').classList.remove('btn-danger');
        } else {
            document.getElementById('checkButton').innerText = "Error";
            document.getElementById('checkButton').classList.remove('btn-success');
            document.getElementById('checkButton').classList.remove('btn-outline-secondary');
            document.getElementById('checkButton').classList.add('btn-danger');
        }
    }

    var list = <?php echo json_encode($recordArrayList) ?>;


    function changeModal(id) {
        selectedID = id;
        document.getElementById('CheckModalTitle').innerText = list[id].platform.toString();
        document.getElementById('CheckModalUsername').innerText = list[id].username.toString();
        document.getElementById('CheckPasswordInput').value = "";
        document.getElementById('checkButton').innerText = "Check";
        document.getElementById('checkButton').classList.remove('btn-success');
        document.getElementById('checkButton').classList.remove('btn-danger');
        document.getElementById('checkButton').classList.add('btn-outline-secondary');
        document.getElementById('modalMoreInfo').innerHTML = "<strong>Hint: </strong>" + list[id].hint.toString();
    }
    var selectedID = 0;
    var test = "nikolas";
</script>

</html>