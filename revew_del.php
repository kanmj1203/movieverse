<?php
session_start();
require("db_connect.php");

$review_num = $_REQUEST["review_num"];
$id =isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
$choice =isset($_REQUEST["choice"]) ? $_REQUEST["choice"] : "";

$db->query("delete from review where review_num = $review_num");
$db->query("delete from review_like where like_review_num = $review_num");


echo "
    <script>
        alert(\"리뷰가 삭제되었습니다.\");
        // history.back();
        location.href = 'choice.php?choice=$choice&id=$id';
    </script>
";
?>