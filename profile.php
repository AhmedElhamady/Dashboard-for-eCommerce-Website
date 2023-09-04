<?php
session_start();
$pageTitle = "My profile";
include("inti.php"); // important file include connection DB and langs
if (isset($_SESSION["user"])) {
    $getUser = $con->prepare("SELECT * from users where Username = ? ");
    $getUser->execute(array($_SESSION["user"]));
    $info = $getUser->fetch();
?>
<div class="information block">
    <div class="container">
        <div class="card">
            <div class="card-header text-bg-primary">
                My Information
            </div>
            <div class="card-body">
                <p><i class="fa fa-unlock"></i><span>Login Name</span>: <?= $info["Username"] ?> </p>
                <p><i class="fa-solid fa-envelope"></i><span>Email</span>: <?= $info["Email"] ?> </p>
                <p><i class="fa-solid fa-user"></i><span>Full Name</span>: <?= $info["FullName"] ?> </p>
                <p><i class="fa-solid fa-calendar-days"></i><span>Register Date</span>: <?= $info["Date"] ?> </p>
                <p><i class="fa-solid fa-bookmark"></i><span>Fav Category</span>: <?= $info["Username"] ?> </p>
            </div>
        </div>
    </div>
</div>
<div class="my-ads block">
    <div class="container">
        <div class="card">
            <div class="card-header text-bg-primary">
                My Ads
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                        if (!empty(getItems("Member_ID", $info["UserID"]))) {
                            foreach (getItems("Member_ID", $info["UserID"]) as $item) : ?>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <div class="img-thumbnail item-box">
                            <span class="price">$<?= $item["Price"] ?></span>
                            <?php
                                        if ($item["Approve"] == 0) {
                                            echo "<span class='approv'>Waiting approvment</span>";
                                        }
                                        ?>
                            <img class="img-fluid" src="layout/images/img.png" alt="">
                            <div class="caption">
                                <h3><a href="item.php?itemid=<?= $item["Item_ID"] ?>"><?= $item["Name"] ?></a></h3>
                                <p><?= $item["Description"] ?></p>
                                <div class="date"><?= $item["Add_Date"] ?></div>
                            </div>
                        </div>
                    </div>
                    <?php
                            endforeach;
                        } else {
                            echo "<span>There is no ads here</span>";
                        } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="card">
            <div class="card-header text-bg-primary">
                Latest Comments
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
<?php
} else {
    header("Location: login.php");
    exit();
}
include($tpl . "footer.php");
?>