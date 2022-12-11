<?php
require("db_connect.php");
// 선택한 컨텐츠 id param 가져오기
$choice_id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
// 영화인지 드라마인지 (movie or tv)
$choice_content = isset($_REQUEST["choice"]) ? $_REQUEST["choice"] : "";
$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// $similar_array = 
// 비슷한 컨텐츠 랜덤페이지
$similar_random_page = rand(1,500);

$data = array( 
    // 데이터 가져오기
    array(
        'api_key' => $api_key,
        'watch_region' => 'KR',
        'language' => 'ko',
        // 'page' => 1
    ),
   // 비슷한 컨텐츠
    // array(
    // 'api_key' => $api_key,
    // 'language'=> 'ko',
    // 'page' => $similar_random_page,
    // 'watch_region' => 'KR',
    // 'with_watch_providers' => '8|337|97|356',
    // ),
    // 비슷한 컨텐츠
    array(
    'api_key' => $api_key,
    'language'=> 'ko',
    'page' => $similar_random_page,
    'watch_region' => 'KR',
    'with_watch_providers' => '8|337|97|356',
    ),
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
    // 선택한 컨텐츠 정보
    $base_url . "/$choice_content/$choice_id?" . http_build_query($data[0], '', ),
    // 선택한 컨텐츠 provider
    $base_url . "/$choice_content/$choice_id/watch/providers?" . http_build_query($data[2], '', ),
    // 
    $base_url . "/$choice_content/$choice_id/credits?" . http_build_query($data[2], '', ),

    // 비슷한 컨텐츠
    $base_url . "/$choice_content/$choice_id/similar?" . http_build_query($data[1], '', ),
    
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[2], '', )
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

// 크롤링 (플랫폼 클릭 시 해당 페이지로 이동)
// include('./simple_html_dom/simple_html_dom.php'); 
//         //가져올 url 설정
        
//     $open_ott_page = [];

//     if (isset($sResponse[1]['results']['KR']['link'])) {
//         $platform_url = $sResponse[1]['results']['KR']['link']; 
//         $platform_html = @file_get_html($platform_url); 

//         unset($arr_result); 
//         $arr_result = $platform_html->find('#ott_offers_window > section > div.header_poster_wrapper > div > div:nth-child(4) > div > ul > li.ott_filter_best_price > div > a');   //1위 ~ 3위 랭킹순위 및 프로그램명 가져오기
//         if(count($arr_result) > 0){                         //위의 이미지에서 a 태그에 포함되는 html dom 객체를 가져옴
//             foreach($arr_result as $e){

//                 //children 속성을 이용해 맨 처음(0)의 태그 가져오기(<span class="rank_num">1</span>값 가져옴)
//                 array_push($open_ott_page, [$e->href, $e->children(0)->src]);     //위의 값 중 1 값을 가져옴
//             } 
// } 
// }

$content = $sResponse[0];

// 제목 가져오는거 컨텐츠에 따라 결정
$title_change = $choice_content == 'movie' ? 'title' : 'name';
$original_title_change = $choice_content == 'movie' ? 'original_title' : 'original_name';
        
// 감독 가져오기 (영화면 credits에서 director가져와야 함)
// created_by
$director = [];
if ($choice_content == 'movie') {
    $count = 0;
    foreach($sResponse[2]['crew'] as $crew){
        if (strstr($crew['job'], 'Director')) {
            array_push($director, [$crew['name']." - ".$crew['job']]);
            $count++;
        };
    
    if($count > 4){
        array_push($director, ['...']);
        break;
    };
    }
} else {
    foreach($sResponse[0]['created_by'] as $create) {
        array_push($director, [$create['name']]);
    }
}

// 배우 가져오기 (credits에서 actor)
$actors = [];
$actors_count = 0;
$actors_all_count = count($sResponse[2]['cast']);
foreach($sResponse[2]['cast'] as $cast){
        array_push($actors, [$cast['name']." - <br>".$cast['character']]);
        $actors_count++;

        if($actors_count > 6){
            
            array_push($actors, ['...']);
            break;
        };
}

// 개봉일 추출 (영화는 $content['release_date']), Tv는 $content['last_air_date']
$open_date = $choice_content == 'movie' ? $content['release_date'] : $content['last_air_date'];



// 페이지네이션

$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
// 현재 페이지 0보다 작으면 1로 설정
$page = 0 < $page ? $page : 1;

$page_count = 3;
$page_lines = 2;
$numRecords = $db->query("select count(*) from  review  where choice_content='$choice_content' and content_id = $choice_id")->fetchColumn();
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

