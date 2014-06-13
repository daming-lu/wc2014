<?php
$content = "haha";
$content = wordwrap($content, 70);
$date = date("Y-m-d H:i:s");
$result = mail(
    "handtemple@gmail.com",
    "Alert $date!",
    $content."\n"
);
if($result) {
    echo "Success\n";
} else {
    echo "Fail\n";
}
?>
 