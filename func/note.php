<?php
include('db.php');
if (isset($_POST['myTextArea'])) {
    $posted = filter_var($_POST['myTextArea'], FILTER_SANITIZE_STRING);
    $sql = "UPDATE note SET txt='$posted' WHERE id = 1";
    mysqli_query($con, $sql);
}