// $numLines= 2;
$stat = $page >= 1 ? ($page -1) * $page_count : $page * $page_count;


// $numLines= 2;
// $numLinke= 3;

// // $page = empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
// // $stat = ($page -1) * $numLines;

// // $count=0;
// // $good_img="good.png";
// // $query3 = $db->query("select *,DATE(review_date)as review_date  from review AS rv ,user AS us  WHERE rv.member_num=us.member_num and rv.content_id = $choice_id and rv.choice_content = '$choice_content' order by $pougodd desc LIMIT $stat , $numLines");

// $firstLink = floor(($page - 1)/$numLinke)*$numLinke+1;
// $lastLink = $firstLink + $numLinke -1;

// $numRecords = $db->query("select count(*) from  review  where choice_content='$choice_content' and content_id = $choice_id")->fetchColumn();
// $numPage = ceil($numRecords / $numLines);

// if($lastLink  >$numPage){
//    $lastLink = $numPage;
// }//올림은 ceil


?>


<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">
    
    <link rel="stylesheet" type="text/css" href="css/choice.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" type="text/css" href="css/poster_hover.css">
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<script>
List = [];
</script>

<div>
<?php
	
	session_start();
	$_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
	$_SESSION["userNum"] = empty($_SESSION["userNum"]) ? "" : $_SESSION["userNum"];
    $_SESSION["userName"] = empty($_SESSION["userName"]) ? "" : $_SESSION["userName"];
  /*
  조회한 다수의 SELECT 문을 하나로 합치고싶을때 유니온(UNION) 을 사용 할 수 있습니다.
  UNION 은 결과를 합칠때 중복되는 행은 하나만 표시해줍니다.
  UNION ALL 은 중복제거를 하지 않고 모두 합쳐서 보여줍니다.
  */

	// while ($row = $query3->fetch()) {
        // $row['title'];
?>
    <script>
    //   List.push(''); // 제목 리스트에 넣기
    </script>
<?php
// }

// 북마크 (로그인인지, 북마크 체크되어 있는지)

$bookmark_link = '';
$bookmark_checked_class = '';
if($_SESSION["userId"]!=""){
    $bookmark=$db->query("select count(*) from  bookmark  where member_num='$_SESSION[userNum]' and choice_num = $choice_id and choice ='$choice_content'")->fetchColumn();

    $bookmark_link = 'join_bookmark.php?id='.$choice_id.'&choice='.$choice_content;
    // '&poster='.$content['poster_path'].
    // '&overview='.$content['overview'];

    if($bookmark>0){
        $bookmark_checked_class = 'checked_bm';
    } else {
        $bookmark_checked_class = 'unchecked_bm'; 
    }
} else {
    $bookmark_link = 'login.php';
    $bookmark_checked_class = 'unchecked_bm'; 
}
?>

</div>
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
    <?php

	$choice = $_GET['choice']; // 선택한 오브젝트 가져오기
	if($choice=='tv'){   // 카테고리에 따라서 GNB 색상 변경
		$dra= "#3482EA";
		$mo="black";
	}else if($choice=='movie'){
		$dra= "#black";
		$mo="#3482EA";
	}
