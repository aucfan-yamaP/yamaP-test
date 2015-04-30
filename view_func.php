<?php
    function isAndroid()
    {
        $return = false;
        if(stripos($_ENV["HTTP_USER_AGENT"],'iPhone') === false)
        {
            $return = true;
        }
        return $return;
    }
?>