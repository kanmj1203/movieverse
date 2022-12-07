<?php

/*
넷플 : 
{
    "display_priority": 0,
    "logo_path": "/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg",
    "provider_name": "Netflix",
    "provider_id": 8
    },

왓챠 :
{
    "display_priority": 3,
    "logo_path": "/cNi4Nv5EPsnvf5WmgwhfWDsdMUd.jpg",
    "provider_name": "Watcha",
    "provider_id": 97
},

디즈니 : 
{
    "display_priority": 1,
    "logo_path": "/dgPueyEdOwpQ10fjuhL2WYFQwQs.jpg",
    "provider_name": "Disney Plus",
    "provider_id": 337
},

웨이브 :
{
    "display_priority": 2,
    "logo_path": "/8N0DNa4BO3lH24KWv1EjJh4TxoD.jpg",
    "provider_name": "wavve",
    "provider_id": 356
},
*/

$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// 영화 데이터
$data = array( 
    // 최신순
    // array(
    //     'api_key' => $api_key,
    //     'with_watch_providers' => 8,
    //     'with_watch_providers' => 337,
    //     'with_watch_providers' => 97,
    //     'with_watch_providers' => 356,
    //     'sort_by' => 'release_date.desc',
    //     'watch_region' => 'KR',
    //     'language' => 'ko',
    //     'page' => 1
    // ),
    // 인기순
    // array(
    //     'api_key' => $api_key,
    //     'with_watch_providers' => [8, 337, 97, 356],
    //     // 'with_watch_providers' => 337,
    //     // 'with_watch_providers' => 97,
    //     // 'with_watch_providers' => 356,
    //     'sort_by' => 'popularity.desc',
    //     'watch_region' => 'KR',
    //     'language' => 'ko',
    //     'page' => 1
    // ),
    // 플랫폼 가져오기
    array(
        'api_key' => $api_key
    )
);

// 드라마/시리즈 데이터
// $tv_data = array(
//     'api_key' => '13e4eba426cd07a638195e968ac8cf19',
//     'with_watch_providers' => 8,
//     'watch_region' => 'KR',
//     'language' => 'ko',
//     'page' => 1
// );

// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
    // 최신순
    // $base_url . "/discover/movie?" . http_build_query($data[0], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[0], '', ),
    // 인기순
    // $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[0], '', )
);

// TMDB API에서 데이터 불러오기
for($i = 0; $i < count($url); $i++){
    $ch = curl_init();                                 //curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url[$i]);               //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함 
    //curl_setopt($ch, CURLOPT_SSLVERSION, 3); // SSL 버젼 (https 접속시에 필요)
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $response = curl_exec($ch);

    $sResponse[$i] = json_decode($response , true);		//배열형태로 반환

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
}

// 플랫폼 id 리스트
$providers_id = [8, 337, 97, 356];

// TMDB에서 이미지 가져오기
$tmdb_img_base_url = "https://image.tmdb.org/t/p/original/";

// // 시나리오
// $overview = $sResponse[2]['results'][0]['overview'];
// // 공백문자, 줄바꿈 치환
// $overview = str_replace(" ", "&nbsp;", $overview); //공백
// $overview = str_replace("\n", "<br>", $overview); //줄바꿈

// return $response;
// function getTitle($count) {
    // $a = $sResponse['results'];
    // for ($i=0; $i<count($sResponse['results']); $i++) {
    //     // print($sResponse['results'][$i]['title']);
    //     // $a =  $sResponse['results'];
    //     print_r($sResponse['results'][$i]);
    //     print("<br><br>");
        
    // }
// }
// print("<br><br><br>" . $url);

             
        ?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">
	
	<link rel="stylesheet" type="text/css"href="css/user.css">
	<link rel="stylesheet" type="text/css"href="css/basic.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
  </head>
  
<script>
List = [];
</script>

<div>
<?php 
	require("db_connect.php");
	session_start();
	$_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
	
?>