?>
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
            <div class="choice_img_div"><!--이미지-->
                    <!-- 인기 영화 메인화면에 출력 -->
                    <img class="choice_img" 
                    src="<?=$tmdb_img_base_url.$sResponse[0]['backdrop_path']?>"
                    alt="poster"
                    onerror="this.src='<?=$tmdb_img_base_url.$sResponse[0]['poster_path']?>'"
                    >
                <!-- <div class="choice_img_gradient_top"></div> -->
                <div class="choice_img_gradient_bottom"></div>
            </div>
            <!--choice_img_div END-->


            
            <div class="choice_container">
                <div class="choice_wrap_header">
                    <div class="main_title"><?=$content[$title_change]?>(<?=substr($open_date, 0 ,4)?>)</div>
                    <div class="choice_wrap_header_right">
                        <div>TMDB <?=round($content["vote_average"],1)?></div>
                        <div class="bookmark_header <?=$bookmark_checked_class?>">
                            <a href="<?=$bookmark_link?>">
                                <img src="./img/bookmark_icon.png" alt="bookmark_icon"/>북마크
                            </a>
                        </div>
                    </div>
                </div>
                <div class="choice_wrap">
                    <div class="choice_wrap_left">
                        <div class="choice_poster_wrap"><img src="<?=$tmdb_img_base_url.$content["poster_path"]?>"/></div>
                                <div class="bookmark <?=$bookmark_checked_class?>">
                                    <a href="<?=$bookmark_link?>">
                                    <img src="./img/bookmark_icon.png" alt="bookmark_icon"/>북마크
                                    </a>
                                </div>
                        <!-- <div class="providers_link">
                            <?php
                            // foreach($open_ott_page as $ott_url) {
                            ?>
                                <span><a href="<=$ott_url[0]?>" target='_blank'>
                                    <img width="50" height="50" src="<=$tmdb_img_base_url.$ott_url[1]?>"/>
                                </a></span>
                            <?php
                            // }
                            ?>
                        </div> -->
                    </div>
                    <!-- choice_left END -->
                    <div class="choice_wrap_center">
                        <ul class="center_main_ul">
                            <li><div><?=$content[$original_title_change]?></div></li>
                            <li><div>상세정보</div></li>
                            <ul>
                                <li><div>감독 : 
                                    <span class="crew_cast">
                                        <?php
                                        foreach($director as $dir){
                                        ?>
                                        <span>
                                            <?=$dir[0]?>
                                        </span>
                                        <?php
                                        }
                                        ?>
                                    <span>
                                </div></li>
                                <li><div>언어 : <?=$content['original_language']?></div></li>
                                <li><div>개봉일 : <?=$open_date?></div></li>
                                <li><div>장르 : 
                                    <span class="genres">
                                        <?php
                                        foreach($content['genres'] as $genre){
                                        ?>
                                        <span>
                                            <?=$genre['name']?>
                                        </span>
                                        <?php
                                        }
                                        ?>
                                    </span>
                                </div></li>
                            </ul>
                            <li><div>시놉시스</div></li>
                            <ul>
                                <li><div><?=$content['overview']?></div></li>
                            </ul>
                        </ul>
                    </div>
                    <!-- choice_center END -->
                    <div class="choice_wrap_right">
                        <div>배우<span>(<?=$actors_all_count?>)</span></div>
                        <div> 
                            <span class="crew_cast">
                                <?php
                                foreach($actors as $act){
                                ?>
                                <span>
                                    <?=$act[0]?>
                                </span>
                                <?php
                                }
                                ?>
                            <span>
                        </div>
                    </div>
                    <!-- choice_right END -->
                </div>
                <!-- choice_wrap END -->
<?php
//현재 열려있는 컨텐츠 별점 평균
$star_avg = '';
$star_query = $db->query("select truncate(avg(star_rating),1) as star_avg from review where choice_content='$choice_content' and content_id=$choice_id ");
while ($row = $star_query->fetch()) {
$star_avg = $row['star_avg'];
}

$default_text = null;
$review_readonly = null;
$review_action = 'login.php';
$review_button = '로그인';

$my_review_num = 0;
$my_review_date = null;
$my_review_content = null;
$my_star_rating = 0;
$my_review_like = null;
$review_readonly = null;

$review_state = '0';

if ($_SESSION["userNum"]=="") {
    // 로그아웃 상태일 때
    $default_text = '작품에 대한 리뷰를 남기려면 로그인이 필요합니다.';
    $review_readonly = 'readonly';
} else {
    // 로그인 상태일 때
    // $db->query($overlap_check)->fetchColumn()
    $review_state = $db->query("select count(*) from review where choice_content='$choice_content' and content_id=$choice_id and member_num = '$_SESSION[userNum]'")->fetchColumn();
    // 작성한 리뷰가 있다면
    if ($review_state != '0') {
            // 내가 작성한 리뷰 가져오기
            $my_review_query = $db->query("select * from review where choice_content='$choice_content' and content_id=$choice_id and member_num = '$_SESSION[userNum]'");
            while ($row = $my_review_query->fetch()) {
                $my_review_num = $row['review_num'];
                $my_review_date = $row['review_date'];
                $my_review_content = $row['review_content'];
                $my_star_rating = $row['star_rating'];
                $my_review_like = $db->query("select count(*) from review_like where like_review_num = '$row[like_num]'")->fetchColumn();
   
            }
            // 수정하기 
                $review_action = 'review_star.php?choice='.$choice_content.'&id='.$choice_id.'';
                $review_button = '수정하기';

    } else {

        $review_button = '작성하기';
        $review_action = 'review_star.php?choice='.$choice_content.'&id='.$choice_id.'';
        $default_text = '별점과 리뷰를 입력해주세요.';
    }
}
?>
                <div class="review_container">
                    <div class="review_title">리뷰</div>
                    <div class="review_wrap">
                        <div class="review_header">
                            <span class="star">
                                ☆☆☆☆☆
                                <div style="width:<?=$my_star_rating*12?>px" id="box">★★★★★</div>
                                <input id="star_value" type="range" oninput="drawStar(this)" value="<?=$my_star_rating?>" step="1" min="0" max="10" >
                            </span>
                            <span class="like">
                                <span class="<?=$review_state != '0'? '': 'like_display'?>">♥</span><?=$my_review_like?>
                            </span>
                        </div>
                        <form class="" action="<?=$review_action?>" method="post">
                            <input id="starvalue" name="starvalue" type="hidden" value="" />
                            <input name="id" type="hidden" value="<?=$choice_id?>">
                            <input name="choice" type="hidden" value="<?=$choice_content?>">

                            <textarea id="text_crear" class="text_review" name="textreview" 
                            value=""
                            placeholder="<?=$default_text?>"
                            onfocus="this.placeholder=''" 
                            onblur="this.placeholder='<?=$default_text?>'"
                            <?=$review_readonly?>
                            ><?=$my_review_content?></textarea>

                            <div class="review_buttons">
                                <button type="submit" class="review_button"><?=$review_button?></button>
                            <?php
                            if ($_SESSION["userNum"]!=" ") {
                                if ($review_state != '0') {
                                ?>
                                <!-- <input id="A" type="button" class="noi" value="취소"/> -->
                                <button type="button" onclick="location.href='revew_del.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&review_num=<?=$my_review_num?>'" class="review_button">삭제</button>
                                
                                <?php
                                } else {
                                ?>
                                <!-- <button type="button" onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>" class="review_button">취소</button> -->
                                
                                <?php
                                }
                            }
                                ?>
                            </div>

                         </form>
                    </div>
                </div>
                <!-- review_container END -->


