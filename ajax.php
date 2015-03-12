<?php
    $shift = $_POST['shift'];
    $target_date = $_POST['date'];
    $del_flg = $_POST['del'];
    
    $mongo = new MongoClient();
    $db = $mongo->selectDB('calendar');
    $shift_collection = $db->shift;
    $ret_count = $shift_collection->count(array('date'=>$target_date));

    if($ret_count == 0)
    {
        
    }
    
    if($del_flg == 'del') echo 'del';
    
?>