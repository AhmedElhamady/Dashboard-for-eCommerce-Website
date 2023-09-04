<?php
session_start();
if (isset($_SESSION["Username"])) {
    $pageTitle = "Categories";
    include "inti.php";
    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    if ($do == "Manage") {
        $sort = "Asc";
        $sortArray = array("Asc", "Desc");
        if (isset($_GET["sort"]) && in_array($_GET["sort"], $sortArray)) {
            $sort = $_GET["sort"];
        }
        $stmt = $con->prepare("SELECT * FROM categories order by Ordering $sort ");
        $stmt->execute();
        $cats = $stmt->fetchAll(); ?>
        <h1 class="text-center"><?= lang("mng categories") ?></h1>
        <div class="container categories">
            <div class="panel-heading">
                <i class="fa-solid fa-layer-group"></i>
                <?= lang("categories") ?>
                <div class="options">
                    Ordering : [ <a href="?sort=Asc" class="<?= $sort == "Asc" ? "active" : "" ?>">Asc</a> | <a href="?sort=Desc" class="<?= $sort == "Desc" ? "active" : "" ?>">Desc</a> ] -
                    View : [ <span class="active" data-view="full">Full</span> | <span data-view="">Classic</span> ]
                </div>
            </div>
            <div class="panel-body">
                <?php foreach ($cats as $cat) : ?>
                    <div class="cat">
                        <div class="hidden-bottons">
                            <a href="category.php?do=Edit&catid=<?= $cat["ID"] ?>" class='btn btn-primary'>
                                <i class='fa-sharp fa-solid fa-pen-to-square'></i></a>
                            <a href="category.php?do=Delete&catid=<?= $cat["ID"] ?>" class='btn btn-danger confirm'>
                                <i class='fa-solid fa-trash'></i></a>
                        </div>
                        <h3><?= $cat["Name"] ?></h3>
                        <div class="full-view">
                            <p><?= !empty($cat["Description"]) ? $cat["Description"] : "This category has no description" ?></p>
                            <?= ($cat["Visibility"]) == 1 ? "<span class='visibility'>Hidden</span>" : "" ?>
                            <?= ($cat["Allow_Comment"]) == 1 ? "<span class='commenting'>Comments: Disable</span>" : "" ?>
                            <?= ($cat["Allow_Ads"]) == 1 ? "<span class='advertises'>Ads: Disable</span>" : "" ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <a href="category.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i>
                <?= lang("new category") ?></a>
        </div>
    <?php
    } elseif ($do == "Add") { ?>
        <h1 class="text-center"><?= lang("add category") ?></h1>
        <div class="container">
            <form action="?do=Insert" method="post">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" required="requered" name="name" autocomplete="off" placeholder="Name of the category ">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("description") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control password" name="description" autocomplete="new-password" placeholder="Discribe of the category">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("ordering") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" name="ordering" autocomplete="off" placeholder="Number to arrange category">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("visible") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="visibility" checked id="vis-yes">
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="visibility" id="vis-no">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("comment") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="commenting" checked id="com-yes">
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="commenting" id="com-no">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("ads") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="ads" checked id="ads-yes">
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="ads" id="ads-no">
                            <label for="ads-no">No</label>
                        </div>
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
        echo "<h1 class='text-center'>" . lang("add category") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $name = $_POST["name"];
            $description = $_POST["description"];
            $ordering = $_POST["ordering"];
            $visibility = $_POST["visibility"];
            $commenting = $_POST["commenting"];
            $ads = $_POST["ads"];

            if (checkItem("Name", "categories", $name) == 1) {
                $theMsg = "<div class='alert alert-danger'>Sorry this <strong>category</strong> is already exist
                    </div>";
                redirectHome($theMsg, "back");
            } else {
                $stmt = $con->prepare("INSERT INTO categories (Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
                    VALUES (:zname, :zdesc, :zorder, :zvis, :zcom, :zads ) ");
                $stmt->execute(array(
                    "zname" => $name,
                    "zdesc" => $description,
                    "zorder" => $ordering,
                    "zvis" => $visibility,
                    "zcom" => $commenting,
                    "zads" => $ads
                ));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted" . "</div>";
                redirectHome($theMsg, "back");
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directly</div>";
            redirectHome($theMsg, "back");
        }
        echo "</div>";
    } elseif ($do == "Edit") {
        echo '<h1 class="text-center">' . lang("edt category") . '</h1>';
        echo '<div class="container">';
        $catid = isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0;
        $stmt = $con->prepare("select * from categories where ID = ? limit 1");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) { ?>
            <form action="?do=Update" method="post">
                <input type="hidden" value="<?= $cat["ID"] ?>" name="id">

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("name") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" value="<?= $cat["Name"] ?>" name="name" autocomplete="off" required="requered" placeholder="Name of the category ">
                    </div>
                </div>
                <div class="row mb-3 holder">
                    <label class="col-sm-2 col-form-label"><?= lang("description") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control password" value="<?= $cat["Description"] ?>" name="description" placeholder="Discribe of the category">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("ordering") ?></label>
                    <div class="col-sm-10 holder">
                        <input type="text" class="form-control" value="<?= $cat["Ordering"] ?>" name="ordering" autocomplete="off" placeholder="Number to arrange category">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("visible") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="visibility" id="vis-yes" <?= $cat["Visibility"] == 0 ? "checked" : "" ?>>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="visibility" id="vis-no" <?= $cat["Visibility"] == 1 ? "checked" : "" ?>>
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("comment") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="commenting" id="com-yes" <?= $cat["Allow_Comment"] == 0 ? "checked" : "" ?>>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="commenting" id="com-no" <?= $cat["Allow_Comment"] == 1 ? "checked" : "" ?>>
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"><?= lang("ads") ?></label>
                    <div class="col-sm-10 holder">
                        <div>
                            <input type="radio" value="0" name="ads" id="ads-yes" <?= $cat["Allow_Ads"] == 0 ? "checked" : "" ?>>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" value="1" name="ads" id="ads-no" <?= $cat["Allow_Ads"] == 1 ? "checked" : "" ?>>
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="offset-md-2 col-sm-10">
                        <input type="submit" class="btn btn-primary col-sm-2" value="<?= lang("save") ?>">
                    </div>
                </div>
            </form>
<?php
        } else {
            $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($theMsg, "back", 3);
        }
        echo '</div>';
    } elseif ($do == "Update") {
        echo "<h1 class='text-center'>" . lang("add category") . "</h1>";
        echo "<div class='container'>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST["id"];
            $name = $_POST["name"];
            $description = $_POST["description"];
            $ordering = $_POST["ordering"];
            $visibility = $_POST["visibility"];
            $commenting = $_POST["commenting"];
            $ads = $_POST["ads"];

            if (checkItem("ID", "categories", $id)) {
                $stmt = $con->prepare("UPDATE categories SET Name=?, Description=?, Ordering=?, Visibility=?, Allow_Comment=? , Allow_Ads=?
                    WHERE ID=? ");
                $stmt->execute(array($name, $description, $ordering, $visibility, $commenting, $ads, $id));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated" . "</div>";
                redirectHome($theMsg, "back", 3);
            } else {
                $theMsg = "<div class='alert alert-danger'>There is no such id</div>";
                redirectHome($theMsg, "back", 3);
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>You can not access this page directlly</div>";
            redirectHome($theMsg, "back", 3);
        }
        echo '</div>';
    } elseif ($do == "Delete") {
        echo "<h1 class='text-center'>" . lang("del member") . "</h1>";
        echo "<div class='container'>";
        $catid = isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0;
        if (checkItem("ID", "categories", $catid)) {
            $stmt = $con->prepare("DELETE from categories where ID = :catid ");
            $stmt->bindParam(":catid", $catid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>1 Record Deleted</div>";
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
