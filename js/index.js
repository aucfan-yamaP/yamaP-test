$(function(){
    var shift_btn = '';
    var day_select = '';
    $('.menu_btn').click(function(){
        if($('table.main_table').hasClass('select') || $('table.main_table').hasClass('selectday')) return false;
        menuToggle($(this));
        return false;
    });
    $('#select_date').change(function(){
        location.href = '?date='+$(this).val();
    });
    $('.shift_to_form').click(function(){
        menuToggle($('.menu_btn_off'));
        $('.shift_box').fadeIn('fast');
        return false;
    });
    $('.day_to_form').click(function(){
        menuToggle($('.menu_btn_off'));
        $('.status_bar .status_day').css('display','inline-block');
        $('.main_table').addClass('selectday');
        $('.end_select').css('display','block');
        $('body').addClass('select');
        $('div.attention').css('display','block');
        $(window).scrollTop(100);
        $('.menu_btn').css('display','none');
        return false;
    });
    $('.shift_box .contents li').click(function(){
        if($('table.main_table').hasClass('selectday')) return false;
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
        $('body').addClass('select');
        $('div.attention').css('display','block');
        $(window).scrollTop(100);
        $('.menu_btn').css('display','none');
    });

    $('table.main_table td.days').click(function(){
        if(!$('table.main_table').hasClass('select')) return false;
        if($(this).find('span.mark img.check').css('display') != 'none')
        {
            $(this).find('span.mark img.check').css('display','none');
            $(this).find('input').val('');
            ajaxCheck($(this),shift_btn,'del');
            $(this).find('.day_shift').attr('data-shiftVal','');
            $(this).find('.day_shift').text('');
        } else {
            $(this).find('span.mark img.loading').css('display','block');
            $(this).find('input').val(shift_btn);
            ajaxCheck($(this),shift_btn);
        }
    });
    $('table.main_table td.days').click(function(){
        if(!$('table.main_table').hasClass('selectday')) return false;
        day_select = $(this);
        var lis = $('.shift_box .contents li');
        for(var i=0;i<lis.length;i++)
        {
            $(lis[i]).removeClass('on');
            if($(lis[i]).attr('data-val') == $(this).find('.day_shift').attr('data-shiftVal')) $(lis[i]).addClass('on');
        }
        $('.shift_box').fadeIn('fast');
    });

    $('.shift_box .contents li').click(function(){
        if(!$('table.main_table').hasClass('selectday')) return false;
        shift_btn = $(this).attr('data-val');
        if($(this).hasClass('on'))
        {
            ajaxCheck(day_select,shift_btn,'del');
            day_select.find('span.mark img.check').css('display','none');
            day_select.find('.day_shift').attr('data-shiftVal','');
            day_select.find('.day_shift').text('');
            $(this).removeClass('on');
        } else {
            day_select.find('span.mark img.loading').css('display','block');
            ajaxCheck(day_select,shift_btn);
            day_select.find('.day_shift').attr('data-shiftVal',shift_btn);
            day_select.find('.day_shift').text(shift_btn);
            $(this).addClass('on');
        }
        $('.shadow').click();
    });

    $('.end_select').click(function(){
        if($('.main_table').hasClass('select')) $('.main_table').removeClass('select');
        $('.status_bar .status_shift').css('display','none');
        $(this).css('display','none');
        return true;
    });

    $('table.main_table,.menu_btn').touchwipe({
         wipeLeft:function(){
             $('.menu_btn').click();
         },
         wipeRight:function(){
             $('.menu_btn').click();
         },
         wipeUp:function(){
             return true;
         },
         wipeDown:function(){
             return true;             
         },
         min_move_x: 70,
         min_move_y: 70,
         preventDefaultEvents: true
    });

    $('.shadow').click(function(){
        $(this).parent('div').fadeOut('fast');
    });
    function menuToggle(switch_obj){
        if(switch_obj.hasClass('menu_btn_on')) $('.menu_list').css('display','block');
        if(switch_obj.hasClass('menu_btn_off')) $('.menu_list').css('display','none');        
    }
    function ajaxCheck(obj,shift,delFlg){
        obj.find('img.batsu').css('display','none');
        var del = (delFlg == 'del')? 'del':'';
        $.ajax({
            url:'ajax.php',
            type:'POST',
            data:{'shift':shift,'date':obj.attr('data-dateFull'),'del':del},
            success:function(ret){
                if(ret != 1 && ret != 'del')
                {
                    obj.find('img.batsu').fadeIn();
                }
                if(ret != 'del')
                {
                    obj.find('img.loading').fadeOut('fast',function(){
                        obj.find('img.check').fadeIn();
                    });
                }
            },
            error:function(){
                obj.find('img.batsu').fadeIn();
            },
        });
    }
});