<?php
$dns = "mysql:host=localhost;dbname=shop";
$user = "root";
$pass = "";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
);
try {
    $con = new PDO($dns, $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "you are connected successfully";
} catch (PDOException $e) {
    echo "failed to connect " . $e->getMessage();
}





// define('ROOT_URL', 'http://localhost/fmp/');
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'shop');
// //connect to the database 
// $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// if ($connection) {
//     // echo "you are connected successfully";
// }
// if (!$connection) {
//     echo mysqli_connect_errno();
//     exit;
// }