<?php

    require("db_connect.php");
    session_start();
    $_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
    
    // $query3 = $db->query("SELECT title FROM tv UNION SELECT title  FROM movie "); 
    // while ($row = $query3->fetch()) {
        // <?=$row['title'];
?>
<script> // 영화 제목 리스트 추가 (자동완성 리스트)

    List = [];  // 배열 생성

    // List.push('');
    
</script>
<?php
// }


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
$today = date("Y-m-d");
// 영화 데이터
$data = array( 
    // 영화
    // 최신순
    array(
        'api_key' => $api_key,
        // 'with_watch_providers' => '8|337|97|356',
        'sort_by' => 'release_date.desc',
        'watch_region' => 'KR',
        'language' => 'ko',
        'page' => 1,
        'release_date.lte' => $today
    ),
    // 인기순
    array(
        'api_key' => $api_key,
        // 'with_watch_providers' => '8|337|97|356',
        'sort_by' => 'popularity.desc',
        'watch_region' => 'KR',
        'language' => 'ko',
        'page' => 1,
        'release_date.lte' => $today
        
    ),
    // 드라마
        // 최신순
        array(
            'api_key' => $api_key,
            // 'with_watch_providers' => '8|337|97|356',
            'sort_by' => 'first_air_date.desc',
            'watch_region' => 'KR',
            'language' => 'ko',
            'page' => 1,
            'air_date.lte' => $today,
            'first_air_date.lte' => $today,
            'include_null_first_air_dates' => false,
        ),
        // 인기순
        array(
            'api_key' => $api_key,
            // 'with_watch_providers' => '8|337|97|356',
            'sort_by' => 'popularity.desc',
            'watch_region' => 'KR',
            'language' => 'ko',
            'page' => 1,
            'air_date.lte' => $today,
            'first_air_date.lte' => $today,
            'include_null_first_air_dates' => false,
        ),
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
    $base_url . "/discover/movie?" . http_build_query($data[0], '', ),
    $base_url . "/discover/tv?" . http_build_query($data[2], '', ),
    // 인기순
    $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    $base_url . "/discover/tv?" . http_build_query($data[3], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[4], '', )
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

// 시나리오
$overview = $sResponse[2]['results'][0]['overview'];
// 공백문자, 줄바꿈 치환
$overview = str_replace(" ", "&nbsp;", $overview); //공백
$overview = str_replace("\n", "<br>", $overview); //줄바꿈

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
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">

    <!--css 링크-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/poster_hover.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!--자동완성 기능 autocomplete-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>

<!--검색-->
<script>
    $(function() {
        $("#searchInput").autocomplete({
            source: List,   // 자동완성 대상
            focus: function(event, ui) { //포커스 시 이벤트
                return false;
            },
            minLength: 1,   // 최소 글자 수
            delay: 100,     //글자 입력 후 이벤트 발생까지 지연 시간
        });
    });
</script>
<body>
    <div class="all"> <!--전체 너비 설정-->
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
    $query3 = $db->query("select * from user where identification='$_SESSION[userId]'"); 
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
                    <form class="search" action="search_cookie.php" method="get">
                        <input id="searchInput" type="text" name="search" 
                        placeholder="찾으시려는 드라마 또는 영화 제목을 입력해 주세요."
                         onfocus="this.placeholder=''" 
                         onblur="this.placeholder='찾으시려는 드라마 또는 영화 제목을 입력해 주세요.'"
                        size="70" required="required"/>
                        <input class="search_Img" name="button" type="image" src="img/search_img.png" />
                    </form>
                    <div class="search_cookie_wrap">
                        <?php
                        if (isset($_COOKIE['search_cookie'])) {
                        ?>
                            <?php
                            foreach($_COOKIE['search_cookie'] as $name => $value) {
                            ?>
                                <div class="search_cookie_box">
                                    <div><a href="search_cookie.php?search=<?=$name?>"><?=$name?></a></div>
                                    <div class="cookie_delete">
                                        <a href="search_cookie.php?cookie_del=<?=$name?>"><img src="./img/close_icon_white.png" alt="X"/></a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </div>
            </div>
        </div>
        <!-- search_modal END -->

        <div id='wrap'>
            <div class="main_img_div"><!--메인 이미지-->
                <!-- 인기 영화 메인화면에 출력 -->
                <img class="main_img" src="<?=$tmdb_img_base_url.$sResponse[2]['results'][0]['backdrop_path']?>">
                <div style="width : 100%;" class="main_text">
                    <span class="main_title"><?=$sResponse[2]['results'][0]['title']?></span>
                    <p class="main_scenario"><?=$overview?></p>
                    <a href="choice.php?choice=movie&id=<?=$sResponse[2]['results'][0]['id']?>"class="main_view_detail">상세 보기</a>
                </div>

            <div class="main_img_gradient_top"></div>
            <div class="main_img_gradient_bottom"></div>
            </div><!--main_img_div END-->
           
<?php
$list_arr = [["영화 최신 순", "movie_release_list", "movie"],
            ["드라마 최신 순", "drama_release_list", "tv"],
            ["영화 인기 순", "movie_popularity_list", "movie"],
            ["드라마 인기 순","drama_popularity_list", "tv"]];

$list_count = 0;   
$title_change = '';

foreach($list_arr as $main_lists){
?>
            <div class="main_content_view">
                <!--드라마 리스트-->
                <p class="main_slide_title"><?=$main_lists[0]?></p>
                <span class="remote_wrapper">
                    <p class="more_view" onclick="location.href='movie.php?bid=';">ALL</p>
                    <div class="move_buttons_remote">
                        <div class="prev_remote" onclick='move("left","<?=$main_lists[1]?>")'><</div>  
                        <div class="next_remote" onclick='move("right","<?=$main_lists[1]?>")'>></div>  
                    </div>
                </span>
                <!--드라마 좌우이동-->
                <div class="move_buttons">
                    <div class="left" onclick='move("left","<?=$main_lists[1]?>")'><</div>  
                    <div class="right" onclick='move("right","<?=$main_lists[1]?>")'>></div>  
                </div>
                <div class="show"><!--리스트 보여지는 틀-->
                    <div class="main_slide_lists"><!--리스트 이미지 출력-->
<?php
                   
    // 짝수로 가져온 컨텐츠면 드라마 >> 제목 가져올 때 title
    // 홀수면 영화 >> 제목 가져올 때 name 
    // $sResponse[$list_count]['results'][$i][$title_change]
    if ($list_count % 2 == 0) {
        $title_change = 'title';
        echo '<script>console.log("name")</script>';
    } else {
        $title_change = 'name';
    }
    for ($i = 0; $i < count($sResponse[0]['results']); $i++) {

        // 시나리오 글자수 제한
        $poster_overview = $sResponse[$list_count]['results'][$i]['overview'];
        
        if (strlen($poster_overview) >= 330) {
            $poster_overview = iconv_substr($poster_overview,0,140,"utf-8").'...';
        }
    ?>

                        <div class="main_poster_img_container poster_container" onclick="location.href='choice.php?choice=<?=$main_lists[2]?>&id=<?=$sResponse[$list_count]['results'][$i]['id']?>';">
                            <div class="poster_hover_wrapper">
                                <p class="main_poster_title"><?=$sResponse[$list_count]['results'][$i][$title_change]?></p>
                                <p class="main_poster_overview"><?=$poster_overview?></p>
                            </div>
                            <span class="poster_wrap">
                            <img class="main_poster_img" 
                                src="<?=$tmdb_img_base_url.$sResponse[$list_count]['results'][$i]['poster_path']?>" 
                                alt=""
                                onerror="this.parentNode.parentNode.style.display='none';">
                            </span>
                        </div>
    <?php
        }
        ?>

<?php
         
                    $list_count++;
?>
                    </div>
                </div> <!--show END-->
            </div>  <!--main_content_view-->

            <?php
            }
            ?>
        
            </div><!--wrap END-->
        </div><!--all END-->

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
<script>

    // 마우스 오버시 좌우 이동 보이게
    let show = document.querySelectorAll('.show');
    let move_buttons = document.querySelectorAll('.move_buttons');
    let move_buttons_remote = document.querySelectorAll('.move_buttons_remote');
    let left_btn = document.querySelectorAll('.left');
    let right_btn = document.querySelectorAll('.right');

    for(let i = 0; i < show.length; i++){
        show[i].addEventListener("mouseover", function () {
            move_buttons[i].style.opacity = "100";
        }, false);
    

        move_buttons[i].addEventListener("mouseover", function () {
            move_buttons[i].style.opacity = "100";
        }, false);
    

        show[i].addEventListener("mouseout", function(){
            move_buttons[i].style.opacity = "0";
        }, false);
    }

    // 리스트 화면 이동 기능
    function move(type, check) {
        if (check == 'movie_release_list') {
            var check = 0;
        } else if (check == 'drama_release_list') {
            var check = 1;
        } else if (check == 'movie_popularity_list') {
            var check = 2;
        } else if (check == 'drama_popularity_list') {
            var check = 3;
        }
        let tab = document.querySelectorAll('.main_slide_lists');
        var marginLeft = window.getComputedStyle(tab[check]).getPropertyValue('margin-left');
        let poster_wrap = document.querySelectorAll('.poster_wrap');

        let slide_count = 0;
        marginLeft = parseInt(marginLeft);

        move_buttons[check].style.pointerEvents = 'none';
        move_buttons_remote[check].style.pointerEvents = 'none';
        if (type === 'right' && -marginLeft < tab[check].scrollWidth) {
            
            var a = marginLeft - windowWidth;
            a = a > -tab[check].scrollWidth ? a : marginLeft;
            console.log('a : ' + a);
            tab[check].style.marginLeft = a + 'px';  // 마진값 변경하여 좌 우 이동
            tab[check].style.transition = `${0.4}s ease-out`;    // 이동 시 딜레이 주어 부드럽게
            
        } 
        if (type === 'left' && -marginLeft > 0) {
            
            // marginLeft = marginLeft > 0 ? 0 : marginLeft;
            var a = marginLeft + windowWidth;
            a = a > 0 ? 0 : a;
            console.log('a : ' + a);
            tab[check].style.marginLeft = a + 'px';
            tab[check].style.transition = `${0.4}s ease-out`;
            
        } else {
            
        }
        // 중복 클릭 방지
        setTimeout(() => { 
            move_buttons[check].style.pointerEvents = 'auto';
            move_buttons_remote[check].style.pointerEvents = 'auto';
        }, 400);
    }
</script>