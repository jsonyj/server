<?php

$code['menu'] = array(
    'class' => array(
        'name' => '班级管理',
        'icon' => 'fa-graduation-cap',
        'uri' => '?c=class&a=index',
        // "sub" => array(
        //     'index' => array("name" => '班级一览', 'uri' => '?c=class&a=index'),
        // )
    ),
    // 'student' => array(
    //     'name' => '学生管理',
    //     'icon' => 'fa-users',
    //     "sub" => array(
    //         'index' => array("name" => '学生一览', 'uri' => '?c=student&a=index'),
    //         'claimIndex' => array("name" => '学生认领', 'uri' => '?c=student&a=claimIndex'),
    //     )
    // ),
    // 'parent' => array(
    //     'name' => '家长管理',
    //     'icon' => 'fa-user',
    //     "sub" => array(
    //         'index' => array("name" => '家长一览', 'uri' => '?c=parent&a=index'),
    //         'typeIndex' => array("name" => '接送一览', 'uri' => '?c=parent&a=pickUp'),
    //     )
    // ),
    'student' => array(
        'name' => '学生一览',
        'icon' => 'fa-users',
        'uri' => '?c=student&a=index',
        // "sub" => array(
        //     'index' => array("name" => '学生一览', 'uri' => '?c=student&a=index'),
        // )
    ),
    'students' => array(
        'name' => '学生认领',
        'icon' => 'fa-child',
        'uri' => '?c=student&a=claimIndex',
    ),
    'students1' => array(
        'name' => '学生信息导出',
        'icon' => 'fa-download',
        'uri' => '?c=student&a=exportIndex',
    ),
    'students2' => array(
        'name' => '学生信息打印',
        'icon' => 'fa-print',
        'uri' => '?c=student&a=printIndex',
    ),
    // 'parent' => array(
    //     'name' => '家长管理',
    //     'icon' => 'fa-user',
    //     'uri' => '?c=parent&a=index',
    //     // "sub" => array(
    //     //     'index' => array("name" => '家长一览', 'uri' => '?c=parent&a=index'),
    //     // )
    // ),
    'parents' => array(
        'name' => '接送一览',
        'icon' => 'fa-user',
        'uri' => '?c=parent&a=pickUp',
    ),
    'video' => array(
        'name' => '视频管理',
        'icon' => 'fa-eye',
        'uri' => '?c=video&a=index',
        // "sub" => array(
        //     'index' => array("name" => '视频一览', 'uri' => '?c=video&a=index'),
        // )
    ),
    'staff' => array(
        'name' => '职工管理',
        'icon' => 'fa-user-secret',
        "sub" => array(
            'index' => array("name" => '职工列表', 'uri' => '?c=staff&a=index'),
            // 'typeIndex' => array("name" => '考勤类型列表', 'uri' => '?c=staff&a=typeIndex'),
            'signIndex' => array("name" => '签到统计列表', 'uri' => '?c=staff&a=signIndex'),

        )
    ),
    // 'sign' => array(
    //     'name' => '签到管理',
    //     'icon' => 'fa-bar-chart',
    //     "sub" => array(
    //         'typeIndex' => array("name" => '考勤类型列表', 'uri' => '?c=sign&a=typeIndex'),
    //         'signIndex' => array("name" => '签到统计列表', 'uri' => '?c=sign&a=signIndex'),
    //     )
    // ),
);

$code['upload'] = array(
    'default' => array(
        'image[file]' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . APP_UPLOAD_TEMP,
            'root' => APP_RESOURCE_ROOT,
            'size' => 1.5 * 1024 * 1024
        ),
    )
);

$code['paging'] = array(
    'default' => array(
        'one' => array(
            array('value' => 10, 'name' => '10件', 'default' => true),
            array('value' => 20, 'name' => '20件'),
            array('value' => 50, 'name' => '50件')
        ),
    ),
    'imgPaging' => array(
        'one' => array(
            array('value' => 24, 'name' => '24件', 'default' => true),
            array('value' => 36, 'name' => '36件'),
            array('value' => 48, 'name' => '48件')
        ),

    ),
);

