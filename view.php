<html>
    <head>
        <link type="text/css" rel="stylesheet" href="css/index.css" />
    </head>
    <body>
        <div class="this_ym">
            <span class="this_y"><?php echo $today_y; ?>&nbsp;/&nbsp;</span><span class="this_m"><?php echo $today_n; ?></span>月
        </div>
        <table>
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
                        <td class="days w<?php echo $weekend_no; ?><?php if($day['type'] != 'main'): ?> not_main<?php endif; ?>">
                            <span class="day"><?php echo $day['day']; ?></span>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>