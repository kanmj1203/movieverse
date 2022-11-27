<?php
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
	<link rel="stylesheet" type="text/css"href="js/list.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/data.js"></script>
	
  </head>
  
  <script>
	
	$(function () {	
		$("#searchInput").autocomplete({  
			source: List,
			focus : function(event, ui) { 	
				return false;
			},
			minLength: 1,
			delay: 100,	
			

		});
	});

</script>

  
 <body>
 <div class="all">
  <header>
  <div class="head">

<img class="logo"src="img/logo.png">
     <a class="home" onclick="location.href='index.php?bid='"> 홈 </a>
       <a class="dramatap" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a>
       <a class="movietap" onclick="location.href='movie.php?bid=';"> 영화</a>
	
 <form class="serch" action="search_result.php" method="get">
      <input id="searchInput"  type="text"  placeholder="드라마/시리즈, 영화 제목 검색해주세요" name="search" size="35" required="required" /> 
    </form>
		
	   <img class="serch_Img"src="img/serch.png">
<?php 
$a=1;
if($a==1){
?>
	   <button class="login_btn" onclick="location.href='login.php';">로그인</button>
<?php
	   }else if($a==1){
?>
<button class="login_btn" onclick="location.href='login.php';">로그인</button>
<?php
	   }else{
?>	
<button class="login_btn" onclick="location.href='login.php';">로그인</button>
<?php	   
	   }
?>
</div>	
<div class="sidenav">
  <p id="side_TEXT">계정정보</p>
  <a href="myinfo">내 계정정보</a>
  <a href="pwre">비밀번호 수정</a>
  <a href="mybook">내 북마크</a>
  <a href="myreview">작성한 평가</a>
</div>

<div class="main">

   <div class="main_Img">
     <div class="user_Img"></div>
     <p>사용자 이메일</p>
     <p>사용자 닉네임</p>
 </div>
 <div class="divpwre">
  <h1>비밀번호 수정</h1>
       <p>현재 비밀번호</p>
<input style="" id="id" type="password" name="pw" placeholder="현재 비밀번호">
<p>새로운 비밀번호</p>
<input style="" id="id" type="password" name="pw" placeholder="새로운 비밀번호">
<p>비밀번호 확인</p>
<input style="" id="id" type="password" name="pw" placeholder="비밀번호 확인">
<button class="login_btn" onclick="javascript:history.back()">정보수정</button>
<button class="login_btn" onclick="location.href='login.php';">취소하기</button>
  </div>
  </div>


<div style="margin: 1600px 0px 0px;"><a href=".serch">제일 위로</a></div></div>

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