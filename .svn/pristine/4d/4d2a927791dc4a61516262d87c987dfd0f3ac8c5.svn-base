<?php

$code['paging'] = array(
    'default' => array(
        'one' => array(
            array('value' => 10, 'name' => '10件', 'default' => true),
            array('value' => 20, 'name' => '20件'),
            array('value' => 50, 'name' => '50件'),
        ),
    ),
);

$code['upload'] = array(
    'default' => array(
        'image[file]' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . APP_UPLOAD_TEMP,
            'root' => APP_RESOURCE_ROOT,
            'size' => 10 * 1024 * 1024
        ),
    ),
    'img' => array(//Realy Image
        'img[file]' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . 'upload' . DS, //上传文件服务器根目录
            'uri' => 'upload', //上传文件 url 根目录
            'dir' => DS, //文件放置目录名，DS 为不再建目录，没有 key 为年月日时间戳
            'size' => 10 * 1024 * 1024
        ),
    )
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
// OSS 参数
$code['oss'] = array(
    'default' => array(
        'bucket' => 'image-didanuo',
    ),
);
?>