</div>
<script>

    $(function() {
        $("#searchInput").autocomplete({
            source: List,
            focus: function(event, ui) {
                return false;
            },
            minLength: 1,
            delay: 100,


        });
    });
</script>


  
 <body>
 <div class="all">

 <header class="header_scroll_top">
            <div class="head">  <!--header GNB-->
                <div class="header_left">
                    <ul>
                        <li><img class="logo" onclick="location.href='index.php'" src="img/logo/logo_txt.png"></li>
                        <li><a class="header_gnb" onclick="location.href='movie.php?bid=';"> 영화</a></li>
                        <li><a class="header_gnb" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a></li>

                        <?php 
                            if($_SESSION["userId"]=="admin") {
                        ?>
                            <li><a class="header_gnb" onclick="location.href='./phptest/list.php';"> 관리자 페이지</a></li>
                        <?php  
                        } 
                        ?>
                    </ul><!--header_left END-->
                </div>

                <div class="header_right">
                    <ul>   <!-- header_right -->
                    <!-- 검색 버튼 -->
                    <li>
                        <div class="search_button">
                            <input class="search_img" name="button" type="image" src="img/search_img.png" />
                        </div>
                    </li>
                    <li>
<?php 
// 사용자 프로필 사진
if($_SESSION["userId"]!=""){ // 로그인 됐을 경우
    $query3 = $db->query("select * from user where email='$_SESSION[userId]'"); 
    while ($row = $query3->fetch()) {
        $iset=$row['img_link'];
    }
?>
                        <img class="user_img" src="user_img/<?= $iset?>">
<?php
}else{ // 로그아웃일 경우
?>
                        <button class="login_btn" onclick="location.href='login.php';">로그인</button>
<?php	   
}
?>              
                    </li>
                </ul>  <!-- header_right END -->
            </div>
            
        </div> <!--head END-->
        
            <div class="hide" id="userDiv"><!--유저 정보 프로필-->
                <ul>
                    <li><h3><img class="userimg" src="user_img/<?= $iset?>"><?=$_SESSION["userName"]?></h3></li>
                    <li onclick="location.href='myinfo.php';">내 계정정보</li> 
                    <!-- <img class="userimg" src="img/user.png"> -->
                    <li onclick="location.href='pwre.php';">비밀번호 수정</li>
                    <!-- <img class="userimg" src="img/lock.png"> -->
                    <li onclick="location.href='mybook.php';">내 북마크</li>
                    <!-- <img class="userimg" src="img/bookmark.png"> -->
                    <li onclick="location.href='myreview.php';">작성한 평가</li>
                    <!-- <img class="userimg" src="img/review.png"> -->
                    <li onclick="location.href='log_out.php';">로그아웃 </li>
                    <!-- <img class="userimg" src="img/logout.png"> -->
                </ul>
            </div>  <!--userDiv END-->

            <!-- min-width:768px부터 header, 햄버거 메뉴 -->
            <div class="width_768px_logo"><img class="logo" onclick="location.href='index.php'" src="img/logo/logo_txt.png"></div>
                <div class="width_768px_search_button search_button">
                    <input class="search_img" name="button" type="image" src="img/search_img.png" />
                </div>
                <input type="checkbox" id="menu_icon">
                <label for="menu_icon">  
                    <!--label은 인라인 스타일-->
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
            <div class="menu_sidebar">
                <ul class="menu_sidebar_wrapper">
                    <!-- 로그인 상태 여부 -->
<?php
if ($_SESSION["userId"] != "") {
?>
                <li class="menu_font_size"><img class="menu_userimg" src="user_img/<?= $iset?>"><h3><?=$_SESSION["userName"]?></h3></li>          
<?php
} else {
?>
                    <li class="menu_font_size menu_login_btn"><button onclick="location.href='login.php';">로그인</button></li>          
<?php
}
?>

                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='movie.php?bid=';"> 영화</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a></li>
