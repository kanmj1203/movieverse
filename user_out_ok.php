<?php

session_start();
require("db_connect.php");
	$_POST['nickname']= empty($_POST['nickname']) ? "" : $_POST['nickname'];
    $_POST['identification']= empty($_POST['identification']) ? "" : $_POST['identification'];	
    $_POST['password']= empty($_POST['password']) ? "" : $_POST['password'];	

if ($_POST['nickname'] == "" || $_POST['identification'] == "" || $_POST['password'] == "") {
    echo "
    <script>
        alert('빈 칸 없이 입력해 주세요.');
        location.href = 'history.back(),',window.close();
    </script>
";
}

if($_POST['identification']!=$_SESSION["userId"] ||  $_POST['nickname']!=$_SESSION["userName"] || $_POST['password']!=$_SESSION["userPw"]){
    echo "
        <script>
            alert('일치하지 않는 내용이 있습니다.');
            location.href = 'history.back(),',window.close();
        </script>
    ";
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

  <div style="text-align: center">
  삭제 하시겠습니까?<br><br>
        <button onclick="opener.location.href='user_out_del.php'; window.close();">네</button>
        <button onclick="window.close()">아니요</button>
    </div>

</body>
</html>
