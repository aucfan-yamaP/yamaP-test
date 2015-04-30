<?php
    function isAndroid()
    {
        $return = false;
        if(stripos($_SERVER['HTTP_USER_AGENT'],'iPhone') === false)
        {
            $return = true;
        }
        return $return;
    }
?>