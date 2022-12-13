<?php
	
	
			
	$email  = 	"";
	$passwd = "";
	$nickname = "";
	$identification  = 	"";
	$img_link =	"";

	$action = "update.php";
	
	$member_num = empty($_REQUEST["member_num"]) ? "" : $_REQUEST["member_num"];
	

	
	// $num = isset($_REQUEST["num"] ? $_REQuEST["num"] : "";
	if ($member_num) {					
		require("../db_connect.php");
		$query = $db->query("select * from user where member_num=$member_num");
		
		if ($row = $query->fetch()) {	
			$email  = 	isset($row["email"]) ? $row["email"] : "";
			$passwd  = 	isset($row["passwd"]) ? $row["passwd"] : "";
			$nickname = isset($row["nickname"]) ? $row["nickname"] : "";
			$identification = isset($row["identification"]) ? $row["identification"] : "";
			$img_link  = isset($row["img_link"]) ? $row["img_link"] : "";
			
			// $action = "update.php";
			
		}
	}
	



	
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css"href="../admin_page.css">
    
    <meta charset="UTF-8">
    <style>
        /* table { width:900px; } */
        /* th    { width:150px; background-color:#3482EA; } */
        input[type=text], textarea { width:100%; font-size : 1rem;}
		input[type=checkbox] + label { 
		
		
		
		text-align:center;
		
		cursor: pointer;
		
		width: 100px;
		height:20px;
		}
		

    </style>
</head>
<body>


<div class="write_main_view">
<h1>회원 정보 수정</h1> 
<form method="post" action="<?=$action?>" enctype="multipart/form-data">
<input type="text" name="member_num"  maxlength="80" value="<?=$member_num?>" hidden>

<div>
    <table>
    	<tr>
            <th>이메일</th>
            <td><input type="text" name="email"  maxlength="80"
                       value="<?=$email?>">

            </td>
        </tr>
        
        
        <tr>
            <th>아이디</th>
            <td><input type="text" name="id"  maxlength="80"
                       value="<?=$identification?>">

            </td>
        </tr>
        <tr>
            <th>비밀번호</th>
            <td><input type="text" name="passwd"  maxlength="80"
                       value="<?=$passwd?>">

            </td>
        </tr>
        
		
		
        <tr>
            <th>닉네임</th>
            <td><input type="text" name="nickname" maxlength="500"
                       value="<?=$nickname?>">

            </td>
        </tr>
        
		<tr>
            <th>프로필 사진</th>
            <td>
				<label for="file"></label>
				<input type="file" name="imgFile" id="file" class="upload-hidden">
				<input class="upload-name" value="" placeholder="첨부파일" hidden>

			<input type="text" name="img_link" maxlength="500"
                       value="<?=$img_link?>" readonly>

            </td>
        </tr>
	
    </table>
	
 <br>
    <input type="submit" value="저장">
    <input type="button" value="취소" onclick="history.back()">
</div>
</form>

</div>
</div>
</body>
</html>

