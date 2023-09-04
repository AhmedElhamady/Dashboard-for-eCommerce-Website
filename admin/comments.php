<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Comments";
    include "inti.php";

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    if ($do == "Manage") {
        $stmt = $con->prepare("SELECT comments.*, items.Name as Item_Name, users.Username FROM comments
        inner join items on items.Item_ID = comments.Item_ID
        inner join users on users.UserID = comments.User_ID ");
        $stmt->execute();
        $rows = $stmt->fetchAll(); ?>
        <h1 class="text-center"><?= lang("mng comments") ?></h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table text-center table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["C_ID"] . "</td>";
                        echo "<td class='max' title ='" . $row['Comment'] . "' >" . $row["Comment"] . "</td>";
                        echo "<td>" . $row["Item_Name"] . "</td>";
                        echo "<td>" . $row["Username"] . "</td>";
                        echo "<td>" . $row["Comment_Date"] . "</td>";
                        echo "<td class='cust'>";
                        echo "<a href='comments.php?do=Edit&comid=" . $row["C_ID"] .
                            "' class='btn btn-success'><i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i>"
                            . lang("edt") . "</a>";
                        echo "<a href='comments.php?do=Delete&comid=" . $row["C_ID"] .
                            "' class='btn btn-danger confirm'><i class='icon-mem fa-solid fa-trash'></i>"
                            . lang("del") . "</a>";
                        if ($row["Status"] == 0) {
                            echo "<a href='comments.php?do=Approve&comid=" . $row["C_ID"] .
                                "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                . lang("approve") . "</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php
    } else if ($do == "Edit") {
        $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;
        $stmt = $con->prepare("SELECT Comment, C_ID from comments where C_ID = ? limit 1");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        echo '<h1 class="text-center">' . lang("edt comment") . '</h1>';
        echo '<div class="container">';
        if ($count > 0) { ?>
            <form action="?do=Update" method="post">
                <input type="hidden" value="<?= $row["C_ID"] ?>" name="comid">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("comment") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="required" value="<?= $row["Comment"] ?>" name="comment" autocomplete="off">
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
        echo "<h1 class='text-center'>" . lang("edt comment") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $comid = $_POST["comid"];
            $comment = $_POST["comment"];
            $stmt = $con->prepare("UPDATE comments SET Comment=? WHERE C_ID=? ");
            $stmt->execute(array($comment, $comid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated" . "</div>";
            redirectHome($theMsg, "back", 3);
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back", 3);
        }
        echo "</div>";
    } elseif ($do == "Delete") {
        echo "<h1 class='text-center'>" . lang("del comment") . "</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;
        if (checkItem("C_ID", "comments", $comid)) {
            $stmt = $con->prepare("DELETE from comments where C_ID = :comid ");
            $stmt->bindParam(":comid", $comid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>1 Record Deleted</div>";
            redirectHome($theMsg, "back");
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "Approve") {
        echo "<h1 class='text-center'>" . lang("approve comment") . "</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0;
        // GroupID != 1 from me not elzero
        $check = checkItem("C_ID", "comments", $comid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE  comments set Status = 1 where C_ID = ?");
            $stmt->execute(array($comid));
            $theMsg = "<div class='alert alert-success'>1 Record Updated</div>";
            redirectHome($theMsg, "back");
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "View") {

        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        $stmt = $con->prepare("SELECT Name from items where Item_ID = ? limit 1");
        $stmt->execute(array($itemid));
        $itemName = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $stmt = $con->prepare("SELECT comments.*, items.Name as Item_Name, users.Username FROM comments 
            inner join users on users.UserID = comments.User_ID 
            inner join items on items.Item_ID = comments.Item_ID where comments.Item_ID = ? ");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
            $countCom = $stmt->rowCount();
            if ($countCom > 0) { ?>
                <h1 class="text-center"><?= lang("all comments for") . " " . $itemName["Name"]  ?></h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table table text-center table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td class='max' title ='" . $row['Comment'] . "' >" . $row["Comment"] . "</td>";
                                echo "<td>" . $row["Username"] . "</td>";
                                echo "<td>" . $row["Comment_Date"] . "</td>";
                                echo "<td class='cust'>";
                                echo "<a href='comments.php?do=Edit&comid=" . $row["C_ID"] .
                                    "' class='btn btn-success'><i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i>"
                                    . lang("edt") . "</a>";
                                echo "<a href='comments.php?do=Delete&comid=" . $row["C_ID"] .
                                    "' class='btn btn-danger confirm'><i class='icon-mem fa-solid fa-trash'></i>"
                                    . lang("del") . "</a>";
                                if ($row["Status"] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid=" . $row["C_ID"] .
                                        "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                        . lang("approve") . "</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
    <?php
            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger mt-5'>There is no comments for this item</div>";
                echo $theMsg;
                echo "</div>";
            };
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
