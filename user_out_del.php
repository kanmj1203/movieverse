<?php

session_start();
require("db_connect.php");
$num = $_SESSION["userNum"];



$db->exec("delete from user where  member_num='$num'");
$db->exec("delete from review where member_num='$num'");
$db->exec("delete from bookmark where member_num='$num'");

unset($_SESSION["userId"]);
unset($_SESSION["userName"]);
unset($_SESSION["userPw"]);
unset($_SESSION["userNum"]);
unset($_SESSION["userEmail"]);

echo "
    <script>
        alert('회원 탈퇴가 완료되었습니다.');
        location.href = 'index.php';
    </script>
";
?>