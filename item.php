<?php
session_start();
$pageTitle = "My profile";
include("inti.php"); // important file include connection DB and langs
$itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
$stmt = $con->prepare("SELECT items.*, categories.Name as Category_name, users.Username
                    from items inner join categories on items.Cat_ID = categories.ID
                    inner join users on items.Member_ID = users.UserID
                    where Item_ID = ? and Approve = 1 ");
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
echo "<div class='container item mt-3'>";
if ($count > 0) {
    $row = $stmt->fetch();
?>
    <h1 class='text-center'><?= $row["Name"] ?></h1>
    <div class="row">
        <div class="col-md-3">
            <img src="layout/images/img.png" alt="">
        </div>
        <div class="col-md-9">
            <h2><?= $row["Name"] ?></h2>
            <p><?= $row["Description"] ?></p>
            <ul class="listed-unstyle">
                <li><i class="fa fa-calendar fa-fw"></i><span>Added Date</span>: <?= $row["Add_Date"] ?></li>
                <li><i class="fa fa-money-check-dollar fa-fw"></i><span>Price</span>: $<?= $row["Price"] ?></li>
                <li><i class="fa fa-building fa-fw"></i><span>Made In</span>: <?= $row["Country_Made"] ?></li>
                <li><i class="fa-solid fa-layer-group fa-fw"></i><span>Category</span>: <?= $row["Category_name"] ?></li>
                <li><i class="fa fa-user fa-fw"></i><span>Added By</span>: <?= $row["Username"] ?></li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php
    if (isset($_SESSION["user"])) { ?>
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <form action="" method="post">
                    <textarea class="form-control mb-2" name="comment" placeholder="Put your comment here"></textarea>
                    <input class="btn btn-primary col-sm-3" type="submit" value="Add">
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $comment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
                    $userid = $_SESSION["uid"];
                    $itemid = $row["Item_ID"];
                    $stmt = $con->prepare("INSERT into comments(Comment,Status, Comment_Date, Item_ID, User_ID)
                    values(:comment,0 , now(), :itemid, :userid)");
                    $stmt->execute(array(
                        "comment" => $comment,
                        "itemid" => $itemid,
                        "userid" => $userid
                    ));
                    if ($stmt) {
                        echo "<p class='alert alert-success p-2 mt-2'>Comment is added successfully</p>";
                    }
                }
                ?>
            </div>
        </div>
    <?php } else {
        echo "<a href='login.php'>Login</a> or <a href='login.php'>Register</a> to add comment";
    }
    ?>
    <hr class="custom-hr">
    <?php
    $stmt = $con->prepare("SELECT comments.*, users.Username FROM comments
            inner join users on users.UserID = comments.User_ID
            where Item_ID = ? and Status = 1 order by C_ID desc ");
    $stmt->execute(array($row["Item_ID"]));
    $comments = $stmt->fetchAll();
    foreach ($comments as $comment) : ?>
        <div class="row">
            <div class="col-md-3 text-center">
                <img class="com-img rounded-circle" src="layout/images/img.png" alt="">
                <?= $comment["Username"] ?>
            </div>
            <div class="col-md-9"><?= $comment["Comment"] ?></div>
        </div>
        <hr>
    <?php endforeach ?>
<?php
} else {
    echo "<h3 class='text-center alert alert-danger mt-3 p-2'>There is no such id </h2>";
    echo "</div>";
}
include($tpl . "footer.php");
?>