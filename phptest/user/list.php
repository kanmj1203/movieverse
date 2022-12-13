<?php

require("../db_connect.php");
	

// 페이지네이션

$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
// 현재 페이지 0보다 작으면 1로 설정
$page = 0 < $page ? $page : 1;

$page_count = 5;
$page_lines = 5;

$numRecords = $db->query("select count(*) from user")->fetchColumn();
// $numRecords = (int)$numRecords;
$page_total = (int)$numRecords;
$page_total_pages = ceil($page_total  / $page_count);

// 현재 페이지 page_count보다 크면 page_total_pages로 설정
$page = $page_total_pages < $page ? $page_total_pages : $page;


$page_list = ceil($page / $page_lines);

$page_last = $page_list <= $page_total_pages ? $page_list * $page_count : $page_total_pages;
$page_last = $page_count > $page_total_pages ? $page_total_pages : $page_last;

$page_start = $page_last - ($page_count - 1) <= 0 ? 1 : $page_last - ($page_count - 1);
// echo gettype($page_start);
$page_prev = $page_start - 1;
$page_next = $page_last + 1;

$stat = $page >= 1 ? ($page -1) * $page_count : $page * $page_count;


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css"href="../admin_page.css">
    <style>
		
    </style>
</head>

<body>
<div class="all">
 <div class="head">
 	<a href="../../index.php"><img class="logo"src="../../img/logo/logo_txt.png"></a>
</div>
<div class="sidenav">
	<a href="../list.php">관리자 페이지</p>
	<a href="../user/list.php">회원 관리</a>
	<br><br>
	<a href="../review/list.php">리뷰 관리</a>

	</div>

<div class="main_view">
<h1 style="text-align: center;"> 관리자 페이지 </h1>
<h2 style="text-align: center;"> User 정보 </h2>

<table>

    <tr style="color: #ffffff;">
        <th class="member_num">유저 번호    </th>
        <th class="email"  >이메일    </th>
		<th class="" >아이디  </th>
		<th class="" >비밀번호  </th>
        <th class="nickname" >닉네임  </th>
        <th class="join_date">가입날짜</th>
        <th class="bookmark_num" >북마크 개수</th>
        <th class="review_num" >리뷰 개수</th>
        
    </tr>

<?php

	$query = $db->query("select * from user limit $stat, $page_count ");

    while ($row = $query->fetch()) {

	$review_count = $db->query("select count(*) from review where member_num='$row[member_num]'")->fetchColumn(); 
	$bookmark_count = $db->query("select count(*) from bookmark where member_num='$row[member_num]'")->fetchColumn();  
	// $my_info_query = $db->query("select * from user where member_num='$_SESSION[userNum]'");
		

?>
<!-- <a href="view.php?member_num=<=$row["member_num"]?>"> -->
		<tr style="border-bottom: 50px solid #fffff;"	>
            <td><?=$row["member_num"]?></td>
            <td style="text-align:left;">
                <?=$row["email"]?>
            </td>
			<td><?=$row["identification"]?></td>
			<td><?=$row["passwd"]?></td>
            <td><?=$row["nickname"]?></td>
			<td><?=$row["join_date"]?></td>

			<td><?=$bookmark_count?></td>
			<td><?=$review_count?></td>
			<td class="admin_button"><a href="view.php?member_num=<?=$row["member_num"]?>">정보보기</a></td>
			<td class="admin_button"><a href="delete.php?member_num=<?=$row["member_num"]?>">삭제하기</a></td>
        </tr>
		<?php
	}
?>
<?php
                    
                ?>
			


</table>

<div class="pagination">
                <ul>
                    <li>
                    <button 
                    class="pagination_button"
                    type="button"
                    onclick="location.href='list.php?page=<?=$page_prev?>'">
                    <
                    </button></li>
<?php
                for($i = $page_start; $i <= $page_last; $i++){
?>
                    <li><button
                    class="pagination_button <?=$i == $page ? 'now_page' : ''?>"
                    type="button"
                    onclick="location.href='list.php?page=<?=$i?>'"
                    >
                    <?=$i?></button></li>
<?php
                }
?>
                    <li><button 
                    class="pagination_button"
                    type="button"
                    onclick="location.href='list.php?page=<?=$page_next?>'" >
                    >
                    </button></li>
            </ul>
            </div>
</div>


</body>
</html>