<?php
session_start();
$pageTitle = "eCommerce shop";
include("inti.php"); // important file include connection DB and langs
?>
<div class="all">
    <div class="container mt-4">
        <div class="row">
            <?php
            $items = getAll("*", "items", "where Approve = 1", "Item_ID", "DESC");
            foreach ($items as $item) : ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="img-thumbnail item-box">
                        <span class="price">$<?= $item["Price"] ?></span>
                        <img class="img-fluid" src="layout/images/img.png" alt="">
                        <div class="caption">
                            <h3><a href="item.php?itemid=<?= $item["Item_ID"] ?>"><?= $item["Name"] ?></a></h3>
                            <p><?= $item["Description"] ?></p>
                            <div class="date"><?= $item["Add_Date"] ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php
include($tpl . "footer.php");
