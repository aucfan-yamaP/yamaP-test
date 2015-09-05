<?php
    $user = (isset($cookie_auth_user))? $cookie_auth_user:'guest';
    if($view_only) $user = 'mao';
    if($user == 'ryota') $user = 'mao';
    $mongo = new MongoClient();
    $db = $mongo->selectDB('calendar');
    $shift_collection = $db->shift;
    $ret = $shift_collection->find(array('status'=>0,'user'=>$user,'date'=>array('$gte'=>new MongoDate(strtotime($calendar_main[0][0]['day_full'].' 00:00:00 +0900')),'$lte'=> new MongoDate(strtotime($calendaer_last_date.' 00:00:00 +0900')))));
    $db_data = array();
    foreach($ret as $r)
    {
        $db_date = (array) $r['date'];
        $db_data[date('Y-m-j',$db_date['sec'])] = $r['shift'];
        $db_data[date('Y-n-j',$db_date['sec'])] = $r['shift'];
    }
?>