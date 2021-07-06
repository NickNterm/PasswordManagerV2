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

if (isset($_POST['logout'])) {
    setcookie("UserToken", "", time() + (10 * 365 * 24 * 60 * 60));
    header("Location: login.php");
}

if (isset($_POST['addNewButton'])) {
    if (
        $_POST["AddPasswordInput2"] === $_POST["AddPasswordInput1"]
        && $_POST["AddPasswordInput2"] != null
        && $_POST["AddPlatformInput"] != null
        && $_POST["AddUsernameInput"] != null
        && $_POST["moreInfoTextArea"] != null
        && $_POST["hintTextArea"] != null
    ) {
        $record = new record;
        $string = $_POST["AddPlatformInput"];
        settype($string , "string");
        $record->platform = str_replace("'", "\"", $string);
        $string = $_POST["AddUsernameInput"];
        settype($string , "string");
        $record->username = str_replace("'", "\"", $string);
        $string = $_POST["AddPasswordInput1"];
        settype($string , "string");
        $record->hashedpassword = hash('sha256', str_replace("'", "\"", $string), false);
        $string = $_POST["moreInfoTextArea"];
        settype($string , "string");
        $record->moreinfo = str_replace("'", "\"", $string);
        $string = $_POST["hintTextArea"];
        settype($string , "string");
        $record->hint = str_replace("'", "\"", $string);

        $sql = "INSERT INTO data (token, platform, username, password, hint, more_info)  VALUES ('$token', '$record->platform', '$record->username', '$record->hashedpassword', '$record->hint', '$record->moreinfo')";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo $conn->error;
        }
    }
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
            <form action="" method="post">
                <button type="submit" class="btn btn-outline-secondary" name="logout">LogOut</button>
            </form>
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
                    <div class=\"col-md-4 col-sm-1 col-lg-2 p-2\" id=\"element$x\" name=\"CardElement\">
                            <div class=\"card border-secondary\" style=\"height:200px;\">
                                <div class=\"card-body p-0\">
                                    <h5 class=\"card-header text-truncate\" name=\"CardHeader\">" . $recordArrayList[$x]->platform . "</h5>
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
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#AddModal" onclick="openAddModal()"><i class="fas fa-plus fa-5x"></i></button>
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
                <form class="modal-body pb-0" action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Platform" id="AddPlatformInput" name="AddPlatformInput" required>
                        <label for="AddPlatformInput">Platform</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Username" id="AddUsernameInput" name="AddUsernameInput" required>
                        <label for="AddUsernameInput">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="Password" class="form-control" placeholder="Password" id="AddPasswordInput1" name="AddPasswordInput1" required>
                        <label for="AddPasswordInput1">Password</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="Password" class="form-control" placeholder="Repeat" id="AddPasswordInput2" name="AddPasswordInput2" required>
                        <label for="AddPasswordInput2">Repeat</label>
                    </div>
                    <div class="mb-3 pt-2 pb-2" id="modalMoreInfo" role="alert">
                        <div class="form-floating">
                            <textarea class="form-control mb-3" id="moreInfoTextArea" placeholder="More Info" style="overflow:hidden" rows="1" name="moreInfoTextArea" required> </textarea>
                            <label for="moreInfoTextArea">More Info</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="hintTextArea" placeholder="Hint" style="overflow:hidden" rows="1" name="hintTextArea" required> </textarea>
                            <label for="hintTextArea">Hint</label>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-outline-secondary text-center" id="addNewButton" name="addNewButton">Submit</button>
                    </div>
                </form>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="sha256.js" type="text/javascript"></script>
<script>
    console.log();
    function searchFunction() {
        var txtValue;
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var div = document.getElementById("ElementDiv");
        var elements = div.getElementsByName("CardElement");
        for (i = 0; i < elements.length; i++) {
            txtValue = elements[i].getElementsByName("CardHeader").textContent || elements[i].getElementsByName("CardHeader").innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                elements[i].style.display = "";
            } else {
                elements[i].style.display = "none";
            }
        }
    }

    $("textarea").each(function() {
        if (this.scrollHeight > 100) {
            this.setAttribute("style", "height:" + (this.scrollHeight + 20) + "px;overflow-y:hidden;");
        }
    }).on("input", function() {
        this.style.height = "auto";
        this.style.height = (this.scrollHeight) + "px";
    });

    function openAddModal() {
        document.getElementById('AddPlatformInput').value = "";
        document.getElementById('AddUsernameInput').value = "";
        document.getElementById('AddPasswordInput1').value = "";
        document.getElementById('AddPasswordInput2').value = "";
        document.getElementById('moreInfoTextArea').value = "";
        document.getElementById('hintTextArea').value = "";

    }

    function checkPassword() {
        if (sha256(document.getElementById('CheckPasswordInput').value) == list[selectedID].hashedpassword.toString()) {
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