<?php 
if($_SESSION["userId"]=="admin") {
?>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='./phptest/list.php';"> 관리자 페이지</a></li>
<?php  
} 
if($_SESSION["userId"]!=""){ // 로그인 됐을 경우
?>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='myinfo.php';">내 계정정보</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='pwre.php';">비밀번호 수정</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='mybook.php';">내 북마크</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='myreview.php';">작성한 평가</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='log_out.php';">로그아웃</a></li>
<?php
}
?>
                </ul>
                <ul class="menu_sidebar_wrapper">
                </ul>
            </div>
        </header>

        <!-- 검색창 -->
        <div class="search_modal">
            <div class="search_modal_close">
                <img src="./img/close_icon_white.png">
            </div>
            <div class="search_wrapper">
                    <form class="search" action="search_result.php" method="get">
                        <input id="searchInput" type="text" name="search" 
                        placeholder="찾으시려는 드라마 또는 영화 제목을 입력해 주세요."
                         onfocus="this.placeholder=''" 
                         onblur="this.placeholder='찾으시려는 드라마 또는 영화 제목을 입력해 주세요.'"
                        size="70" required="required"/>
                        <input class="search_Img" name="button" type="image" src="img/search_img.png" />
                    </form>
            </div>
        </div>
        <!-- search_modal END -->
        

