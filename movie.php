<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$sort_by = isset($_REQUEST["sort_by"]) ? $_REQUEST["sort_by"] : 'popularity.desc';

$platform =isset($_REQUEST["platform"]) ? $_REQUEST["platform"] : [8, 337, 97, 356];
$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// 영화 데이터
$data = array( 
    // 최신순
    array(
        'api_key' => $api_key,
        'with_watch_providers' => $platform,
        'sort_by' => $sort_by,
        'watch_region' => 'KR',
        'language' => 'ko',
        'page' => $page
    ),
    // 인기순
    // array(
    //     'api_key' => $api_key,
    //     'with_watch_providers' => 8,
    //     'with_watch_providers' => 337,
    //     'with_watch_providers' => 97,
    //     'with_watch_providers' => 356,
    //     'sort_by' => 'popularity.desc',
    //     'watch_region' => 'KR',
    //     'language' => 'ko',
    //     'page' => $page
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
//     'page' => $page
// );

// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
    // 최신순
    $base_url . "/discover/movie?" . http_build_query($data[0], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[0], '', ),
    // 인기순
    // $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[1], '', )
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
$providers_name = ['넷플릭스', '왓챠', '디즈니', '웨이브'];

// TMDB에서 이미지 가져오기
$tmdb_img_base_url = "https://image.tmdb.org/t/p/original/";

// $query3 = $db->query("select * FROM streaming ");
$styles="font-size: 20px;background-Color: #E5E5E5;	color:#3482EA;";

$bid =isset($_REQUEST["bid"]) ? $_REQUEST["bid"] : "";
$search =isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">

    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/movie_tv.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/data.js"></script>

</head>

<script>
List = [];
</script>

<div>
<?php 
	require("db_connect.php");
	session_start();
	$_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
    // $_SESSION["userNum"] = empty($_REQUEST["userNum"]) ? "" : $_REQUEST["userNum"];
	
$query3 = $db->query("SELECT title FROM tv UNION SELECT title  FROM movie "); 
	while ($row = $query3->fetch()) {
	
	
	

?>
<script>
List.push('<?=$row['title'];?>');
</script>
<?php
}
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
                        placeholder="제목, 배우를 검색해주세요"
                         onfocus="this.placeholder=''" 
                         onblur="this.placeholder='제목, 배우를 검색해주세요'"
                        size="70" required="required"/>
                        <input class="search_Img" name="button" type="image" src="img/search_img.png" />
                    </form>
            </div>
        </div>
        <!-- search_modal END -->
        
        <!-- category -->
        <div class="category_container">
                <!-- <div style="margin:10px 0 0 430px;">
<?php

while ($row = $query3->fetch()) {
?>
                    <img onclick="location.href='movie.php?platform=<?=$row['provider_id']?>&search=<?=$search?>&bid=<?=$bid?>&sort_by=<?=$sort_by?>';" class="where_logo"src="img/<?=$row['logo_path']?>">
<?php
}
?>
<img onclick="location.href='movie.php?bid='" class="where_logo"src="img/all.png">
                </div> -->
                <div class="category_wrap">
                    <ul>
                        <?php
                        for($i = 0; $i < 4; $i++){
                            // 클릭된 플랫폼 표시
                            $clicked_provider = $providers_id[$i] == $platform ? "1" : "0.5";
                            // 클릭된 플랫폼 다시 클릭 시 해제되도록
                            $already_click = $providers_id[$i] == $platform ? "": $providers_id[$i]; 
                        ?>
                        <li class="category_li"><a style="opacity: <?=$clicked_provider?>"
                            onclick="location.href='movie.php?platform=<?=$already_click?>&search=<?=$search?>&bid=<?=$bid?>&sort_by=<?=$sort_by?>';"><?=$providers_name[$i]?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="category_right_wrap">
                    <p id="carta">카테고리<p>
                    <p><a style="opacity: <?= $sort_by=="popularity" ? '1' : '0.5'?>;"href="movie.php?sort_by=popularity&bid=<?=$bid?>&search=<?=$search?>&platform=<?=is_array($platform) ? : $platform?>">인기순</a></p>
                    <p id="ll">ㅣ</p>
                    <p><a style="opacity: <?= $sort_by !="popularity" ? '1' : '0.5'?>;" href="movie.php?sort_by=release_date&bid=<?=$bid?>&search=<?=$search?>&platform=<?=is_array($platform) ? : $platform?>">최신순</a></p>
                </div>
        </div>

                
                <div id="myDIV">
                    <ul>
                        <a href="movie.php?bid=&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid=="")echo $styles; ?>;">전체</li></a>       
                        <a href="movie.php?bid=14&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==14) echo $styles; ?>">모험</li></a>       
                        <a href="movie.php?bid=16&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==16) echo $styles; ?>">애니메이션</li></a> 
                        <a href="movie.php?bid=18&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==18) echo $styles; ?>">드라마</li></a>     
                        <a href="movie.php?bid=27&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==27) echo $styles; ?>">공포</li></a>       
                        
                    </ul>
                    <ul>
                       <a href="movie.php?bid=28&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==28) echo $styles; ?>">액션</li></a>
                       <a href="movie.php?bid=35&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==35) echo $styles; ?>">코미디</li></a> 
                       <a href="movie.php?bid=36&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==36) echo $styles; ?>">역사</li></a>
                       <a href="movie.php?bid=37&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==37) echo $styles; ?>">서부</li></a>
                       <a href="movie.php?bid=53&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==53) echo $styles; ?>">스릴러</li></a>
                    </ul>
                    <ul>
                      <a href="movie.php?bid=80&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>">   <li style="<?php if($bid==80) echo $styles; ?>">범죄      </li></a> 
                      <a href="movie.php?bid=99&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>">   <li style="<?php if($bid==99) echo $styles; ?>">다큐멘터리</li></a> 
                      <a href="movie.php?bid=878&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>">  <li style="<?php if($bid==878) echo $styles; ?>">SF        </li></a>
                      <a href="movie.php?bid=9648&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"> <li style="<?php if($bid==9648) echo $styles; ?>">미스터리  </li></a>
                      <a href="movie.php?bid=10402&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10402) echo $styles; ?>">음악      </li></a>
                    </ul>
                    <ul>
                    <a href="movie.php?bid=10749&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10749) echo $styles; ?>">로맨스    </li></a>
                    <a href="movie.php?bid=10751&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10751) echo $styles; ?>">가족      </li></a> 
                    <a href="movie.php?bid=10752&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10752) echo $styles; ?>">전쟁      </li></a>
                    <a href="movie.php?bid=10759&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10759) echo $styles; ?>">액션&어드벤쳐</li></a>
                    <a href="movie.php?bid=10762&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10762) echo $styles; ?>">키즈      </li></a>
                    </ul>
                    <ul>
                    <a href="movie.php?bid=10763&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10763) echo $styles; ?>">뉴스</li></a> 
                    <a href="movie.php?bid=10764&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10764) echo $styles; ?>">리얼리티</li></a> 
                    <a href="movie.php?bid=10765&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10765) echo $styles; ?>">공상과학&판타지</li></a>
                    <a href="movie.php?bid=10766&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10766) echo $styles; ?>">연속극</li></a>
                    <a href="movie.php?bid=10767&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10767) echo $styles; ?>">토크</li></a>
                    </ul>
                    <ul>
                        <a href="movie.php?bid=10768&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10768) echo $styles; ?>">시사 </li></a>
                        <a href="movie.php?bid=10770&search=<?=$search?>&platform=<?=$platform?>&sort_by=<?=$sort_by?>"><li style="<?php if($bid==10770) echo $styles; ?>">TV 영화 </li></a>

                    </ul>

                </div>

        <div id='wrap' class="main_container">
            <div class="movie_all">
                <p class="movietext"> 영화ㅣ 전체 </p>
                
            </div>
        </div>
        <!-- wrap END -->
        
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
<script type="text/javascript" src="./js/movie_tv.js"></script>
<script type="text/javascript" src="./js/header.js"></script>

<script>

</script>