<?php
    require_once('auth.php');
    require_once('conf.php');

    $this_day = (isset($_GET['date']) && strtotime($_GET['date']) > strtotime('2010-01-01'))? $_GET['date']:date('Y-m-d');
    $this_day_strtotime = strtotime($this_day);
    $real_today = date('Y-m-j');

    $today_y = date('Y',$this_day_strtotime);
    $today_m = date('m',$this_day_strtotime);
    $today_d = date('d',$this_day_strtotime);
    $today_n = date('n',$this_day_strtotime);
    $today_j = date('j',$this_day_strtotime);
    
    $before_y = date('Y',strtotime($this_day.' -1 month'));
    $before_m = date('m',strtotime($this_day.' -1 month'));
    $before_d = date('d',strtotime($this_day.' -1 month'));
    $before_n = date('n',strtotime($this_day.' -1 month'));
    $before_j = date('j',strtotime($this_day.' -1 month'));

    $after_y = date('Y',strtotime($this_day.' +1 month'));
    $after_m = date('m',strtotime($this_day.' +1 month'));
    $after_d = date('d',strtotime($this_day.' +1 month'));
    $after_n = date('n',strtotime($this_day.' +1 month'));
    $after_j = date('j',strtotime($this_day.' +1 month'));

    $before_l = date('d',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
    $today_l = date('d',strtotime($after_y.'-'.$after_m.'-01 -1 day'));
    $before_lj = date('j',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
    $today_lj = date('j',strtotime($after_y.'-'.$after_m.'-01 -1 day'));
    $before_lw = date('w',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
    $today_lw = date('w',strtotime($after_y.'-'.$after_m.'-01 -1 day'));
    
    $today_month_first_weekend = date('w',strtotime($today_y.'-'.$today_m.'-01'));
    $today_month_first_weekend_str = $conf['WEEKEND'][$today_month_first_weekend];

    $calendar_before_month_days = array();
    for($i = 0;$i<$today_month_first_weekend;$i++)
    {
        $calendar_before_month_days[$i] = $before_lj-($today_month_first_weekend-$i);
    }

    $calendar_after_month_days = array();
    for($i = $today_lw;$i<6;$i++)
    {
        $calendar_after_month_days[$today_lw+(count($calendar_after_month_days)+1)] = count($calendar_after_month_days)+1;
    }

    $calendar_main = array();
    $calendar_row_count = 0;
    $calendaer_last_date = '';
    foreach($calendar_before_month_days as $key => $val)
    {
        $calendar_main[$calendar_row_count][$key]['type'] = 'before';
        $calendar_main[$calendar_row_count][$key]['day'] = $val;
        $calendar_main[$calendar_row_count][$key]['day_full'] = $before_y.'-'.$before_n.'-'.$val;
    }
    for($i=1;$i<=$today_lj;$i++)
    {
        $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_m.'-'.$i))]['type'] = 'main';
        $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_m.'-'.$i))]['day'] = $i;
        $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_n.'-'.$i))]['day_full'] = $today_y.'-'.$today_m.'-'.$i;
        $calendaer_last_date = $today_y.'-'.$today_m.'-'.$i;
        if(date('w',strtotime($today_y.'-'.$today_m.'-'.$i)) == 6) $calendar_row_count++;
    }
    foreach($calendar_after_month_days as $key => $val)
    {
        $calendar_main[$calendar_row_count][$key]['type'] = 'after';
        $calendar_main[$calendar_row_count][$key]['day'] = $val;
        $calendar_main[$calendar_row_count][$key]['day_full'] = $after_y.'-'.$after_n.'-'.$val;
        $calendaer_last_date = $after_y.'-'.$after_n.'-'.$val;
    }
    require_once('db.php');
?>