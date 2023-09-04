<?php
session_start();
if (isset($_SESSION["Username"])) {
    header("Location: dashboard.php");
    exit;
}
$noNavbar = "";
$pageTitle = "Login";

include("inti.php"); // important file include connection DB and langs

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user"];
    $password = $_POST["pass"];
    $hashedPass = sha1($password);
    // chick if this user exist
    $stmt = $con->prepare("select Username, UserID, Password from users where Username = ? and Password = ? and GroupID = 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
        $_SESSION["Username"] = $username;
        $_SESSION["ID"] = $row['UserID'];
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!-- login -->
<form class="login" action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
    <h4 class="text-center">Login Now</h4>
    <input class="form-control" type="text" placeholder="Username" autocomplete="off" name="user">
    <input class="form-control" type="password" placeholder="Password" autocomplete="off" name="pass">
    <input class="btn btn-primary btn-block" type="submit" value="login">
</form>

<?php include($tpl . "footer.php") ?>