<?php
    $shift = $_POST['shift'];
    $target_date = $_POST['date'];
    $del_flg = $_POST['del'];
    $ret = '';
    
    $mongo = new MongoClient();
    $db = $mongo->selectDB('calendar');
    $shift_collection = $db->shift;
    $select_query = array('date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0);
    $ret_count = $shift_collection->count($select_query);
    if($ret_count == 0)
    {
        $ret = $shift_collection->insert(array('_id'=>$shift_collection->count()+1,'shift'=>$shift,'date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0,'created_at'=>new MongoDate(),'updated_at'=>new MongoDate()));
    }
    if($ret_count >= 1)
    {
        if($del_flg != 'del')
        {
            $ret = $shift_collection->update($select_query,array('$set' => array('shift'=>$shift,'date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0,'updated_at'=>new MongoDate())));
        } else {
            $ret = $shift_collection->update($select_query,array('$set' => array('status'=>9,'updated_at'=>new MongoDate())));
            echo $del_flg;
            exit;
        }
    }
    echo (is_array($ret))? '1':'';
?>