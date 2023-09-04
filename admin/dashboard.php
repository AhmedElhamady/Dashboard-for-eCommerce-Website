<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Dashboard";
    include("inti.php");
?>

    <!-- srart dashboard -->
    <div class="container home-stats text-center">
        <h1><?= lang("dashboard") ?></h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <?= lang("tot member") ?>
                    <span><a href="members.php"><?= countItems("UserID", "users") ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <?= lang("pending members") ?>
                    <span><a href="members.php?page=Pending"><?= checkItem("RegStatus", "users", 0) ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <?= lang("tot items") ?>
                    <span><a href="items.php"><?= countItems("Item_ID", "items") ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <?= lang("tot comments") ?>
                    <span><a href="comments.php"><?= countItems("C_ID", "comments") ?></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row mb-4">
            <div class="col-sm-6">
                <div class="panel-heading">
                    <div>
                        <i class="fa fa-users"></i>
                        <?= lang("latest users") ?>
                    </div>
                    <span class="show-info"><i class="fa fa-plus"></i></span>
                </div>
                <div class="panel-body">
                    <?php
                    $latest = getLatest("*", "users", "UserID", 5);
                    foreach ($latest as $user) : ?>
                        <div class="user">
                            <p><?= $user["Username"] ?></p>
                            <div>
                                <?php
                                if ($user["RegStatus"] == 0) {
                                    echo "<a href='members.php?do=Activate&userid=" . $user["UserID"] .
                                        "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                        . lang("actv") . "</a>";
                                }
                                ?>
                                <a href="members.php?do=Edit&userid=<?= $user["UserID"] ?>" class="btn btn-success">
                                    <i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i><?= lang("edt") ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-defualt">
                    <div class="panel-heading">
                        <div>
                            <i class="fa fa-tag"></i>
                            <?= lang("latest items") ?>
                        </div>
                        <span class="show-info"><i class="fa fa-plus"></i></span>
                    </div>
                    <div class="panel-body">
                        <?php
                        $latest = getLatest("*", "items", "Item_ID", 5);
                        foreach ($latest as $item) : ?>
                            <div class="user">
                                <p><?= $item["Name"] ?></p>
                                <div>
                                    <?php
                                    if ($item["Approve"] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" . $item["Item_ID"] .
                                            "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                            . lang("approve") . "</a>";
                                    }
                                    ?>
                                    <a href="items.php?do=Edit&itemid=<?= $item["Item_ID"] ?>" class="btn btn-success">
                                        <i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i><?= lang("edt") ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-sm-6">
                <div class="panel-heading">
                    <div>
                        <i class="fa fa-comment"></i>
                        <?= lang("latest comments") ?>
                    </div>
                    <span class="show-info"><i class="fa fa-plus"></i></span>
                </div>
                <div class="panel-body com">
                    <?php
                    $stmt = $con->prepare("SELECT comments.Comment, users.Username , users.UserID from comments inner join users on users.UserID = comments.User_ID order by C_ID desc limit 3 ");
                    $stmt->execute();
                    $coms = $stmt->fetchAll();
                    foreach ($coms as $com) : ?>
                        <div class="com-body">
                            <!-- <p><?= $com["Username"] ?></p> -->
                            <a href="members.php?do=Edit&userid=<?= $com["UserID"] ?>"><?= $com["Username"] ?></a>
                            <div><?= $com["Comment"] ?></div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>

<?php
    include($tpl . "footer.php");
} else {
    header("Location: index.php");
    exit;
}
