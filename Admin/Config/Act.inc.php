<?php

$act['index'] = array(
    'action' => array(
        'login' => array(
            'allow' => array(ACT_NO_ROLE),
            'deny' => array(),
            'direct' => '?c=index&a=login',
        ),
    ),
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['device'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['school'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['class'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['student'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);


$act['parent'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['article'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['help'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['branch'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['version'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

$act['downimg'] = array(
    'allow' => array(ACT_ADMIN_ROLE),
    'deny' => array(ACT_NO_ROLE, ACT_PARENT_ROLE, ACT_SCHOOL_ADMIN_ROLE),
    'direct' => '?c=index&a=login',
);

?>