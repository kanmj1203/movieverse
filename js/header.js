//브라우저 실시간 크기 가져오기
let windowWidth = window.innerWidth;
$(window).resize(function() {
    windowWidth = window.innerWidth;

    // 화면 사이즈 조절 시 열려있는 메뉴 닫히게
    // 사이즈 1023 이상 : 햄버거 체크 해제, ~이하 : 프로필 메뉴 안보이게
    windowWidth <= '1023' ? $("#userDiv").css('display', 'none') : $("#menu_icon").prop('checked', false); 
    
// console.log(windowWidth);
}).resize(); 

$( document ).ready(function(){
    // 스크롤 시 header fade-in
    $(document).on('scroll', function(){
        if($(window).scrollTop() > 100){
            $("header").removeClass("header_scroll_top");
            $("header").addClass("header_scroll_down");
        }else{
            $("header").removeClass("header_scroll_down");
            $("header").addClass("header_scroll_top");
        }
    });

    //  GNB 검색 버튼 클릭 시 화면 출력
    $(".search_button").click(function(){
        $(".search_modal").fadeIn();
    });

    $(".search_modal_close").click(function(){
        $(".search_modal").fadeOut();
    });

    // 로그인 상태일 때 프로필 누르면 리스트 보여지게
    $(".user_img").click(function() {
        let profile_list = $("#userDiv");
        // profile_list.slideToggle();
        profile_list.fadeToggle();
    });
    
});
