<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layout/css/bootstrap.min.css" />
    <link rel="stylesheet" href="layout/css/all.min.css" />
    <link rel="stylesheet" href="layout/css/front.css" />
    <title><?php setTitle(); ?></title>
</head>

<body>
    <div class="upper-nav">
        <div class="container">
            <?php if (isset($_SESSION["user"])) : ?>
                <p style="margin: 0">Welcome <?= $_SESSION["user"] ?></p>
                <a href="profile.php">My profile</a>
                <a href="newad.php">New Ad</a>
                <a href="logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">
                    <span class="">Login/Signup</span>
                </a>
            <?php endif ?>
        </div>
    </div>
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?= lang("home shop") ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse cust-header" id="navbarSupportedContent">
                <ul class="nav navbar-nav navbar-right">
                    <?php foreach (getCats() as $cat) : ?>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="categories.php?pageid=<?= $cat["ID"] ?>"><?= $cat["Name"] ?></a>
                        </li>
                    <?php endforeach  ?>
                </ul>
            </div>
        </div>
    </nav>