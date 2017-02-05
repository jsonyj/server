<?php

function smarty_function_ago($params, &$smarty) {

    $datetime = $params['datetime'];

    $now_time = date("Y-m-d H:i:s", time());
    $now_time = strtotime($now_time);
    $show_time = strtotime($datetime);

    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}

?>
