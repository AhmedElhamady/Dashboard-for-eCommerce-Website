<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Members";
    include "inti.php";

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    if ($do == "Manage") {
        $query = "";
        if (isset($_GET["page"]) && $_GET["page"] == "Pending") {
            $query = "AND RegStatus = 0 ";
        }
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ");
        $stmt->execute();
        $rows = $stmt->fetchAll(); ?>
        <h1 class="text-center"><?= lang("mng members") ?></h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table text-center table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Operation</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["UserID"] . "</td>";
                        echo "<td>" . $row["Username"] . "</td>";
                        echo "<td>" . $row["Email"] . "</td>";
                        echo "<td>" . $row["FullName"] . "</td>";
                        echo "<td>" . $row["Date"] . "</td>";
                        echo "<td class='cust'>";
                        echo "<a href='members.php?do=Edit&userid=" . $row["UserID"] .
                            "' class='btn btn-success'><i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i>"
                            . lang("edt") . "</a>";
                        echo "<a href='members.php?do=Delete&userid=" . $row["UserID"] .
                            "' class='btn btn-danger confirm'><i class='icon-mem fa-solid fa-trash'></i>"
                            . lang("del") . "</a>";
                        if ($row["RegStatus"] == 0) {
                            echo "<a href='members.php?do=Activate&userid=" . $row["UserID"] .
                                "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                . lang("actv") . "</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>
                <?= lang("new member") ?></a>
        </div>

        <?php
    } else if ($do == "Edit") {
        $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
        $stmt = $con->prepare("select * from users where UserID = ? limit 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        echo '<h1 class="text-center">' . lang("edt member") . '</h1>';
        echo '<div class="container">';
        if ($count > 0) { ?>
            <form action="?do=Update" method="post">
                <input type="hidden" value="<?= $row["UserID"] ?>" name="userid">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("username") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="required" value="<?= $row["Username"] ?>" name="username" autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("password") ?></label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" value="<?= $row["Password"] ?>" name="oldpassword" autocomplete="new-password">
                        <input type="password" class="form-control" name="newpassword" autocomplete="new-password" placeholder="Leave it if you don't want to change">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("email") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="email" required="required" class="form-control" value="<?= $row["Email"] ?>" name="email" autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("full name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="required" value="<?= $row["FullName"] ?>" name="full" autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="offset-md-2 col-sm-10">
                        <input type="submit" class="btn btn-primary col-sm-2" value="<?= lang("save") ?>">
                    </div>
                </div>
            </form>
        <?php } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back", 3);
        }
        echo "</div>";
    } elseif ($do == "Update") {
        echo "<h1 class='text-center'>" . lang("edt member") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["userid"];
            $user = $_POST["username"];
            $email = $_POST["email"];
            $name = $_POST["full"];
            $pass = empty($_POST["newpassword"]) ? $_POST["oldpassword"] : sha1($_POST["newpassword"]);

            // check errors
            $formErrors = array();
            if (empty($user))
                $formErrors[] = "<div class='alert alert-danger'>Username can't be empty</div>";
            if (strlen($user) > 20 || strlen($user) < 3)
                $formErrors[] = "<div class='alert alert-danger'>Username must more than 2 chars and less than 21 chars</div>";
            if (empty($email))
                $formErrors[] = "<div class='alert alert-danger'>Email can't be empty</div>";
            if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))
                $formErrors[] = "<div class='alert alert-danger'>Enter correct email</div>";
            if (empty($name))
                $formErrors[] = "<div class='alert alert-danger'>Full name can't be empty</div>";
            if (strlen($name) < 6)
                $formErrors[] = "<div class='alert alert-danger'>Full name must more than 5 chars</div>";
            if ($formErrors) {
                foreach ($formErrors as $error) {
                    echo $error;
                }
            } else {
                $stmt2 = $con->prepare("SELECT * from users where Username = ? and UserID != ? ");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                if ($count != 1) {
                    $stmt = $con->prepare("UPDATE users SET Username=?, Email=?, FullName=?, Password=? WHERE UserID=? ");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated" . "</div>";
                    redirectHome($theMsg, "back", 3);
                } else {
                    $theMsg = "<div class='alert alert-danger'>This username is exist</div>";
                    redirectHome($theMsg, "back", 3);
                }
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back", 3);
        }
        echo "</div>";
    } elseif ($do == "Add") { ?>
        <h1 class="text-center"><?= lang("add member") ?></h1>
        <div class="container">
            <form action="?do=Insert" method="post">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("username") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="required" name="username" autocomplete="off" placeholder="Enter username to login in shop">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("password") ?></label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control password" name="password" required="required" autocomplete="new-password" placeholder="Password must be complex">
                        <i class="show-pass fa-solid fa-eye"></i>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("email") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="email" required="required" class="form-control" name="email" autocomplete="off" placeholder="Enter valid email">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("full name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="required" name="full" autocomplete="off" placeholder="Enter full name, appear in profile">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="offset-md-2 col-sm-10">
                        <input type="submit" class="btn btn-primary col-sm-2" value="<?= lang("add") ?>">
                    </div>
                </div>
            </form>
        </div>
<?php
    } elseif ($do == "Insert") {
        echo "<h1 class='text-center'>" . lang("add member") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $_POST["username"];
            $pass = $_POST["password"];
            $hashPass = sha1($pass);
            $email = $_POST["email"];
            $name = $_POST["full"];

            // check errors
            $formErrors = array();
            if (empty($user))
                $formErrors[] = "Username can't be empty";
            if (strlen($user) > 20 || strlen($user) < 3)
                $formErrors[] = "Username must more than 2 chars and less than 21 chars";
            if (empty($email))
                $formErrors[] = "Email can't be empty";
            if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))
                $formErrors[] = "Enter correct email";
            if (empty($name))
                $formErrors[] = "Full name can't be empty";
            if (strlen($name) < 6)
                $formErrors[] = "Full name must more than 5 chars";
            if (strlen($pass) < 5)
                $formErrors[] = "Full name must equal or more than 5 chars";
            if ($formErrors) {
                foreach ($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
            } else {
                if (checkItem("Username", "users", $user) == 1) {
                    $theMsg = "<div class='alert alert-danger'>Sorry this <strong>Username</strong> is already exist
                    </div>";
                    redirectHome($theMsg, "back");
                } elseif (checkItem("Email", "users", $email) == 1) {
                    $theMsg = "<div class='alert alert-danger'>Sorry this <strong>Email</strong> is already exist
                    </div>";
                    redirectHome($theMsg, "back");
                } else {
                    $stmt = $con->prepare("INSERT INTO users (Username, Email, Password, FullName, RegStatus, Date)
                    VALUES (:user, :email, :pass, :name, 1, now() ) ");
                    $stmt->execute(array(
                        "user" => $user,
                        "email" => $email,
                        "pass" => $hashPass,
                        "name" => $name,
                    ));
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted" . "</div>";
                    redirectHome($theMsg, "back");
                }
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "Delete") {
        echo "<h1 class='text-center'>" . lang("del member") . "</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
        // GroupID != 1 from me not elzero
        $stmt = $con->prepare("SELECT * from users where UserID = ? AND GroupID != 1  limit 1");
        $stmt->execute(array($userid));
        $count = $stmt->rowCount();
        if ($count > 0) {
            $stmt = $con->prepare("DELETE from users where UserID = :userid ");
            $stmt->bindParam(":userid", $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>1 Record Deleted</div>";
            redirectHome($theMsg, "back");
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "Activate") {
        echo "<h1 class='text-center'>" . lang("actv member") . "</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
        // GroupID != 1 from me not elzero
        $check = checkItem("UserID", "users", $userid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE  users set RegStatus = 1 where UserID = ?");
            $stmt->execute(array($userid));
            $theMsg = "<div class='alert alert-success'>1 Record Updated</div>";
            redirectHome($theMsg, "back");
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    }
    include $tpl . "footer.php";
} else {
    header("Location: index.php");
}
