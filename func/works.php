<?php
include('db.php');
if (isset($_POST["languages"])) {
    if (empty($_POST["languages"])) {
        echo 'گزینه‌های مورد نظر را انتخاب نکرده‌اید!';
    } else {
        $posted =  filter_var($_POST["languages"], FILTER_SANITIZE_STRING);
        $exploded = explode(',', $posted);
        foreach ($exploded as $updated) {
            $sql = "UPDATE works SET stat='1' WHERE id='$updated'";

            if (mysqli_query($con, $sql)) {
              echo "تغییرات اعمال شد.";
            } else {
echo '.تغییرات اعمال نشد';
            }
        }
    }
}
