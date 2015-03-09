$(function(){
    var shift_btn = '';
    $('.menu_btn').click(function(){
        menuToggle($(this));
        return false;
    });
    $('.shift_to_form').click(function(){
        menuToggle($('.menu_btn_off'));
        $('.shift_box').fadeIn('fast');
        return false;
    });
    $('.shift_box .contents li').click(function(){
        var lis = $('.shift_box .contents li');
        for(var i=0;i<lis.length;i++)
        {
            if($(lis[i]).hasClass('on')) $(lis[i]).removeClass('on');
        }
        $('.status_bar span.status_shift span.shift_val').text($(this).find('div.val').text());
        $('.status_bar .status_shift').css('display','inline-block');
        $('.status_bar .status_shift span.shift_val').css('display','inline-block');
        $(this).addClass('on');
        $('.shadow').click();
    });

    $('.shadow').click(function(){
        $(this).parent('div').fadeOut('fast');
    });
    function menuToggle(switch_obj){
        if(switch_obj.hasClass('menu_btn_on')) $('.menu_list').css('display','block');
        if(switch_obj.hasClass('menu_btn_off')) $('.menu_list').css('display','none');        
    }
});