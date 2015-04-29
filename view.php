<html>
    <head>
        <meta name="viewport" content="width=device-width,height=device-height">
        <meta name="robots" content="none">
        <link type="text/css" rel="stylesheet" href="css/index.css?<?php echo date('YmdHis'); ?>" />
        <link rel="apple-touch-icon" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/maoshift_favi.png" />
        <link rel="shortcut icon" href="favicon.ico" /> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/swipe.js"></script>
        <script src="js/index.js"></script>
        <title>まおｼﾌﾄ</title>
    </head>
    <body>
        <form action="update" method="post" enctype="multipart/form-data" name="cal_form" id="cal_form">
            <div class="account_img">
                <img src="img/bell<?php echo rand(1,3); ?>.png" />
            </div>
            <div class="this_ym">
                <span class="date_title"><span class="this_y"><?php echo $today_y; ?>&nbsp;/&nbsp;</span><span class="this_m"><?php echo $today_n; ?></span>月</span><br />
            </div>
            <div class="menu">
                <a href="#">
                    <img class="menu_btn menu_btn_on" src="img/btn_mypagen.png" />
                </a>
                <img class="menu_btn_grey" src="img/btn_mypagen_grey.png" />
            </div>
            <div class="menu_list">
                <?php if(!$view_only): ?>
                    シフト登録：
                <?php endif; ?>
                <img class="menu_btn menu_btn_off" src="img/btn_mypagen.png" />
                <?php if(!$view_only): ?>
                    <a href="#">
                        <div style="margin-top:0px;" class="radius day_to_form menu_btns">日にちから<br />登録</div>
                    </a>
                    <a href="#">
                        <div class="radius shift_to_form menu_btns">シフトから<br />登録</div>
                    </a>
                <?php endif; ?>
                <span class="select_date">
                    年月変更：
                    <select name="select_date" id="select_date">
                        <?php for($ii=6;$ii>=1;$ii--): ?>
                        <option <?php if(date('Y-m',$this_day_strtotime) == date('Y-m',strtotime($real_today.' -'.$ii.' month'))) echo 'selected'; ?> value="<?php echo date('Y-m',strtotime($real_today.' -'.$ii.' month')); ?>"><?php echo date('Y/m',strtotime($real_today.' -'.$ii.' month')); ?></option>
                        <?php endfor; ?>
                        <option <?php if(date('Y-m',$this_day_strtotime) == date('Y-m',strtotime($real_today))) echo 'selected'; ?> value="<?php echo date('Y-m',strtotime($real_today)); ?>"><?php echo date('Y/m',strtotime($real_today)); ?></option>
                        <?php for($ii=1;$ii<=6;$ii++): ?>
                            <option <?php if(date('Y-m',$this_day_strtotime) == date('Y-m',strtotime($real_today.' +'.$ii.' month'))) echo 'selected'; ?> value="<?php echo date('Y-m',strtotime($real_today.' +'.$ii.' month')); ?>"><?php echo date('Y/m',strtotime($real_today.' +'.$ii.' month')); ?></option>
                        <?php endfor; ?>
                    </select>
                </span>
                <?php if($view_only): ?>
                    <ul class="shift_info">
                        <li>●シフト時間</li>
                        <?php foreach($conf['MAO_SHIFTS'] as $key => $val): ?>
                            <?php if($key == 'off') continue; ?>
                            <li>
                                <?php echo $key; ?>：<br />　<?php echo $val['time_start'].'〜'.$val['time_end']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <br /><br />
                <span class="funny_img">
                    <a href="http://<?php echo $_SERVER['SERVER_NAME']; ?><?php if($view_only): ?>/top.php<?php endif; ?>"><?php if(!$view_only): ?><img src="img/<?php echo (rand(0,1) == 0)? 'ryota':'mao'; ?>0.png" /><?php endif; ?>&nbsp;&nbsp;今月に戻る</a>
                </span>
                <span class="logout">
                    <!-- <a href="#">ログアウト</a> -->
                </span>
            </div>
            <div class="attention">
                ※登録が完了したら下部の「選択完了」ボタンを押下してください
            </div>
            <table class="main_table" data-nextcal="<?php echo $after_y.'-'.$after_n; ?>" data-prevcal="<?php echo $before_y.'-'.$before_n; ?>">
                <tr class="week_tr">
                    <?php foreach($conf['WEEKEND_ENG'] as $key => $val): ?>
                        <th class="weekend w<?php echo $key; ?>">
                            <span class="radius_light w_radi"><?php echo $val; ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach($calendar_main as $cal_row => $days): ?>
                    <tr>
                        <?php foreach($days as $weekend_no => $day): ?>
                            <td class="days w<?php echo $weekend_no; ?><?php if($day['type'] != 'main'): ?> not_main<?php endif; ?><?php if(isset($holiday_array[$day['day_full']])): ?> holi<?php endif; ?>" data-date="<?php echo $day['day']; ?>" data-dateFull="<?php echo $day['day_full']; ?>">
                                <span class="day<?php if($day['day_full'] == $real_today): ?> today<?php endif; ?>"><?php echo $day['day']; ?></span><br />
                                <span class="day_shift" data-shiftVal="<?php if(isset($db_data[$day['day_full']])) echo $db_data[$day['day_full']]; ?>"><?php if(isset($db_data[$day['day_full']])) echo $db_data[$day['day_full']]; ?></span><br />
                                <span class="mark"><img class="loading" src="img/loading<?php if($weekend_no == 0 || $weekend_no == 6) echo $weekend_no; ?>.gif" /><img class="check" src="img/check.png" /><img class="batsu" src="img/batsu.png" /></span>
                                <input type="hidden" name="date_<?php echo $day['day_full']; ?>" value="" />
                                <?php if(isset($holiday_array[$day['day_full']])): ?><div class="holiday"><?php echo $holiday_array[$day['day_full']]; ?></div><?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="status_bar">
                <span class="status_shift">シフトから選択：<span class="shift_val"></span>にしたい日を選択してください</span>
                <span class="status_day">日付から選択：シフトを入力したい日付を選択してください</span>
                <div class="end_select radius_light"><a href=""><img src="img/select_end_new.png" /></a></div>
            </div>
            <div class="shift_box">
                <div class="contents radius">
                    <span class="select_shift_day"></span>シフトを選択してちょ！：<br />
                    <ul>
                        <?php foreach($conf['MAO_SHIFTS'] as $shift_name => $info): ?>
                            <li class="radius_light" data-val="<?php echo $shift_name; ?>">
                                <div class="val"><?php echo $shift_name; ?></div>
                                <?php if($shift_name != 'off'): ?>
                                    <div class="shift_info radius_light">
                                            <?php echo $info['time_start']; ?>〜<?php echo $info['time_end']; ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="shadow"></div>
            </div>
        </form>
    </body>
</html>