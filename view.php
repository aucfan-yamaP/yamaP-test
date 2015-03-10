<html>
    <head>
        <link type="text/css" rel="stylesheet" href="css/index.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>
    <body>
        <form action="update" method="post" enctype="multipart/form-data" name="cal_form" id="cal_form">
            <div class="this_ym">
                <span class="this_y"><?php echo $today_y; ?>&nbsp;/&nbsp;</span><span class="this_m"><?php echo $today_n; ?></span>月   
            </div>
            <div class="menu">
                <img class="menu_btn menu_btn_on" src="img/btn_mypagen.png" />
            </div>
            <div class="menu_list">
                <img class="menu_btn menu_btn_off" src="img/btn_mypagen.png" />
                <div class="radius shift_to_form menu_btns">シフトから登録</div>
                <div class="radius day_to_form menu_btns">日にちから登録</div>
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
                                <span class="day"><?php echo $day['day']; ?></span>
                                <span class="mark"><img class="check" src="img/check.png" /><img class="batsu" src="img/batsu.png" /></span>
                                <input type="hidden" name="date_<?php echo $day['day_full']; ?>" value="" />
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="status_bar">
                <span class="status_shift">シフトから選択：<span class="shift_val"></span>にしたい日を選択してください</span>
                <div class="end_select radius_light"><img src="img/select_end.png" /></div>
            </div>
            <div class="shift_box">
                <div class="contents radius">
                    シフトを選択してちょ！：
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