<?php
session_start();
$pageTitle = "Category";
include("inti.php"); ?>

<div class="container">
    <h1 class="text-center">Categories Items</h1>
    <div class="container">
        <div class="row">
            <?php foreach (getItems("Cat_ID", $_GET["pageid"], 1) as $item) : ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="img-thumbnail item-box">
                        <span class="price"><?= $item["Price"] ?></span>
                        <img class="img-fluid" src="layout/images/img.png" alt="">
                        <div class="caption">
                            <h3><a href="item.php?itemid=<?= $item["Item_ID"] ?>"><?= $item["Name"] ?></a></h3>
                            <p><?= $item["Description"] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<?php include($tpl . "footer.php"); ?>