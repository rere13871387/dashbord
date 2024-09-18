<?php
$con = mysqli_connect("localhost", "root", "", "school");
// Check connection
if (mysqli_connect_errno()) {
    echo "خطا: " . mysqli_connect_error();
}