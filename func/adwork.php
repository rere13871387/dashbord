<?php
include('db.php');
if (isset($_POST['workdesinput'])) {
$posted = filter_var($_POST['workdesinput'], FILTER_SANITIZE_STRING);
if (isset($_POST['firstName'])) {
    $dat = $_POST['firstName'];
} else {
    $dat = '';}
    $sql = "INSERT INTO `works` (des, dat, stat) VALUES('$posted', '$dat', '0')";
    mysqli_query($con, $sql);
}