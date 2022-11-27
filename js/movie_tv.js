$( document ).ready(function(){
    // 스크롤 시 category fade-in
    $(document).on('scroll', function(){
        if($(window).scrollTop() > 100){
            $(".category_container").removeClass("category_scroll_top");
            $(".category_container").addClass("category_scroll_down");

            $("#myDIV").removeClass("category_list_scroll_top");
            $("#myDIV").addClass("category_list_scroll_down");
        }else{
            $(".category_container").removeClass("category_scroll_down");
            $(".category_container").addClass("category_scroll_top");
            
            $("#myDIV").removeClass("category_list_scroll_down");
            $("#myDIV").addClass("category_list_scroll_top");
        }
    });

    // 카테고리 클릭 시 리스트 호출
    $("#carta").click(function() {
        $("#myDIV").fadeToggle();
    });
});