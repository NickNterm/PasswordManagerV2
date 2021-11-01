<?php
session_start();
include 'connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_COOKIE["UserToken"];
if ($_SESSION["token"] != null) {
    $token = $_SESSION["token"];
}

if ($token === null) {
    setcookie("UserToken", "", time() + (10 * 365 * 24 * 60 * 60));
    header("Location: log_in");
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
        settype($string, "string");
        $record->platform = str_replace("'", "\"", $string);
        $string = $_POST["AddUsernameInput"];
        settype($string, "string");
        $record->username = str_replace("'", "\"", $string);
        $string = $_POST["AddPasswordInput1"];
        settype($string, "string");
        $record->salt = generateRandomString(10);
        $record->hashedpassword = hash('sha256', str_replace("'", "\"", $string).$record->salt, false);
        $string = $_POST["moreInfoTextArea"];
        settype($string, "string");
        $record->moreinfo = str_replace("'", "\"", $string);
        $string = $_POST["hintTextArea"];
        settype($string, "string");
        $record->hint = str_replace("'", "\"", $string);
        $sql = "INSERT INTO data (token, platform, username, password, password_salt, hint, more_info)  VALUES ('$token', '$record->platform', '$record->username', '$record->hashedpassword', '$record->salt', '$record->hint', '$record->moreinfo')";
        if ($conn->query($sql) === TRUE) {
            header("Location: postHandler.php");
        } else {
            echo $conn->error;
        }
    }
}

if (isset($_POST['deleteButton'])) {
    if ($_POST['id'] != null) {
        $id = $_POST['id'];
        $sql = "DELETE FROM data WHERE id='$id';";
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
            $element->salt = $row["password_salt"];
            $element->moreinfo = $row["more_info"];
            $element->hint = $row["hint"];
            $element->id = $row["id"];
            array_push($recordArrayList, $element);
            usort($recordArrayList, "mySort");
        }
    }
} else {
    setcookie("UserToken", "", time() + (10 * 365 * 24 * 60 * 60));
    header("Location: log_in");
}

function mySort($a, $b)
{
    return ucfirst($a->platform) > ucfirst($b->platform);
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

class record
{
    var string $id;
    var string $platform;
    var string $username;
    var string $hashedpassword;
    var string $salt;
    var string $moreinfo;
    var string $hint;
}

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="main_style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/93cf06ec80.js" crossorigin="anonymous"></script>


<meta name="viewport" content="width=device-width, initial-scale=1" />
<html>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top"> Bootstrap
            </a>
            <form class="d-flex mb-0">
                <input class="form-control me-2" type="search" placeholder="Search" onkeyup="searchFunction()" id="searchInput" aria-label="Search">
                <div class="dropdown dropstart">
                    <button class="btn btn-light m-1 px-1" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog fa-lg"></i></button>
                    <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                        <li><a class="dropdown-item" href="log_in">Log Out</a></li>
                        <li><a class="dropdown-item" href="https://github.com/NickNterm/PasswordManagerV2" target="_blank">About</a></li>
                    </ul>
                </div>
            </form>
        </div>
    </nav>
    <div class="container-fluid =x-3 " id="ElementDiv">
        <div class="row">   
            <?php
            for ($x = 0; $x < sizeof($recordArrayList); $x++) {
                echo "
                    <div class=\"col-md-4 col-sm-1 col-lg-2 p-2 CardElement\">
                            <div class=\"card border-secondary\" style=\"height:205px;\">
                                <div class=\"card-body p-0\">
                                    <div class=\"card-header CardHeader px-2\">
                                        <div class=\"row\">
                                            <h5 class=\"col-10 m-0 text-truncate\">" . $recordArrayList[$x]->platform . "</h5>
                                            <div class=\"col-2\">
                                                <button type=\"button\" class=\"btn-close float-end py-1\" aria-label=\"Close\" data-bs-toggle=\"modal\" data-bs-target=\"#DeleteModal\"onclick=\"changeSelectedID(this.id)\" id=\"$x\"></button>
                                            </div>
                                        </div>
                                    </div>  
                                    <p class=\"card-text m-2\" style=\"overflow-y: auto; height:85px;\"><strong>More Info: </strong>" . $recordArrayList[$x]->moreinfo . "</p>
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
    <div class="modal fade" id="checkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="CheckModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-truncate" id="CheckModalTitle">Modal title</h5>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalTitle">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this element?
                </div>
                <div class="modal-footer px-0">
                    <div class="container-fluid">
                        <button type="button" class="btn btn-danger float-start" onclick="deleteElement()">Delete</button>
                        <button type="button" class="btn btn-secondary float-end" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Add Modal -->
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
    function searchFunction() {
        var txtValue;
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var elements = document.getElementsByClassName("CardElement");
        for (i = 0; i < elements.length; i++) {
            var element = elements[i].getElementsByClassName("CardHeader")[0];
            txtValue = element.textContent || element.innerText;
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

    function deleteElement() {
        console.log(list[selectedID].id);
        $.ajax({
            type: "POST", //type of method
            url: "main.php", //your page
            data: {
                deleteButton: true,
                id: list[selectedID].id
            }, // passing the values
            success: function(res) {
                window.location.href = "postHandler.php";
            }
        });
    }

    function openAddModal() {
        document.getElementById('AddPlatformInput').value = "";
        document.getElementById('AddUsernameInput').value = "";
        document.getElementById('AddPasswordInput1').value = "";
        document.getElementById('AddPasswordInput2').value = "";
        document.getElementById('moreInfoTextArea').value = "";
        document.getElementById('hintTextArea').value = "";
    }

    function checkPassword() {
        if (sha256(document.getElementById('CheckPasswordInput').value+list[selectedID].salt.toString()) == list[selectedID].hashedpassword.toString()) {
            document.getElementById('checkButton').innerText = "Correct";
            document.getElementById('checkButton').classList.add('btn-success');
            document.getElementById('CheckPasswordInput').disabled = true
            document.getElementById('modalMoreInfo').innerHTML = "<strong>Password Found!</strong> > "+document.getElementById('CheckPasswordInput').value;
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

    function changeSelectedID(id) {
        selectedID = id;
    }

    function changeModal(id) {
        selectedID = id;
        document.getElementById('CheckModalTitle').innerText = list[id].platform.toString();
        document.getElementById('CheckModalUsername').innerText = list[id].username.toString();
        document.getElementById('CheckPasswordInput').value = "";
        document.getElementById('CheckPasswordInput').disabled = false;
        document.getElementById('checkButton').innerText = "Check";
        document.getElementById('checkButton').classList.remove('btn-success');
        document.getElementById('checkButton').classList.remove('btn-danger');
        document.getElementById('checkButton').classList.add('btn-outline-secondary');
        document.getElementById('modalMoreInfo').innerHTML = "<strong>Hint: </strong>" + list[id].hint.toString();
    }
    var selectedID = 0;
</script>

</html>