<?php 
	require("db_connect.php");


	$query3 = $db->query("select *  from user where email='$_SESSION[userId]' "); 
	while ($row = $query3->fetch()) {
		if($row['img_link']){
		$iset=$row['img_link'];
	}else{
			$iset="user_img";
		}
	
	
	
?>

<div style="height: 2522px;" class="sidenav">
  <p id="side_TEXT">계정정보</p>
  <a href="myinfo.php">내 계정정보</a>
  <a href="pwre.php">비밀번호 수정</a>
  <a href="mybook.php">내 북마크</a>
  <a style="background-Color: #E5E5E5;" href="myreview.php">작성한 평가</a>
</div>

<div class="main">

   <div class="main_Img">
     <div class="user_Img"><img style="width:100%;"src="user_img/<?= $iset?>"></div>
	 <p class="name"><?=$row['nickname']?></p>
	 
 <script>
$(document).ready(function(){
  var fileTarget = $('.filebox .upload-hidden');

    fileTarget.on('change', function(){
        if(window.FileReader){
            var filename = $(this)[0].files[0].name;
        } else {
            var filename = $(this).val().split('/').pop().split('\\').pop();
        }

        $(this).siblings('.upload-name').val(filename);
    });
}); 

  </script>	 
	 
	 <form name='tmp_name' method="post" action="img_plus.php" enctype="multipart/form-data">
	 
	 <div class="filebox">
    <label for="file"></label> 
	    <input type="file"  name="imgFile" id="file"class="upload-hidden">
    <input class="upload-name" value="" placeholder="첨부파일" >
	</div> 

   <button class="submit" type="submit">저장</button>
	 </form>
     <p class="id_main"><?=$row['email']?></p>



 </div>

<h2 style="margin-top:40px;margin-left:24px;">작성한 평가 </h2>
 <?php
	}
	
		$numLines= 5;
	$numLinke= 3;

	$page = empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
	$stat = ($page -1) * $numLines;
	
	
	  $query3 = $db->query("
(select *,DATE(review_date)as review_date FROM review,tv WHERE review.member_num='$_SESSION[userNum]' AND tv.id=review.tv_id)
UNION ALL
(select *,DATE(review_date)as review_date FROM review,movie WHERE review.member_num='$_SESSION[userNum]' AND movie.id=review.movie_id)LIMIT $stat , $numLines; 
 "); 

	while ($row = $query3->fetch()) {

if($row['id']!=null){
$choice="tv";
}else{
$choice="movie";
}
	   	$content = str_replace(" ", "&nbsp;", $row['review']);
			$row['review'] = str_replace("\n", "<br>", $content);
			
			
			
	$ress=$db->query("select count(*) from good where id=$row[id] and good_yes=$row[member_num] and member_num= $_SESSION[userNum]")->fetchColumn();
			
			if($ress>0){
$good_img="goodyes.png";
}else{
$good_img="good.png";
}
?>
<hr class="line" width =80% color="#707070" align="left" size=1px/>
<div>
 <span class="star">
            ★★★★★
            <span style="width:<?=$row['star_rating']*10?>%;" id="box">★★★★★</span>
            <input id="star_value" type="range" oninput="drawStar(this)" value="" step="1" min="0" max="10">
        </span>
<div  class="other_review">

<img class="review_user_img" src="https://image.tmdb.org/t/p/original<?=$row['poster_path']?>">
             <div class="review"><?=$row['review']?></div>
<p class="date"><?=$row['review_date']?></p>
   <img  style="width: 20px;margin: -21px 0 0 502px;cursor: pointer;position: absolute;"onclick="location.href='good.php?choice=<?=$choice?>&id=<?=$row['id']?>&member_num=<?=$row['member_num']?>'"src="img/<?=$good_img?>">
   <div style="color: #707070;margin: -22px 0 0 530px;position: absolute; "class=""><?=$row['good']?></div>
				
</div>
<button class="rebutton"onclick="location.href='choice.php?choice=<?=$choice?>&id=<?=$row['id']?>&re=re';">수정하기</button>
<button onclick="location.href='revew_del.php?choice=<?=$choice?>&id=<?=$row['id']?>'" class="delbutton">삭제하기</button>

</div>
 <?php
	}
?>
        <?php
	$firstLink = floor(($page - 1)/$numLinke)*$numLinke+1;
	$lastLink = $firstLink + $numLinke -1;

	$numRecords = $db->query("select count(*) from  review  where member_num='$_SESSION[userNum]'")->fetchColumn();
	$numPage = ceil($numRecords / $numLines);

	if($lastLink  >$numPage){
	   $lastLink = $numPage;
	}//올림은 ceil

?>
        <div class="page_num">
            <?php
		if($firstLink>1){
?>
            <a class="nones" href="myreview.php?page=<?= $firstLink -1 ?>"> 이전 </a>
            <?php
	 }

		for ($i=$firstLink; $i <= $lastLink; $i++){
?>
            <a class="nones" href="myreview.php?page=<?=$i?>"> <?=($i == $page) ? "<u>$i</u>" : $i?> </a>
            <?php
		}

		if($lastLink<$numPage){
?>
            <a class="nones" style='margin:0;' href="myreview.php?page=<?=$lastLink +1?>"> 다음 </a>
            <?php
		}

?>

        </div>
</div>

       <!--footer-->
       <footer class="footer">
            <div class="footer_logos">
        <?php

$provider_logo_path = [];

// $sResponse에서 마지막 요소 가져오기
$footer_provider_array = end($sResponse);

for ($res_count=0; $res_count < count($footer_provider_array["results"]); $res_count++) {
    foreach ($providers_id as $prov_id) {
        if ($prov_id == $footer_provider_array["results"][$res_count]['provider_id']) {
            array_push($provider_logo_path, $footer_provider_array["results"][$res_count]['logo_path']);
        } else {
        }
    }
}

$prov_count = 0;
foreach($provider_logo_path as $prov_logo_path){
        ?>
                <div class="footer_img" onclick="location.href='movie.php?platform=<?=$providers_id[$prov_count]?>';"><img src="<?=$tmdb_img_base_url.$prov_logo_path?>"></div>
    <?php
    $prov_count++;
}
    ?>
    </div>
                <div class="footer_text">신구대학교 팀프로젝트 6조
                    <br>
                    권은진 강민지 천서정 시지원 김나영
                    <br><br>
                    성남시 중원구 광명로377(금광2동 2685) 신구대학교 산학관 110호 
                </div>
            </footer>
            <!--footer END-->
</body>

</html>
<script type="text/javascript" src="./js/header.js"></script>