$code['order'] = array(
    'default' => array(
        'a' => array('field' => 'created', 'default' => true, 'sort' => 'DESC'),
    ),
    'class' => array(
        'a' => array('field' => 'tb_class.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'device' => array(
        'a' => array('field' => 'tb_device.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'sign' => array(
        'a' => array('field' => 'r.sign_date', 'default' => true, 'sort' => 'DESC'),//r=tb_sign_record
    ),
    'takeAway' => array(
        'a' => array('field' => 'tb_away.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'studentSearch' => array(
        'a' => array('field' => 'tb_student.created', 'default' => true, 'sort' => 'DESC'),
    ),

);

$code['gender'] = array(
    GENDER_MEN => array('name' => '男', 'value' => GENDER_MEN),
    GENDER_WOMEN => array('name' => '女', 'value' => GENDER_WOMEN)
);


$code['status'] = array(
    0 => array('name' => '否', 'value' => '0'),
    1 => array('name' => '是', 'value' => '1')
);

$code['parentType'] = array(
    PARENT_TYPE_MAJOR => array('name' => '主账号', 'value' => PARENT_TYPE_MAJOR)
);

$code['relation'] = array(
    PARENT_TYPE_FATHER => array('name' => '爸爸', 'value' => PARENT_TYPE_FATHER),
    PARENT_TYPE_MOTHER => array('name' => '妈妈', 'value' => PARENT_TYPE_MOTHER),
    PARENT_TYPE_GRANDPA => array('name' => '爷爷', 'value' => PARENT_TYPE_GRANDPA),
    PARENT_TYPE_GRANDMA => array('name' => '奶奶', 'value' => PARENT_TYPE_GRANDMA),
    PARENT_TYPE_GRANDFATHER => array('name' => '外公', 'value' => PARENT_TYPE_GRANDFATHER),
    PARENT_TYPE_GRANDMOTHER => array('name' => '外婆', 'value' => PARENT_TYPE_GRANDMOTHER),
    PARENT_TYPE_OTHER => array('name' => '其他', 'value' => PARENT_TYPE_OTHER),
);

//短信
$code['sms'] = array(
    'template' => array(
        'parentAdd' => '您好，$studentName小宝贝家长，小诺已经把您和宝贝的资料录入系统，请点击[http://t.cn/R4awNT3]，关注公众号，完成微信和宝贝的绑定后，每天就可以看到您家小宝贝的晨检结果了'
    ),
);
/**
 * @desc 在职员工签到
 * @author ly
 */
$code['sign_staff'] = array(
    ACT_SCHOOL_TEACHER => array('name' => '老师', 'value' => ACT_SCHOOL_TEACHER),
    ACT_SCHOOL_DOCTOR => array('name' => '医生', 'value' => ACT_SCHOOL_DOCTOR),
    ACT_SCHOOL_SUPPORTER => array('name' => '勤务', 'value' => ACT_SCHOOL_SUPPORTER)
);

$code['is_in'] = array(
    APP_UNIFIED_FALSE => array('name' => '未签到', 'value' => APP_UNIFIED_FALSE),
    APP_UNIFIED_TRUE => array('name' => '已签到', 'value' => APP_UNIFIED_TRUE)
);

$code['is_out'] = array(
    APP_UNIFIED_FALSE => array('name' => '未签退', 'value' => APP_UNIFIED_FALSE),
    APP_UNIFIED_TRUE => array('name' => '已签退', 'value' => APP_UNIFIED_TRUE)
);

$code['sign_status'] = array(
    SIGN_STATUS_UNIN_UNOUT => array('name' => '已缺勤', 'value' => SIGN_STATUS_UNIN_UNOUT),
    SIGN_STATUS_IN_UNOUT => array('name' => '签到+未签退', 'value' => SIGN_STATUS_IN_UNOUT),
    SIGN_STATUS_LATE_UNOUT => array('name' => '迟到+未签退', 'value' => SIGN_STATUS_LATE_UNOUT),
    SIGN_STATUS_IN_OUT => array('name' => '签到+签退', 'value' => SIGN_STATUS_IN_OUT),
    SIGN_STATUS_IN_EARLY => array('name' => '签到+早退', 'value' => SIGN_STATUS_IN_EARLY),
    SIGN_STATUS_LATE_OUT => array('name' => '迟到+签退', 'value' => SIGN_STATUS_LATE_OUT),
    SIGN_STATUS_LATE_EARLY => array('name' => '迟到+早退', 'value' => SIGN_STATUS_LATE_EARLY),
);

/**
 * @desc 职工类型
 * @author ly
 */
$code['staff_type'] = array(
    ACT_SCHOOL_HEADMASTER => array('name' => '园长', 'value' => ACT_SCHOOL_HEADMASTER),
    ACT_SCHOOL_TEACHER => array('name' => '老师', 'value' => ACT_SCHOOL_TEACHER),
    ACT_SCHOOL_DOCTOR => array('name' => '医生', 'value' => ACT_SCHOOL_DOCTOR),
    ACT_SCHOOL_SUPPORTER => array('name' => '勤务', 'value' => ACT_SCHOOL_SUPPORTER)
);

?>