<?php 
$review_count = $db->query("select count(*) from review where content_id=$choice_id")->fetchColumn();  
$pougodd=empty($_REQUEST["pougodd"]) ? "like_num" : $_REQUEST["pougodd"];
$pougodd_desc = $pougodd ==  "like_num" ? "" : "desc";
?>

                <div class="other_review_container">
                    <div class="other_review_header">
                        <div class="other_review_title">전체 리뷰<t>(<?=$review_count?>)</t></div>
                        
                        <?php
                        if ($page_total != 0) {
                        ?>
                        <!-- 정렬 버튼 -->
                        <div class="other_review_sort_buttons">
                            <div style="color: <?=$pougodd=="like_num" ? '#83a1dd': 'white'?>" id="pop"onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&pougodd=like_num';">인기순 </div>
                            <div style="color: <?=$pougodd=="review_date" ? '#83a1dd': 'white'?>" id="new"onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&pougodd=review_date';">최신순</div>
                        </div>

                        <div class="other_review_pagination">
                            <ul>
                                <li>
                                <button 
                                class="other_review_pagination_button"
                                type="button"
                                onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&pougodd=<?=$pougodd?>&page=<?=$page_prev?>'">
                                <
                                </button></li>
            <?php
                            for($i = $page_start; $i <= $page_last; $i++){
            ?>
                                <li><button
                                class="other_review_pagination_button <?=$i == $page ? 'review_now_page' : 'review_other_page'?>"
                                type="button"
                                onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&pougodd=<?=$pougodd?>&page=<?=$i?>'">
                                <?=$i?></button></li>
            <?php
                            }
            ?>
                                <li><button 
                                class="other_review_pagination_button"
                                type="button"
                                onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>&pougodd=<?=$pougodd?>&page=<?=$page_next?>'">
                                >
                                </button>
                                </li>
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
                        $limit_start = $page_start-1;
                        $other_review_query = $db->query("select * from review where choice_content='$choice_content' and content_id=$choice_id order by $pougodd $pougodd_desc, review_num $pougodd_desc LIMIT $stat, $page_count");
                        while ($row = $other_review_query->fetch()) {
                            $user_nickname = $db->query("select nickname from user where member_num = '$row[member_num]'")->fetchColumn();
                            $user_review_like = $db->query("select count(*) from review_like where like_review_num = '$row[like_num]'")->fetchColumn();
                            // 좋아요 클릭 여부
                            $click_like_state = $db->query("select count(*) from review_like where like_review_num='$row[like_num]'and like_user_num='$_SESSION[userNum]'")->fetchColumn();
                            $user_content = str_replace(" ", "&nbsp;", $row['review_content']);
                            $user_content = str_replace("\n", "<br>", $user_content);
                    ?>
                        <div class="other_review_wrap">
                            <!-- 닉네임 -->
                            <div class="other_review_left">
                                <?=$user_nickname?>
                                <?= $_SESSION["userName"] == $user_nickname ? '(내 리뷰)': ''?>
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
                                <a href="good.php?like_num=<?=$row['like_num']?>">
                                    <span><?=$click_like_state != '0' ? '♥':'♡'?></span><?=$user_review_like?>
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
                        <div class="similar_genres_title">비슷한 콘텐츠</div>
                        <div class="remote_wrapper">
                            <div class="move_buttons_remote">
                                <div class="prev_remote" onclick='move("left")'><</div>  
                                <div class="next_remote" onclick='move("right")'>></div>  
                            </div>
                        </div>
                    </div>
                    <div class="similar_genres_wrap">
                                   
                        <?php

                        $list_count = 3;   
                        // $title_change = '';

                        ?>
                                    <!-- <div class="main_content_view"> -->

                                        <!--드라마 좌우이동-->
                                        <div class="move_buttons">
                                            <div class="left" onclick='move("left")'><</div>  
                                            <div class="right" onclick='move("right")'>></div>  
                                        </div>
                                        <div class="show"><!--리스트 보여지는 틀-->
                                            <div class="main_slide_lists"><!--리스트 이미지 출력-->
                        <?php
                                        
                            // 짝수로 가져온 컨텐츠면 드라마 >> 제목 가져올 때 title
                            // 홀수면 영화 >> 제목 가져올 때 name 
                            // $sResponse[$list_count]['results'][$i][$title_change]
                            // if ($choice_content == 'tv') {
                            //     $title_change = 'name';
                            // } else {
                            //     $title_change = 'title';
                            // }
                            for ($i = 0; $i < count($sResponse[3]['results']); $i++) {

                                // 시나리오 글자수 제한
                                $poster_overview = $sResponse[$list_count]['results'][$i]['overview'];
                                
                                if (strlen($poster_overview) >= 330) {
                                    $poster_overview = iconv_substr($poster_overview,0,140,"utf-8").'...';
                                }
                            ?>

                                                <div class="main_poster_img_container poster_container" onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$sResponse[$list_count]['results'][$i]['id']?>';">
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
                                      <!--main_content_view-->

                    </div>
                </div>
            </div>
            <!-- choice_container END -->
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
        const drawStar = (target) => {
            if ('<?=$review_readonly?>' != 'readonly') {
            document.querySelector(`.star div`).style.width = `${target.value*12}px`;

            let element = document.getElementById('box');
            let element2 = element.offsetWidth;

            document.getElementById('starvalue').value = element2;


            document.getElementById('starvalue').value = element2;
            }
        }


// 마우스 오버시 좌우 이동 보이게
let show = document.querySelector('.show');
let move_buttons = document.querySelector('.move_buttons');
let move_buttons_remote = document.querySelector('.move_buttons_remote');
let left_btn = document.querySelector('.left');
let right_btn = document.querySelector('.right');

    show.addEventListener("mouseover", function () {
        move_buttons.style.opacity = "100";
    }, false);


    move_buttons.addEventListener("mouseover", function () {
        move_buttons.style.opacity = "100";
    }, false);


    show.addEventListener("mouseout", function(){
        move_buttons.style.opacity = "0";
    }, false);


// 리스트 화면 이동 기능
function move(type) {
    let tab = document.querySelector('.main_slide_lists');
    var marginLeft = window.getComputedStyle(tab).getPropertyValue('margin-left');
    let poster_wrap = document.querySelector('.poster_wrap');

    let slide_count = 0;
    marginLeft = parseInt(marginLeft);

    move_buttons.style.pointerEvents = 'none';
    move_buttons_remote.style.pointerEvents = 'none';
    if (type === 'right' && -marginLeft < tab.scrollWidth) {
        
        var a = marginLeft - windowWidth;
        a = a > -tab.scrollWidth ? a : marginLeft;
        console.log('a : ' + a);
        tab.style.marginLeft = a + 'px';  // 마진값 변경하여 좌 우 이동
        tab.style.transition = `${0.4}s ease-out`;    // 이동 시 딜레이 주어 부드럽게
        
    } 
    if (type === 'left' && -marginLeft > 0) {
        
        // marginLeft = marginLeft > 0 ? 0 : marginLeft;
        var a = marginLeft + windowWidth;
        a = a > 0 ? 0 : a;
        console.log('a : ' + a);
        tab.style.marginLeft = a + 'px';
        tab.style.transition = `${0.4}s ease-out`;
        
    } else {
        
    }
    // 중복 클릭 방지
    setTimeout(() => { 
        move_buttons.style.pointerEvents = 'auto';
        move_buttons_remote.style.pointerEvents = 'auto';
    }, 400);
}
</script>