<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
$pageTitle = "Login to shop";

include("inti.php"); // important file include connection DB and langs

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $hashedPass = sha1($password);
        // chick if this user exist
        $stmt = $con->prepare("SELECT Username, UserID, Password from users where Username = ? and Password = ?");
        $stmt->execute(array($username, $hashedPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION["user"] = $username;
            $_SESSION["uid"] = $get["UserID"];
            header("Location: index.php");
            exit;
        }
    } else {
        $errors = array();
        if (isset($_POST["username"])) {
            $filterUser = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
            if (strlen($filterUser) < 4) {
                $errors[] = "Username must be larger than 4 chars";
            }
        }
        if (isset($_POST["password"]) && isset($_POST["password2"])) {
            if (empty($_POST["password"])) {
                $errors[] = "Password can't be empty";
            }
            $pass1 = sha1($_POST["password"]);
            $pass2 = sha1($_POST["password2"]);
            if ($pass1 !== $pass2) {
                $errors[] = "Sorry password is not match";
            }
        }
        if (!(isset($_POST['email']) && filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))) {
            $errors[] = "Invalid email";
        }
        if (empty($errors)) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = sha1($_POST["password"]);
            // $hashedPass = sha1($password);
            if (checkItem("Username", "users", $username) == 1) {
                $errors[] = "Sorry this Username exist";
            } elseif (checkItem("Email", "users", $email) == 1) {
                $errors[] = "Sorry this Email exist";
            } else {
                $stmt = $con->prepare("INSERT INTO users (Username, Email, Password, Date)
                VALUES (:user, :email, :pass, now() ) ");
                $stmt->execute(array(
                    "user" => $username,
                    "email" => $email,
                    "pass" => $password
                ));
                $successMsg = "Successfully Signup";
            }
        }
    }
}
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="login selected-b">Login</span> | <span class="signup">Signup</span>
    </h1>
    <form action="" method="post" class="login">
        <?= isset($successMsg) ? "<p class='btn btn-outline-primary mb-2'>" . $successMsg . "</p>" : "" ?>
        <input type="text" name="username" required="required" placeholder="Type Username" autocomplete="off" class="form-control">
        <input type="password" name="password" placeholder="Type Password" autocomplete="new-password" class="form-control">
        <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
    </form>
    <form action="" method="post" class="signup">
        <input type="text" name="username" placeholder="Type Username" autocomplete="off" class="form-control">
        <input type="email" name="email" placeholder="Type Valid Email" class="form-control">
        <input type="password" name="password" placeholder="Type Password" autocomplete="new-password" class="form-control">
        <input type="password" name="password2" placeholder="Type Password Again" autocomplete="new-password" class="form-control">
        <input class="btn btn-success btn-block" type="submit" name="signup" value="Signup">
        <div class="errors">
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='btn btn-outline-danger mb-2'>" . $error . "</p>";
                }
            }
            ?>
        </div>
    </form>
</div>

<?php include($tpl . "footer.php"); ?>