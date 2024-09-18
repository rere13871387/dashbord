<?php
require 'func/jdf.php';
include('func/db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم یادآوری</title>
    <link rel="icon" href="icon.jpg">
    <link rel="stylesheet" href="st.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="folder">
        <div class="tabs">
            <button class="tab active" onclick="openTab(event, 'tab-1')">
                <div><span>نمای کلی</span></div>
            </button>
            <button class="tab" onclick="openTab(event, 'tab-2')">
                <div><span>کار‌ها</span></div>
            </button>
            <button class="tab" onclick="openTab(event, 'tab-3')">
                <div><span>رویداد‌ها</span></div>
            </button>
            <button class="tab" onclick="openTab(event, 'tab-4')">
                <div><span>یادداشت‌ها</span></div>
            </button>
        </div>
        <div class="content">
            <div class="content__inner" id="tab-1">
                <div class="page">
                    <div class="top">
                        <table>
                            <tr>
                                <th>
                                    <div class="date">
                                        <div class="dt">
                                            <?php
                                            echo 'امروز' . ' ' . jdate('l') . ' ' . jdate('j') . ' ' . jdate('F') . ' است.';
                                            ?>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class="rmw"><?php
                                                        $sql = "SELECT * from works WHERE stat = 0";
                                                        echo mysqli_num_rows(mysqli_query($con, $sql)) . ' ';
                                                        ?>کار باقی‌مانده</div>
                                </th>
                                <th> <?php
                                        $time = time();
                                        $sql = "SELECT * FROM events WHERE dst > '$time';";
                                        echo mysqli_num_rows(mysqli_query($con, $sql)) . ' ';


                                        ?> رویداد </th>
                            </tr>
                        </table>
                    </div>
                    <div class="paydiv">
                        <div class="payd"> <?php
                                            $now = new DateTime();
                                            $future_date = new DateTime('2023-01-30 0:00:00');

                                            $interval = $future_date->diff($now);

                                            $rd = $interval->format("%a days");
                                            $rd = str_replace('days', '', $rd);
                                            echo filter_var($rd, FILTER_SANITIZE_STRING);
                                            ?> </div>
                        <div> روز تا آزمون پایش </div>
                    </div>
                </div>
                <br />
                <script>
                    $(document).ready(function() {
                        $('#submit').click(function() {
                            var languages = [];
                            $('.get_value').each(function() {
                                if ($(this).is(":checked")) {
                                    languages.push($(this).val());
                                }
                            });
                            languages = languages.toString();
                            $.ajax({
                                url: "func/works.php",
                                method: "POST",
                                data: {
                                    languages: languages
                                },
                                success: function(data) {
                                    $('#result').html(data);
                                }
                            });
                        });
                    });
                </script>
            </div>
            <div class="content__inner" id="tab-2">
                <div class="page">
                    <div class="sow">
                        <?php
                        $sql = "SELECT * FROM works WHERE stat = 0";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) < 1) {
                            echo 'کاری وجود ندارد!:)';
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $wid = $row['id'];
                                $wdes = $row['des'];
                                $wdat = $row['dat'];
                                echo '<input type="checkbox" class="get_value" value="' . $wid . '" />' . $wdes . "|" . $wdat . '<br />';
                            }
                        }
                        ?>
                        <input type="text" name="workdesinput" id="workdesinput" placeholder="توضیحات کار">
                        <input type="text" name="firstName" id="firstName" placeholder="مهلت به حروف">
                        <button type="button" name="submitnewwork" class="btn btn-info" id="submitnewwork">افزودن</button>
                    </div>
                    <script>
                        $(document).ready(function() {

                            $("#submitnewwork").click(function() {
                                var workdesinput = $("#workdesinput").val();
                                var firstName = $("#firstName").val();
                                if (workdesinput == '') {
                                    alert("فیلد را پر کنید!");
                                    return false;
                                }

                                $.ajax({
                                    type: "POST",
                                    url: "func/adwork.php",
                                    data: {
                                        firstName: firstName,
                                        workdesinput: workdesinput
                                    },
                                    cache: false,
                                    success: function(data) {
                                        alert('ثبت شد!');
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr);
                                    }
                                });

                            });

                        });
                    </script>
                    <div id="result"></div>
                </div>
                <button type="button" name="submit" class="btn btn-info" id="submit">ثبت</button>
            </div>
            <div class="content__inner" id="tab-3">
                <div class="page">
                    <?php
                    $time = time();
                    $sql = "SELECT * FROM events WHERE dst > '$time';";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) < 1) {
                        echo 'رویدادی وجود ندارد.';
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $edate = intval($row['dst']);
                            $edes = $row['des'];
                            $edate = floor(($edate - $time) / 86400);
                            echo $edes . '|' . $edate . 'روز دیگر';
                            echo '<br>';
                        }
                    }
                    ?>
                    <div class="newev">
                        <input type="text" name="newevdes" id="newevdes" placeholder="توضیحات">
                        <input type="number" name="newevdat" id="newevdat" placeholder="روز باقی‌مانده">
                        <button type="button" name="submitnewevent" class="btn btn-info" id="submitnewevent">افزودن</button>
                    </div>
                    <script>
                        $(document).ready(function() {

                            $("#submitnewevent").click(function() {
                                var newevdes = $("#newevdes").val();
                                var newevdat = $("#newevdat").val();
                                if (newevdes == '' || newevdat == '') {
                                    alert("فیلدها را پر کنید!");
                                    return false;
                                }

                                $.ajax({
                                    type: "POST",
                                    url: "func/adev.php",
                                    data: {
                                        newevdes: newevdes,
                                        newevdat: newevdat
                                    },
                                    cache: false,
                                    success: function(data) {
                                        alert('ثبت شد!');
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr);
                                    }
                                });

                            });

                        });
                    </script>
                </div>
            </div>
            <div class="content__inner" id="tab-4">
                <div class="page">
                    <textarea id="myTextArea" name="myTextArea"><?php
                                                                $sql = "SELECT * FROM note WHERE id = 1;";
                                                                $result = mysqli_query($con, $sql);
                                                                $row = mysqli_fetch_assoc($result);
                                                                echo filter_var($row['txt'], FILTER_SANITIZE_STRING);
                                                                ?></textarea>
                    <div id="autoSave" name="myTextArea"></div>
                    <input type="hidden" name="post_id" id="post_id" />
                    <input type="hidden" name="post_title" id="post_title" class="form-control" />

                    <script>
                        $(document).ready(function() {
                            function autoSave() {
                                var post_title = $('#post_title').val();
                                var myTextArea = $('#myTextArea').val();
                                var post_id = $('#post_id').val();
                                if (myTextArea != '') {
                                    $.ajax({
                                        url: "func/note.php",
                                        method: "POST",
                                        data: {
                                            myTextArea: myTextArea
                                        },
                                        dataType: "text",
                                        success: function(data) {
                                            if (data != '') {
                                                $('#post_id').val(data);
                                            }
                                            $('#autoSave').text("ذخیره شد.");
                                            setInterval(function() {
                                                $('#autoSave').text('');
                                            }, 5000);
                                        }
                                    });
                                }
                            }
                            setInterval(function() {
                                autoSave();
                            }, 5000);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js.js"></script>

</html>