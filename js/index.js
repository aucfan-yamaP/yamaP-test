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
        shift_btn = $(this).attr('data-val');
        $('.status_bar span.status_shift span.shift_val').text(shift_btn);
        $('.status_bar .status_shift').css('display','inline-block');
        $('.status_bar .status_shift span.shift_val').css('display','inline-block');
        $(this).addClass('on');
        $('.main_table').addClass('select');
        $('.end_select').css('display','block');
        $('.shadow').click();
    });

    $('table.main_table td.days').click(function(){
        if(!$('table.main_table').hasClass('select')) return false;
        if($(this).find('span.mark img.check').css('display') != 'none')
        {
            $(this).find('span.mark img.check').css('display','none');
            $(this).find('input').val('');            
        } else {
            $(this).find('span.mark img.check').css('display','block');
            $(this).find('input').val(shift_btn);
        }
    });
    $('.end_select').click(function(){
        if($('.main_table').hasClass('select')) $('.main_table').removeClass('select');
        $('.status_bar .status_shift').css('display','none');
        $(this).css('display','none');
    });

    $('.shadow').click(function(){
        $(this).parent('div').fadeOut('fast');
    });
    function menuToggle(switch_obj){
        if(switch_obj.hasClass('menu_btn_on')) $('.menu_list').css('display','block');
        if(switch_obj.hasClass('menu_btn_off')) $('.menu_list').css('display','none');        
    }
});