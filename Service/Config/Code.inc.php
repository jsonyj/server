<?php

$code['paging'] = array(
    'default' => array(
        'one' => array(
            array('value' => 10, 'name' => '10件', 'default' => true),
        ),
    ),
);

$code['upload'] = array(
    'image' => array(
        'image' => array(
            'ext' => array('jpg', 'gif', 'png', 'bmp'),
            'base' => APP_RESOURCE_ROOT . 'upload' . DS . 'temp' . DS,
            'size' => 10 * 1024 * 1024
        )
    ),
);

$code['order'] = array(
    'default' => array(
        'a' => array('field' => 'created', 'default' => true, 'sort' => 'DESC'),
    ),
);

$code['sex'] = array(
    1 => array('name' => '男', 'value' => 5),
    2 => array('name' => '女', 'value' => 6),
);

$code['week'] = array(
  1 => array('name' => '周一', 'value' => 1),
  2 => array('name' => '周二', 'value' => 2),
  3 => array('name' => '周三', 'value' => 3),
  4 => array('name' => '周四', 'value' => 4),
  5 => array('name' => '周五', 'value' => 5),
  6 => array('name' => '周六', 'value' => 6),
  7 => array('name' => '周日', 'value' => 7),
);
