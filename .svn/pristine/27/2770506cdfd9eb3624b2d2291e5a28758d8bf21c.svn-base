<?php

$code['menu'] = array(
    'device' => array(
        'name' => '设备管理',
        'icon' => 'fa-cloud',
        "sub" => array(
            'index' => array("name" => '设备一览', 'uri' => '?c=device&a=index'),
        )
    ),
    'school' => array(
        'name' => '学校管理',
        'icon' => 'fa-university',
        "sub" => array(
            'index' => array("name" => '学校一览', 'uri' => '?c=school&a=index'),
            'dataStatistics' => array("name" => '设备运营统计', 'uri' => '?c=school&a=dataStatistics'),
        )
    ),
    'class' => array(
        'name' => '班级管理',
        'icon' => 'fa-graduation-cap',
        "sub" => array(
            'index' => array("name" => '班级一览', 'uri' => '?c=class&a=index'),
        )
    ),
    'student' => array(
        'name' => '学生管理',
        'icon' => 'fa-users',
        "sub" => array(
            'index' => array("name" => '学生一览', 'uri' => '?c=student&a=index'),
            'dreamIndex' => array("name" => '学生梦想', 'uri' => '?c=student&a=dreamIndex'),
            'hobbyIndex' => array("name" => '学生爱好', 'uri' => '?c=student&a=hobbyIndex'),
        )
    ),
    'parent' => array(
        'name' => '家长管理',
        'icon' => 'fa-user',
        "sub" => array(
            'index' => array("name" => '家长一览', 'uri' => '?c=parent&a=index'),
        )
    ),
    'article' => array(
        'name' => '文章管理',
        'icon' => 'fa-file-text-o',
        "sub" => array(
            'index' => array("name" => '文章一览', 'uri' => '?c=article&a=index'),
        )
    ),
    'help' => array(
        'name' => '帮助管理',
        'icon' => 'fa-bar-chart',
        "sub" => array(
            'index' => array("name" => '帮助一览', 'uri' => '?c=help&a=index'),
        )
    ),
    'branch' => array(
        'name' => '群管理',
        'icon' => 'fa-gears',
        "sub" => array(
            'index' => array("name" => '群一览', 'uri' => '?c=branch&a=index'),
        )
    ),
    'version' => array(
        'name' => '版本管理',
        'icon' => 'fa-cog',
        "sub" => array(
            'index' => array("name" => '版本一览', 'uri' => '?c=version&a=index'),
        )
    ),
    'downimg' => array(
        'name' => '图片管理',
        'icon' => 'fa-file-picture-o',
        'sub' => array(
            'index' => array("name" => '图片一览', 'uri' => '?c=downimg&a=index'),
        )
    ),
    'activity' => array(
        'name' => '活动管理',
        'icon' => 'fa-file-picture-o',
        'sub' => array(
            'index' => array("name" => '活动一览', 'uri' => '?c=activity&a=index'),
            'gift' => array("name" => '礼物一览', 'uri' => '?c=activity&a=giftIndex'),
        )

    ),
);

$code['upload'] = array(
    'dream' => array(
        'dream[file]' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . 'dream' . DS ,
            'uri' => 'dream',
            'dir' => DS,
            'size' => 1.5 * 1024 * 1024
        ),
    ),
    'help' => array(
        'help[file]' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . 'help' . DS ,
            'uri' => 'help',
            'dir' => DS,
            'size' => 1.5 * 1024 * 1024
        )
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
    'device' => array(
        'one' => array(
            array('value' => 50, 'name' => '50件', 'default' => true),
            array('value' => 100, 'name' => '100件')
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
    'help' => array(
        'a' => array('field' => 'tb_help.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'dream' => array(
        'a' => array('field' => 'tb_dream.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'hobby' => array(
        'a' => array('field' => 'tb_hobby.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'branch' => array(
        'a' => array('field' => 'tb_branch.created', 'default' => true, 'sort' => 'DESC'),
    ),
    'version' => array(
        'a' => array('field' => 'tb_version.created', 'default' => true, 'sort' => 'DESC'),
    ),
    // 'pick' => array(
    //     'a' => array('field' => 'tb_student_away_record.created', 'default' => true, 'sort' => 'DESC'),
    // ),
);

$code['gender'] = array(
    GENDER_MEN => array('name' => '男', 'value' => GENDER_MEN),
    GENDER_WOMEN => array('name' => '女', 'value' => GENDER_WOMEN)
);


$code['status'] = array(
    0 => array('name' => '否', 'value' => '0'),
    1 => array('name' => '是', 'value' => '1')
);

$code['gradeList'] = array(
    1 => array('name' => '一年级', 'value' => '1'),
    2 => array('name' => '二年级', 'value' => '2'),
    3 => array('name' => '三年级', 'value' => '3'),
    4 => array('name' => '四年级', 'value' => '4')
);

$code['parentType'] = array(
    PARENT_TYPE_MAJOR => array('name' => '主账户', 'value' => PARENT_TYPE_MAJOR)
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

//短信验证码接口
$code['sms'] = array(
    'account' => 'cf_ddnkj',
    'password' => 'didanuo',
    'url' => 'http://106.ihuyi.cn/webservice/sms.php',
    'template' => array(
        'parentAdd' => '您好，$studentName小宝贝家长，小诺已经把您和宝贝的资料录入系统，请点击[http://t.cn/R4awNT3]，关注公众号，完成微信和宝贝的绑定后，每天就可以看到您家小宝贝的晨检结果了'
    ),
);
// OSS 参数
$code['oss'] = array(
    'default' => array(
        'bucket' => 'image-didanuo',
    ),
);
?>
