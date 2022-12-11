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

$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// 영화 데이터
$data = array( 
    // 플랫폼 가져오기
    array(
        'api_key' => $api_key
    )
);

// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
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
   

// 내 리뷰 총 개수
$review_count = $db->query("select count(*) from review where member_num='$_SESSION[userNum]'")->fetchColumn(); 
$bookmark_count = $db->query("select count(*) from bookmark where member_num='$_SESSION[userNum]'")->fetchColumn();  

// 내 리뷰 전체 가져오기 (최신순)
$review_query = $db->query("select * from review where member_num='$_SESSION[userNum]' order by review_date desc, review_num desc limit 0, 5");  

// 내 북마크 전체 가져오기 (최신순)
$bookmark_query = $db->query("select * from bookmark where member_num='$_SESSION[userNum]' order by join_date desc,  bookmark_num desc limit 0, 4");  

// 내 계정 정보 전체 가져오기
$my_info_query = $db->query("select * from user where member_num='$_SESSION[userNum]'");  


$pougodd=empty($_REQUEST["pougodd"]) ? "like_num" : $_REQUEST["pougodd"];
$pougodd_desc = $pougodd ==  "like_num" ? "" : "desc";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">

    <!--css 링크-->
    <link rel="stylesheet" type="text/css" href="css/user.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/choice.css">
    <link rel="stylesheet" type="text/css" href="css/poster_hover.css">
    <!-- <link rel="stylesheet" type="text/css" href="js/list.css"> -->
    <!-- http://localhost/movieverse/js/list.css  -->
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
        <div id='wrap' class="main_container">
            <!-- wrap -->  
            <div id="mypage_container">
                <?php
                    if($my_info = $my_info_query->fetch()) {
                ?>
                <div class="mypage_info_container">
                    <div class="mypage_info_wrap profile">
                        <div class="my_user_img">
                            <img src="user_img/<?= $iset?>">
                        </div>
                        <div>
                            <form class="profile_img_form" name='tmp_name' method="post" action="img_plus.php" enctype="multipart/form-data">
	 
                                <div class="filebox">
                                <label for="file"></label> 
                                <input type="file" name="imgFile" id="file" class="upload-hidden">
                                <input class="upload-name" value="" placeholder="첨부파일" >
                                </div> 

                                <button class="submit" type="submit">프로필 사진 수정</button>
                            </form>
                        </div>
                    </div>

                    <div class="mypage_info_wrap infos">
                        <div>
                            <div class="nickname"><?=$my_info['nickname']?></div>
                            <div class="my_id"><span>ID : </span><?=$my_info['identification']?></div>
                        </div>
                        <div class="bookmark_and_review_container">
                            <div><p>북마크</p><p><?=$bookmark_count?>개</p></div>
                            <div><p>내가 쓴 리뷰</p><p><?=$review_count?>개</p></div>
                        </div>
                        <div class="change_my_info">
                            <div><a href="user_out.php">회원 탈퇴</a></div>
                            <div><a href="pwre.php">비밀번호 변경</a></div>
                            <div><a href="mynick_update_form.php">닉네임 변경</a></div>
                        </div>
                    </div>
                </div>
                <!-- mypage_info_container END -->
                <?php
                    }
                ?>

                <!-- <div class="mypage_wrap"> -->

                    <div class="other_review_container">
                        <div class="other_review_header">
                            <div class="other_review_title">리뷰 관리</div>
                            
                            <?php
                            if ($review_count != 0) {
                            ?>

                            <div class="mypage_review_more">
                                <ul>
                                    <li>
                                    <button 
                                    class="other_review_pagination_button"
                                    type="button"
                                    onclick="location.href='myreview.php';">
                                    더보기 >
                                    </button></li>
                                </ul>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if ($review_count == '0') {
                            ?>
                            <div class="defind_review">
                                    <div>
                                        <p>작성된 리뷰가 없습니다. 첫 리뷰를 작성해 보세요!</p>
                                    </div>
                                </div>
                            <?php
                        }
                            // $limit_start = $page_start-1;
                            // $other_review_query = $db->query("select * from review where choice_content='$choice_content' and content_id=$choice_id order by $pougodd $pougodd_desc, review_num $pougodd_desc LIMIT $stat, $page_count");
                            while ($row = $review_query->fetch()) {
                                // $review_like_num = $row[like_num];
                                // $user_nickname = $db->query("select nickname from user where member_num = '$row[member_num]'")->fetchColumn();
                                $user_review_like = $db->query("select count(*) from review_like where like_review_num = '$row[like_num]'")->fetchColumn();
                                $review_title = $row['title'];
                                // 좋아요 클릭 여부
                                // $click_like_state = $db->query("select count(*) from review_like where like_review_num='$row[like_num]'and like_user_num='$_SESSION[userNum]'")->fetchColumn();
                                $user_content = str_replace(" ", "&nbsp;", $row['review_content']);
                                $user_content = str_replace("\n", "<br>", $user_content);
                        ?>
                            <div class="other_review_wrap">
                                <!-- 닉네임 -->
                                <div class="other_review_left">
                                    <?=$review_title?>
                                </div>
                                <!-- 별점, 내용 -->
                                <div class="other_review_content">
                                    <span class="other_star">
                                        ☆☆☆☆☆
                                        <span style="width:<?=$row['star_rating']*12?>px" id="box">★★★★★</span>
                                        <!-- <input id="star_value" type="range" oninput="drawStar(this)" value="" step="1" min="0" max="10"> -->
                                    </span>
                                    <div>
                                        <?=$user_content?>
                                    </div>
                                </div>
                                <!-- 작성날짜, 좋아요 -->
                                <div class="other_review_right">
                                    <div>
                                        <?=$row['review_date']?>
                                    </div>
                                    <div class="other_review_like">
                                        <a style="cursor : default;" href="javascript:void(0);">
                                            <span>♡</span><?=$user_review_like?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- other_review_wrap END -->
                        <?php
                            }
                        ?>
                    </div>
                    <!-- other_review_container END -->

            <div class="similar_genres_container">
                <div class="similar_genres_header">
                    <div class="similar_genres_title">북마크</div>
                    <?php
                    if ($bookmark_count != 0) {
                    ?>

                    <div class="mypage_review_more">
                        <ul>
                            <li>
                            <button 
                            class="other_review_pagination_button"
                            type="button"
                            onclick="location.href='mybook.php';">
                            더보기 >
                            </button>
                            </li>
                        </ul>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="mypage_bookmark_wrap"><!--리스트 이미지 출력-->
                <?php
                        if ($bookmark_count == '0') {
                            ?>
                            <div class="defind_review">
                                    <div>
                                        <p>북마크된 콘텐츠가 없습니다. 첫 북마크를 등록해 보세요!</p>
                                    </div>
                            </div>
                            <?php
                        }
                        ?>
<?php 

    while($row=$bookmark_query->fetch()){

        // 시나리오 글자수 제한
        $poster_overview = $row["overview"];
        
        if (strlen($poster_overview) >= 330) {
            $poster_overview = iconv_substr($poster_overview,0,140,"utf-8").'...';
        }
    ?>

                        <div class="main_poster_img_container poster_container" onclick="location.href='choice.php?choice=<?=$row['choice']?>&id=<?=$row['choice_num']?>';">
                            <div class="poster_hover_wrapper">
                                <p class="main_poster_title"><?=$row["title"]?></p>
                                <p class="main_poster_overview"><?=$poster_overview?></p>
                            </div>
                            <span class="poster_wrap">
                            <img class="main_poster_img" 
                                src="<?=$tmdb_img_base_url.$row['poster']?>" 
                                alt=""
                                onerror="this.parentNode.parentNode.style.display='none';">
                            </span>
                        </div>
    <?php
        }
        ?>
                    </div>
                    <!-- main_slide_lists END -->
                </div>
                <!-- similar_genres_container END -->
                </div> 
                <!--show END-->
            </div>
            <!-- mypage_container END -->
    </div>
    <!-- all END -->
        
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


        











