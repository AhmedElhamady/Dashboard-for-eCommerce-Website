<?php
session_start();
$pageTitle = "New Item";
include("inti.php"); // important file include connection DB and langs
if (isset($_SESSION["user"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $formErrors = array();
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST["country"], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST["status"], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST["category"], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($name) < 4 || !isset($_POST["name"]))
            $formErrors[] = "Item name must be at least 4 chars";
        if (strlen($desc) < 10 || !isset($_POST["description"]))
            $formErrors[] = "Item description must be at least 10 chars";
        if (strlen($country) < 2 || !isset($_POST["country"]))
            $formErrors[] = "Item country must be at least 2 chars";
        if (empty($price) || !isset($_POST["price"]))
            $formErrors[] = "Item price must be not empty";
        if (empty($status) || !isset($_POST["status"]))
            $formErrors[] = "Item price must be not empty";
        if (empty($category) || !isset($_POST["category"]))
            $formErrors[] = "Item category must be not empty";
        if (empty($formErrors)) {
            $stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                    VALUES (:name, :desc, :price, :country, :stat, now(), :cat, :member ) ");
            $stmt->execute(array(
                "name" => $name,
                "desc" => $desc,
                "price" => $price,
                "country" => $country,
                "stat" => $status,
                "cat" => $category,
                "member" => $_SESSION["uid"]
            ));
            if ($stmt) {
                $theMsg = $stmt->rowCount() . " Record Inserted";
            }
        }
    } ?>
    <h1 class="text-center">Create new ad</h1>
    <div class="create-ad block">
        <div class="container">
            <?php
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo "<p class='alert alert-danger p-2'>" . $error . "</p>";
                }
            }
            if (isset($theMsg)) {
                echo "<p class='alert alert-success p-2'>" . $theMsg . "</p>";
            }
            ?>
            <div class="card">
                <div class="card-header text-bg-primary">
                    My Ads
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="" method="post">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label"><?= lang("name") ?></label>
                                    <div class="col-sm-9 holder">
                                        <input type="text" data-live="name" class="form-control live" name="name" placeholder="Name of the category ">
                                    </div>
                                </div>
                                <div class="row mb-3 holder">
                                    <label class="col-sm-3 col-form-label"><?= lang("description") ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" data-live="desc" class="form-control live" name="description" placeholder="Discribe of the item">
                                    </div>
                                </div>
                                <div class="row mb-3 holder">
                                    <label class="col-sm-3 col-form-label"><?= lang("price") ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" data-live="price" class="form-control live" name="price" placeholder="Item price">
                                    </div>
                                </div>
                                <div class="row mb-3 holder">
                                    <label class="col-sm-3 col-form-label"><?= lang("country") ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="country" placeholder="Country made">
                                    </div>
                                </div>
                                <div class="row mb-3 holder">
                                    <label class="col-sm-3 col-form-label"><?= lang("status") ?></label>
                                    <div class="col-sm-9">
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
                                    <label class="col-sm-3 col-form-label"><?= lang("category") ?></label>
                                    <div class="col-sm-9">
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
                                <div class="row mb-3">
                                    <div class="offset-md-3 col-sm-9">
                                        <input type="submit" class="btn btn-primary col-sm-3" value="<?= lang("add") ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="img-thumbnail item-box live-preview">
                                <span data-name="0" class="price">0</span>
                                <img class="img-fluid" src="layout/images/img.png" alt="">
                                <div class="caption">
                                    <h3 class="name" data-name="name">name</h3>
                                    <p class="desc" data-name="Description">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
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