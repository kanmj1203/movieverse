<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
$page = 0 < $page ? $page : 1;

$sort_by = isset($_REQUEST["sort_by"]) ? $_REQUEST["sort_by"] : 'popularity.desc';

$platform = empty($_REQUEST["platform"]) ? '' : $_REQUEST["platform"];
$bid = isset($_REQUEST["bid"]) ? $_REQUEST["bid"] : "";

$search_content = isset($_REQUEST["search_content"]) ? $_REQUEST["search_content"] : "movie";
$title_change = $search_content == 'movie' ? 'title' : 'name';

$category_list = ['movie' => '영화' , 'tv' => '드라마'];
$category_id = ['movie' , 'tv'];
$category_name = ['영화', '드라마'];

$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

$search =isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

// 영화 데이터
$data = array( 
    // 최신순
    array(
        'api_key' => $api_key,
        // 'with_watch_providers' => empty($_REQUEST["platform"]) ? '8|337|97|356' : $_REQUEST["platform"],
        // aa($platform),
        // 'sort_by' => $sort_by,
        'region' => 'KR',
        'language' => 'ko',
        // 'with_genres' => $bid,
        'page' => $page,
		 'query' => $search,
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
    // movie or tv 장르 가져오기
    array(
        'api_key' => $api_key,
        'language'=> 'ko'
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
//     'page' => $page
// );

// URL 지정
$base_url = 'https://api.themoviedb.org/3';
$url = array(
    // 최신순
    $base_url . "/search/$search_content?" . http_build_query($data[0], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[0], '', ),
    // 인기순
    // $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 장르
    // $base_url . "/genre/movie/list?" . http_build_query($data[1], '', ),
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
$providers_list = [
    8 => '넷플릭스', 
    337 => '왓챠',
    97 => '디즈니',
    356 => '웨이브'];
$providers_id = [8, 337, 97, 356];
$providers_name = ['넷플릭스', '왓챠', '디즈니', '웨이브'];

// TMDB에서 이미지 가져오기
$tmdb_img_base_url = "https://image.tmdb.org/t/p/original/";

// $query3 = $db->query("select * FROM streaming ");


// 페이지네이션

$page_count = 5;
$page_total_resultes = $sResponse[0]['total_results'];
$page_total = $sResponse[0]['total_pages'];

$page_list = ceil($page / $page_count);

$page_last = $page_list < $page_total ? $page_list * $page_count : $page_total;
$page_last = $page_count > $page_total ? $page_total : $page_last;
$page_start = $page_last - ($page_count - 1) <= 0 ? 1 : $page_last - ($page_count - 1);

$page_prev = $page_start - 1;
$page_next = $page_last + 1;


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
    <link rel="stylesheet" type="text/css" href="css/poster_hover.css">
	<link rel="stylesheet" type="text/css" href="css/search.css">
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
                                        <a href="search_cookie.php?search=<?=$search?>&cookie_del=<?=$name?>"><img src="./img/close_icon_white.png" alt="X"/></a>
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
        
        <!-- category -->
        <div class="category_container">

                <div class="category_wrap">
                    <ul>
                        <?php
                        for($i = 0; $i < 2; $i++){
                            // 클릭된 플랫폼 표시
                            $clicked_category = $category_id[$i] == $search_content ? "1" : "0.5";
                            // 클릭된 플랫폼 다시 클릭 시 해제되도록
                            // $already_click = $category_id[$i] == $search_content ? "": $category_id[$i]; 
                        ?>
                        <li class="category_li"><a style="opacity: <?=$clicked_category?>"
                            onclick="location.href='search_result.php?search_content=<?=$category_id[$i]?>&search=<?=$search?>';"><?=$category_name[$i]?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
        </div>

        <div id='wrap' class="main_container">
            <div class="movie_all">
                <p class="movietext"> 검색 "<?=$search?>"ㅣ <?= isset($category_list[$search_content]) ? $category_list[$search_content] : '전체'?> <span>(<?=$page_total_resultes?>)</span> </p>
                <div class="show"><!--리스트 보여지는 틀-->
                    <div class="main_slide_lists"><!--리스트 이미지 출력-->
<?php
    $pxs=0;
    $list_count = 0;   

    for ($i = 0; $i < count($sResponse[0]['results']); $i++) {

        // 시나리오 글자수 제한
        $poster_overview = $sResponse[$list_count]['results'][$i]['overview'];
        
        if (strlen($poster_overview) >= 330) {
            $poster_overview = iconv_substr($poster_overview,0,140,"utf-8").'...';
        }
    ?>

                        <div class="main_poster_img_container poster_container" onclick="location.href='choice.php?choice=<?=$search_content?>&id=<?=$sResponse[$list_count]['results'][$i]['id']?>';">
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
                    $pxs=24;
                    $list_count++;
?>
                    </div>
                    <!-- main_slide_lists END -->
                </div> 
                <!--show END-->
            </div>
            <!-- movie_all END -->
			<?php
			if ($page_total == 0) {
			?>
			        <div class="img_not_found_wrap">
						<div class="not_found_img">
							<img src="./img/not_found.png" alt="not_found_img">
						</div>
						<div class="not_found_text">
							"<?=$search?>"와 관련된 제목을 찾을 수 없습니다.
						</div>
					</div>       
			<?php
			}
			?>

			<?php
			if ($page_total != 0) {
			?>
            <!-- pagination -->
            <div class="pagination">
                <ul>
                    <li>
                    <button 
                    class="pagination_button"
                    type="button"
                    onclick="location.href='search_result.php?search=<?=$search?>&search_content=<?=$search_content?>&page=<?=$page_prev?>'">
                    <
                    </button></li>
<?php
                for($i = $page_start; $i <= $page_last; $i++){
?>
                    <li><button
                    class="pagination_button <?=$i == $page ? 'now_page' : ''?>"
                    type="button"
                    onclick="location.href='search_result.php?search=<?=$search?>&search_content=<?=$search_content?>&page=<?=$i?>'"
                    >
                    <?=$i?></button></li>
<?php
                }
?>
                    <li><button 
                    class="pagination_button"
                    type="button"
                    onclick="location.href='search_result.php?search=<?=$search?>&search_content=<?=$search_content?>&page=<?=$page_next?>'" >
                    >
                    </button></li>
            </ul>
            </div>
			                        
			<?php
			}
			?>
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