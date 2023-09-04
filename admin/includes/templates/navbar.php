<nav class="navbar navbar-dark bg-dark navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?= lang("home") ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="category.php"><?= lang("categories") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="items.php"><?= lang("items") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="members.php"><?= lang("members") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="comments.php"><?= lang("comments") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="#"><?= lang("statistics") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="#"><?= lang("logs") ?></a>
                </li>
            </ul>
            <!-- <ul class="nav-item dropdown"> -->
            <ul class="nav navbar-nav navbar-right">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    ahmed
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            href="members.php?do=Edit&userid=<?= $_SESSION["ID"] ?>"><?= lang("edt prof") ?></a></li>
                    <li><a class="dropdown-item" href="#"><?= lang("settings") ?></a></li>
                    <li><a class="dropdown-item" href="../index.php"><?= lang("visit shop") ?></a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php"><?= lang("logout") ?></a></li>
                </ul>
            </ul>
        </div>
    </div>
</nav>