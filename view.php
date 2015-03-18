<html>
    <head>
        <meta name="viewport" content="width=device-width,height=device-height">
        <meta name="robots" content="none">
        <link type="text/css" rel="stylesheet" href="css/index.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>
    <body>
        <form action="update" method="post" enctype="multipart/form-data" name="cal_form" id="cal_form">
            <div class="this_ym">
                <span class="date_title"><span class="this_y"><?php echo $today_y; ?>&nbsp;/&nbsp;</span><span class="this_m"><?php echo $today_n; ?></span>月</span><br />
            </div>
            <div class="menu">
                <a href="#">
                    <img class="menu_btn menu_btn_on" src="img/btn_mypagen.png" />
                </a>
            </div>
            <div class="menu_list">
                <img class="menu_btn menu_btn_off" src="img/btn_mypagen.png" />
                <a href="#">
                    <div class="radius day_to_form menu_btns">日にちから<br />登録</div>
                </a>
                <a href="#">
                    <div class="radius shift_to_form menu_btns">シフトから<br />登録</div>
                </a>
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
                </span><br /><br />
                <a href="?date=">本日に戻る</a>
            </div>
            <div class="attention">
                ※登録が完了したら下部の「選択終了」ボタンを押下してください
            </div>
            <table class="main_table">
                <tr>
                    <?php foreach($conf['WEEKEND_ENG'] as $key => $val): ?>
                        <th class="weekend w<?php echo $key; ?>">
                            <span class="radius_light w_radi"><?php echo $val; ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach($calendar_main as $cal_row => $days): ?>
                    <tr>
                        <?php foreach($days as $weekend_no => $day): ?>
                            <td class="days w<?php echo $weekend_no; ?><?php if($day['type'] != 'main'): ?> not_main<?php endif; ?>" data-date="<?php echo $day['day']; ?>" data-dateFull="<?php echo $day['day_full']; ?>">
                                <span class="day<?php if($day['day_full'] == $real_today): ?> today<?php endif; ?>"><?php echo $day['day']; ?></span><br />
                                <span class="day_shift" data-shiftVal="<?php if(isset($db_data[$day['day_full']])) echo $db_data[$day['day_full']]; ?>"><?php if(isset($db_data[$day['day_full']])) echo $db_data[$day['day_full']]; ?></span>
                                <span class="mark"><img class="loading" src="img/loading<?php if($weekend_no == 0 || $weekend_no == 6) echo $weekend_no; ?>.gif" /><img class="check" src="img/check.png" /><img class="batsu" src="img/batsu.png" /></span>
                                <input type="hidden" name="date_<?php echo $day['day_full']; ?>" value="" />
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="status_bar">
                <span class="status_shift">シフトから選択：<span class="shift_val"></span>にしたい日を選択してください</span>
                <span class="status_day">日付から選択：シフトを入力したい日付を選択してください</span>
                <div class="end_select radius_light"><img src="img/select_end.png" /></div>
            </div>
            <div class="shift_box">
                <div class="contents radius">
                    シフトを選択してちょ！：<br />
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