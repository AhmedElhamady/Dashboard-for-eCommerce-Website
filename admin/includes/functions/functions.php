<?php

// page title
function setTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo lang("defulte");
    }
}

// error 
function redirectHome($theMsg, $url = null, $seconds = 3)
{
    $link = "Homepage";
    if ($url === null) {
        $url = "index.php";
    } else {
        $url = isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] != "" ? $_SERVER["HTTP_REFERER"] : "index.php";
        if ($url != "index.php") $link = "Previous page";
    }
    echo "<div class='container'>";
    echo $theMsg;
    echo "<div class='alert alert-success'>You will redirect to $link after $seconds seconds</div>";
    echo "</div>";
    header("refresh:$seconds;url=$url");
    exit;
}

// check item exist 
function checkItem($column, $table, $value)
{
    global $con;
    $statment = $con->prepare("SELECT $column from $table where $column = ? ");
    $statment->execute(array($value));
    $count = $statment->rowCount();
    return $count;
}

// count items
function countItems($column, $table)
{
    global $con;
    $statment = $con->prepare("SELECT COUNT($column) from $table");
    $statment->execute();
    return $statment->fetchColumn();
}

// get latest
function getLatest($select, $table, $order, $limit = 5)
{
    global $con;
    // from me not zero
    $query = $table == "users" ? "where GroupID != 1" : "";
    $statment = $con->prepare("SELECT $select from $table $query ORDER BY $order desc limit $limit ");
    $statment->execute();
    $rows = $statment->fetchAll();
    return $rows;
}
