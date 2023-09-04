<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Items";
    include "inti.php";

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    if ($do == "Manage") {
        $stmt = $con->prepare("SELECT items.*, categories.Name AS Cat_Name, users.Username FROM `items`
        INNER JOIN categories ON categories.ID = items.Cat_ID
        INNER JOIN users ON users.UserID = items.Member_ID");
        $stmt->execute();
        $rows = $stmt->fetchAll(); ?>
        <h1 class="text-center"><?= lang("mng items") ?></h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table text-center table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category Name</td>
                        <td>By Member</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["Item_ID"] . "</td>";
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td class='max' title ='" . $row['Description'] . "' >" . $row["Description"] . "</td>";
                        echo "<td>" . "$" . $row["Price"] . "</td>";
                        echo "<td>" . $row["Add_Date"] . "</td>";
                        echo "<td>" . $row["Cat_Name"] . "</td>";
                        echo "<td>" . $row["Username"] . "</td>";
                        echo "<td class='cust'>";
                        echo "<a href='items.php?do=Edit&itemid=" . $row["Item_ID"] .
                            "' class='btn btn-success'><i class='icon-mem fa-sharp fa-solid fa-pen-to-square'></i>"
                            . lang("edt") . "</a>";
                        echo "<a href='items.php?do=Delete&itemid=" . $row["Item_ID"] .
                            "' class='btn btn-danger confirm'><i class='icon-mem fa-solid fa-trash'></i>"
                            . lang("del") . "</a>";
                        echo "<a href='comments.php?do=View&itemid=" . $row["Item_ID"] .
                            "' class='btn btn-warning'><i class='comment fa-solid fa-comment'></i>"
                            . "</a>";
                        if ($row["Approve"] == 0) {
                            echo "<a href='items.php?do=Approve&itemid=" . $row["Item_ID"] .
                                "' class='btn btn-primary activate'><i class='icon-mem fa-solid fa-circle-check'></i>"
                                . lang("approve") . "</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>
                <?= lang("new item") ?></a>
        </div>

    <?php
    } elseif ($do == "Add") { ?>
        <h1 class="text-center"><?= lang("add item") ?></h1>
        <div class="container">
            <form action="?do=Insert" method="post">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="requered" name="name" placeholder="Name of the category ">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("description") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" name="description" placeholder="Discribe of the item">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("price") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" name="price" placeholder="Item price">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("country") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" name="country" placeholder="Country made">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("status") ?></label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like new</option>
                            <option value="3">Used</option>
                            <option value="4">Very old</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("category") ?></label>
                    <div class="col-sm-10">
                        <select name="category" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $stmt = $con->prepare("SELECT * from categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            foreach ($cats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat["Name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("to member") ?></label>
                    <div class="col-sm-10">
                        <select name="member" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $stmt = $con->prepare("SELECT * from users where GroupID != 1");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user["Username"] . "</option>";
                            }
                            ?>
                        </select>
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
        echo "<h1 class='text-center'>" . lang("add item") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $description = $_POST["description"];
            $price = $_POST["price"];
            $country = $_POST["country"];
            $status = $_POST["status"];
            $cat = $_POST["category"];
            $member = $_POST["member"];
            // check errors
            $formErrors = array();
            if (empty($name))
                $formErrors[] = "Name can't be <strong>empty</strong>";
            if (empty($description))
                $formErrors[] = "Description can't be <strong>empty</strong>";
            if (empty($price))
                $formErrors[] = "Price can't be <strong>empty</strong>";
            if (!filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT))
                $formErrors[] = "Price must be <strong>valid</strong>";
            if (empty($country))
                $formErrors[] = "Country can't be <strong>empty</strong>";
            if (empty($status) && $status == 0)
                $formErrors[] = "Status can't be <strong>empty</strong>";
            if (empty($cat) && $cat == 0)
                $formErrors[] = "Category can't be <strong>empty</strong>";
            if (empty($member) && $member == 0)
                $formErrors[] = "Member can't be <strong>empty</strong>";
            if ($formErrors) {
                foreach ($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
            } else {
                $stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                    VALUES (:name, :desc, :price, :country, :stat, now(), :cat, :member ) ");
                $stmt->execute(array(
                    "name" => $name,
                    "desc" => $description,
                    "price" => $price,
                    "country" => $country,
                    "stat" => $status,
                    "cat" => $cat,
                    "member" => $member
                ));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted" . "</div>";
                redirectHome($theMsg, "back");
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } else if ($do == "Edit") {
        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        $stmt = $con->prepare("select * from items where Item_ID = ? limit 1");
        $stmt->execute(array($itemid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        echo '<h1 class="text-center">' . lang("edt item") . '</h1>';
        echo '<div class="container">';
        if ($count > 0) { ?>
            <form action="?do=Update" method="post">
                <input type="hidden" value="<?= $row["Item_ID"] ?>" name="itemid">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="requered" value="<?= $row["Name"] ?>" name="name" placeholder="Name of the category ">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("description") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" value="<?= $row["Description"] ?>" name="description" placeholder="Discribe of the item">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("price") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" value="<?= $row["Price"] ?>" name="price" placeholder="Item price">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("country") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" required="requered" name="country" value="<?= $row["Country_Made"] ?>" placeholder="Country made">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("status") ?></label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control">
                            <option value="0" <?= $row["Status"] == 0 ? "selected" : "" ?>>...</option>
                            <option value="1" <?= $row["Status"] == 1 ? "selected" : "" ?>>New</option>
                            <option value="2" <?= $row["Status"] == 2 ? "selected" : "" ?>>Like new</option>
                            <option value="3" <?= $row["Status"] == 3 ? "selected" : "" ?>>Used</option>
                            <option value="4" <?= $row["Status"] == 4 ? "selected" : "" ?>>Very old</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("category") ?></label>
                    <div class="col-sm-10">
                        <select name="category" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $stmt = $con->prepare("SELECT * from categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            foreach ($cats as $cat) {
                                $selected = $row["Cat_ID"] == $cat["ID"] ? "selected" : "";
                                echo "<option $selected value='" . $cat['ID'] . "'>" . $cat["Name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("to member") ?></label>
                    <div class="col-sm-10">
                        <select name="member" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $stmt = $con->prepare("SELECT * from users where GroupID != 1");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user) {
                                $selected = $row["Member_ID"] == $user["UserID"] ? "selected" : "";
                                echo "<option $selected value='" . $user['UserID'] . "'>" . $user["Username"] . "</option>";
                            }
                            ?>
                        </select>
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
        echo "<h1 class='text-center'>" . lang("edt item") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $itemid = $_POST["itemid"];
            $name = $_POST["name"];
            $description = $_POST["description"];
            $price = $_POST["price"];
            $country = $_POST["country"];
            $status = $_POST["status"];
            $cat = $_POST["category"];
            $member = $_POST["member"];
            if (checkItem("Item_ID", "items", $itemid)) {
                // check errors
                $formErrors = array();
                if (empty($name))
                    $formErrors[] = "Name can't be <strong>empty</strong>";
                if (empty($description))
                    $formErrors[] = "Description can't be <strong>empty</strong>";
                if (empty($price))
                    $formErrors[] = "Price can't be <strong>empty</strong>";
                if (!filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT))
                    $formErrors[] = "Price must be <strong>valid</strong>";
                if (empty($country))
                    $formErrors[] = "Country can't be <strong>empty</strong>";
                if (empty($status) && $status == 0)
                    $formErrors[] = "Status can't be <strong>empty</strong>";
                if (empty($cat) && $cat == 0)
                    $formErrors[] = "Category can't be <strong>empty</strong>";
                if (empty($member) && $member == 0)
                    $formErrors[] = "Member can't be <strong>empty</strong>";
                if ($formErrors) {
                    foreach ($formErrors as $error) {
                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                    }
                } else {
                    $stmt = $con->prepare("UPDATE items SET Name=?, Description=?, Price=?, Country_Made=?, Status=?, Cat_ID=?, Member_ID=?
                    WHERE Item_ID=? ");
                    $stmt->execute(array($name, $description, $price, $country, $status, $cat, $member, $itemid));
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated" . "</div>";
                    redirectHome($theMsg, "back");
                }
            } else {
                echo $itemid;
                $theMsg = "<div class='alert alert-danger'>There is no such ID</div>";
                redirectHome($theMsg, "back", 3);
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back", 3);
        }
    } elseif ($do == "Delete") {
        echo "<h1 class='text-center'>" . lang("del item") . "</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        if (checkItem("Item_ID", "items", $itemid)) {
            $stmt = $con->prepare("DELETE from items where Item_ID = :itemid ");
            $stmt->bindParam(":itemid", $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>1 Record Deleted</div>";
            redirectHome($theMsg, "back");
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "Approve") {
        echo "<h1 class='text-center'>" . lang("approve item") . "</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
        $check = checkItem("Item_ID", "items", $itemid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE  items set Approve = 1 where Item_ID = ?");
            $stmt->execute(array($itemid));
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
