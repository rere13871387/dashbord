<?php
include('db.php');
if (isset($_POST['newevdes'])) {
    
$posted = filter_var($_POST['newevdes'], FILTER_SANITIZE_STRING);
if (isset($_POST['newevdat'])) {
    $dat = intval($_POST['newevdat'])*86400+time();
    $sql = "INSERT INTO `events` (dst, des) VALUES('$dat', '$posted')";
    mysqli_query($con, $sql);
} 
}