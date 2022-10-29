<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">

    <!--css 링크-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
	<link rel="shortcut icon" href="#">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!--자동완성 기능 autocomplete-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>

<script>
List = [];  // 배열 생성
</script>


<div>   <!--session 생성-->
    <?php 
        require("db_connect.php");
        session_start();
        $_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
        
        $query3 = $db->query("SELECT title FROM tv UNION SELECT title  FROM movie "); 
        while ($row = $query3->fetch()) {
    ?>
    <script> // 영화 제목 리스트 추가 (자동완성 리스트)
        List.push('<?=$row['title'];?>');
    </script>
    <?php
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
    <div class="all"> <!--전체 너비 설정-->
        <header class="header_scroll_top">
            <div class="head">  <!--header GNB-->
                <div class="header_left">
                    <img class="logo" onclick="location.href='index.php'" src="img/logo/logo_txt.png">
                    <!-- <a style="color: #3482EA;" class="home" onclick="location.href='index.php?bid='"> 홈 </a> -->
                    <a class="movietap" onclick="location.href='movie.php?bid=';"> 영화</a>
                    <a class="dramatap" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a>

                    <?php 
                        if($_SESSION["userId"]=="admin") {
                    ?>
                        <a class="admintap" onclick="location.href='./phptest/list.php';"> 관리자 페이지</a>
                    <?php  
                    } 
                    ?>
                </div>  <!--header_left END-->
                
			    <form class="serch" action="search_result.php" method="get">
                    <input class="serch_Img" name="button" type="image" src="img/search_img.png" />
                    <input id="searchInput" type="text" name="search" 
                    placeholder="Search" onfocus="this.placeholder=''" onblur="this.placeholder='Search'"
                    size="50" required="required"/>
                </form>
                <?php 
                // 사용자 프로필 사진
                if($_SESSION["userId"]!=""){ // 로그인 됐을 경우
                    $query3 = $db->query("select * from user where email='$_SESSION[userId]'"); 
                    while ($row = $query3->fetch()) {
                        $iset=$row['img_link'];
                    }
                ?>
                    <script>
                        function doDisplay() {
                            var asd = document.getElementById("userDiv");
                            if (asd.style.display == 'none') {
                                asd.style.display = 'block';
                            } else {
                                asd.style.display = 'none';
                            }
                        }
                    </script>
                    <img class="user_img" onclick="javascript:doDisplay();" src="user_img/<?= $iset?>">
                <?php
	             }else{ // 로그아웃일 경우
?>
                    <button class="login_btn" onclick="location.href='login.php';">로그인</button>
                <?php	   
                }
?>
            </div> <!--header END-->

            <div style="display: none;" id="userDiv"><!--유저 정보 프로필-->
                <h3><img style="width:40px;height:40px;"class="userimg" src="user_img/<?= $iset?>"><?=$_SESSION["userName"]?></h3>
                <ul>
                    <li onclick="location.href='myinfo.php';"><img class="userimg" src="img/user.png">내 계정정보</li>
                    <li onclick="location.href='pwre.php';"><img class="userimg" src="img/lock.png">비밀번호 수정</li>
                    <li onclick="location.href='mybook.php';"><img class="userimg" src="img/bookmark.png">내 북마크</li>
                    <li onclick="location.href='myreview.php';"><img class="userimg" src="img/review.png">작성한 평가</li>
                    <li onclick="location.href='log_out.php';"><img class="userimg" src="img/logout.png">로그아웃 </li>
            </div>  <!--userDiv END-->
        </header>
        <div id='wrap'>
            <div class="main_img_div"><!--메인 이미지-->
                <img class="main_img" src="img/main_img.jpg">
                <div style="width : 100%;" class="main_text">
                    <span class="main_title">MovieVerse</span>
                    <p class="main_scenario">다양한 스트리밍 독점작들을 한 곳에서 편리하게 확인해보세요.<br>
                줄간격<br>확인</p>
                    <a href="#"class="main_view_detail">상세 보기</a>
                </div>

         <div class="main_img_gradient_top"></div>
                <div class="main_img_gradient_bottom"></div>
            </div><!--main_img_div END-->

            <!-- 페이지 기능 설명 이미지-->
            <!-- <div class="img_logose">    
                <img style="margin-left:374px" class="main_img1_logos" src="img/main11.png"/>    
                <img style="margin-left:690px" class="main_img1_logos" src="img/main22.png"/>
                <img style="margin-left:1030px" class="main_img1_logos" src="img/main33.png"/>
                <img style="margin-left:1330px" class="main_img1_logos" src="img/main4.png"/>
                <p>
            </div> -->
           
            <div class="main_content_view">
                <!--드라마 리스트-->
                <p class="tv">드라마/시리즈</p>
                <p class="plus" onclick="location.href='drama.php?bid=';">ALL</p>
                <!--드라마 좌우이동-->
                <div class="move_buttons">
                    <img onclick='move("left","first")' class="left" src="img/left.png">
                    <img onclick='move("right","first")' class="right" src="img/right.png">
                </div>
                <div class="show"><!--리스트 보여지는 틀-->
                    <div class="movies"><!--드라마 리스트 이미지 출력-->
<?php
                    $pxs=0;

                    $query3 = $db->query("SELECT *  , GROUP_CONCAT(genres.genres_name) AS genrs_names  from tv left join genres ON tv.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  group by tv.id 
                    limit 0,9"); 
                    while ($row = $query3->fetch()) {

	?>

                        <div class="banner_img" style="margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=tv&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
							<p style="text-align:center; margin:10px; " 		class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px; text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center; margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center; margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        </div>
                        <div class="banner_img" style="margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=tv&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
							<p style="text-align:center; margin:10px; " 		class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px; text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center; margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center; margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        </div>
                        <div class="banner_img" style="margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=tv&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
							<p style="text-align:center; margin:10px; " 		class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px; text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center; margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center; margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        </div>
                        <div class="banner_img" style="margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=tv&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
							<p style="text-align:center; margin:10px; " 		class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px; text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center; margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center; margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        </div>

<?php
                        $pxs=24;
                    }
?>
                    </div>
                </div> <!--show END-->
            </div>  <!--main_content_view-->
            <div class="main_content_view">
                <p class="tv">영화</p>
                <p class="plus" onclick="location.href='movie.php?bid=';">ALL</p>
                
                <!--영화 좌우이동-->
                <div class="move_buttons">
                    <img onclick='move("left","second")' class="left" src="img/left.png">
                    <img onclick='move("right","second")' class="right" src="img/right.png">
                </div>
                <div class="show">
                <!--영화 리스트-->
                    <div class="movies">
                        <?php
                        $pxs=0;

                            
                        // $query3 = $db->query("SELECT *   , GROUP_CONCAT(genres.genres_name) AS genrs_names  from movie left join genres ON movie.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  group by movie.id 
                        // limit 0,9"); 
                        $query3 = $db->query("SELECT *  , GROUP_CONCAT(genres.genres_name) AS genrs_names  from tv left join genres ON tv.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  group by tv.id 
                        limit 0,9"); 
                        if (!empty($query3)) {
                        while ($row = $query3->fetch()) {

                        ?>
                            <div class="banner_img" style=" margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=movie&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
                            <p style="text-align:center; margin:10px;" class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px;text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center;margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center;margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        
                        </div>
<?php
                        $pxs=24;
                        }
                        } else {
                            ?>
<?php
                        }
?>
                    </div> <!--movies END-->
                </div>  <!--show END-->
            </div>

            <div class="main_content_view">
                <p class="tv">인기 순</p>
                <p class="plus" onclick="location.href='movie.php?bid=';">ALL</p>
                
                <!--영화 좌우이동-->
                <div class="move_buttons">
                    <img onclick='move("left","third")' class="left" src="img/left.png">
                    <img onclick='move("right","third")' class="right" src="img/right.png">
                </div>
                <div class="show">
                <!--영화 리스트-->
                    <div class="movies">
                        <?php
                        $pxs=0;

                            
                        $query3 = $db->query("SELECT *   , GROUP_CONCAT(genres.genres_name) AS genrs_names  from movie left join genres ON movie.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  group by movie.id 
                        limit 0,9"); 
                        while ($row = $query3->fetch()) {

                        ?>
                            <div class="banner_img" style=" margin-left:<?=$pxs?>px;" onclick="location.href='choice.php?choice=movie&id=<?=$row['id'];?>';">
                            <img src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
                            <p style="text-align:center; margin:10px;" class="hover_text"><?=$row['title']?></p>
                            <p style="top:80px;text-align:center; margin:10px;" class="hover_text"><?=$row['release_date']?></p>
                            <p style="top:130px;text-align:center;margin:10px;" class="hover_text"><?=$row['age']?></p>
                            <p style="top:180px;text-align:center;margin:10px;" class="hover_text"><?=$row['genrs_names']?></p>
                        </div>
<?php
                        $pxs=24;
                        }
?>
     <p>sdfjkdshfik;d</p>
     <p>sdfjkdshfik;d</p>
     <p>sdfjkdshfik;d</p>
     <p>sdfjkdshfik;d</p>
                        </div>
                    </div> <!--movies END-->
                </div>  <!--show END-->
            </div>
        </div><!--main END-->

            <!--footer-->
            <footer class="footer">
            <?php           
                //클릭 시 해당 플랫폼으로 영화 페이지 이동
	            $query3 = $db->query("select * FROM streaming ");
                while ($row = $query3->fetch()) {
?>
                    <img onclick="location.href='movie.php?platform=<?=$row['provider_id']?>';" style="margin-left:10px;"class="logoicon"src="img/<?=$row['logo_path']?>">
<?php
                }
?>
    <div style="width : 100%; text-align: center; margin : 0 auto;">
                <div style="background-color : #cecece; width : 104px; height : 104px; border-radius : 100px;
                display : inline-block;margin : 50px 35px"></div>
                                <div style="background-color : #cecece; width : 104px; height : 104px; border-radius : 100px;
                display : inline-block; margin : 50px  35px"></div>
                                <div style="background-color : #cecece; width : 104px; height : 104px; border-radius : 100px;
                display : inline-block; margin : 50px  35px"></div>
                                                <div style="background-color : #cecece; width : 104px; height : 104px; border-radius : 100px;
                display : inline-block; margin : 50px  35px"></div>
    </div>
                <p class="footer_text">신구대학교 팀프로젝트 6조
                    <br>
                    권은진 강민지 천서정 시지원 김나영
                    <br><br>
                    성남시 중원구 광명로377(금광2동 2685) 신구대학교 산학관 110호 
                </p>
            </footer>
            <!--footer END-->
</body>
</html>

<script>

// 스크롤 시 header fade-in
$(function(){
    $(document).on('scroll', function(){
        if($(window).scrollTop() > 150){
            $("header").removeClass("header_scroll_top");
            $("header").addClass("header_scroll_down");
        }else{
            $("header").removeClass("header_scroll_down");
            $("header").addClass("header_scroll_top");
        }
    })
});


    // 마우스 오버시 좌우 이동 보이게
    let show = document.querySelectorAll('.show');
    let move_buttons = document.querySelectorAll('.move_buttons');

    for(var i = 0; i < show.length; i++){
        show[0].addEventListener("mouseover", function () {
            move_buttons[0].style.opacity = "100";
        }, false);
    

        move_buttons[0].addEventListener("mouseover", function () {
            move_buttons[0].style.opacity = "100";
        }, false);
    

        show[0].addEventListener("mouseout", function(){
            move_buttons[0].style.opacity = "0";
        }, false);
    }

    // 리스트 화면 이동 기능
    function move(type, check) {
        if (check == 'first') {
            var check = 0;
        } else if (check == 'second') {
            var check = 1;
        } else if (check == 'third') {
            var check = 2;
        }
        var tab = document.querySelectorAll('.movies');
        var marginLeft = window.getComputedStyle(tab[check]).getPropertyValue('margin-left');
        marginLeft = parseInt(marginLeft);
        console.log(marginLeft);
        if (type === 'right' && marginLeft != -612) {
            var a = marginLeft - 204;
            tab[check].style.marginLeft = a + 'px';  // 마진값 변경하여 좌 우 이동
            tab[check].style.transition = `${0.1}s ease-out`;    // 이동 시 딜레이 주어 부드럽게

        } else if (type === 'left' && marginLeft != 0) {
            var a = marginLeft + 204;
            tab[check].style.marginLeft = a + 'px';
            tab[check].style.transition = `${0.1}s ease-out`;
        }
    }

</script>