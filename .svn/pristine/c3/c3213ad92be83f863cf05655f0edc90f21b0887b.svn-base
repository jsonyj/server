<?php

function smarty_function_edit_toobar($params, &$smarty) {

    $edit_link = $params['edit_link'];

    $output = '';
    if($edit_link && $_SESSION[LOGIN_ROLE_RUNZIYUAN] == ACT_ADMIN_ROLE) {
        $output = '<div class="edit-toolbar ' . $params['class'] . '"><a href="' . $edit_link . '" target="_blank">编辑</a></div>';
    }

    return $output;
}

?>
